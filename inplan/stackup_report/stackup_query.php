<?php 
$sqlStk = "
select root.ITEM_NAME as JobName
      ,m.mrp_name
    --  ,m.mrp_rev_description as description
	 ,decode(m.mrp_rev_description, '', sm.MATERIAL_DESC_, m.mrp_rev_description) as description
      ,decode(ss.SEGMENT_TYPE, 1, 'prepreg', 0, 'core', 3, 'foil', 'Other') as Seg_Type
      ,decode(ss.COPPER_SIDES, 3, 'Both', 2, 'Bottom', 1, 'Top', 'None') as Cu_Sides
      ,0 as CuLayer
      ,decode(ss.SEGMENT_TYPE, 1, pp.raw_thickness, 0, core.LAMINATE_THICKNESS, 0) as lam_thickness
      ,decode(ss.SEGMENT_TYPE, 1, ss.overall_thickness, 0, core.LAMINATE_THICKNESS, 0) as embedded_lam_thickness
      ,'blk' as CuType
      ,0 as BaseCu
      ,0 as TotalCu
      ,'blk' as EtchUp
      ,ss.STACKUP_SEG_INDEX as Seg_Index
      ,sm.sequential_index as seq_index
	 -- ,M.GENERIC_NAME
	  ,decode(M.GENERIC_NAME, Null,(select item_name from items  where item_id=m.item_id and LAST_CHECKED_IN_REV=m.revision_id ),M.GENERIC_NAME ) as GENERIC_NAME
	  ,PP.RESIN_PERCENTAGE
	 -- ,(select ev.value from enum_values ev,enum_types et where ev.enum_type=et.enum_type and et.type_name='MATERIAL_FAMILY' and ev.enum=M.FAMILY) as family	
     ,(select lkb.value
       from items lk,lookup_table_ergo lka,lookup_table_entry_ergo lkb ,field_enum_translate fld
        where(lk.item_type = 50 And lk.item_id = lka.item_id And lk.last_checked_in_rev = lka.revision_id) 
       and lka.item_id=lkb.item_id and lka.revision_id=lkb.revision_id and  lk.item_name like '%AutomaticStackup:PP_Inplan_Werp%' 
       and intname='MATERIAL' and fldname='FAMILY' and fld.enum=M.FAMILY and lkb.key like  fld.value||'<|>%') as family
	  ,pp.TG_MIN as TG
	  ,PP.RESIN_PERMITTIVITY PERMITTIVITY
	  ,ss.PRESSED_THICKNESS
	  ,ss.OVERALL_THICKNESS
	  ,0 as COPPER_USAGE
	  ,sm.seg_ordering_index
	  ,decode(ss.SEGMENT_TYPE, 0, ''|| round(core.TOP_FOIL_CU_WEIGHT/28.3495,3)||' oz / ' || round(core.BOT_FOIL_CU_WEIGHT/28.3495,3)||' oz','')  etch_cu_des
	  ,(select cd.construction_ from core_da cd where cd.item_id=core.item_id and cd.revision_id=core.revision_id) construction
	  ,M.SHEET_SIZE_X
      ,M.SHEET_SIZE_Y";
	  if ($site == 'GZ' || $site == 'ZS') { 
		$sqlStk .= ",'Width' GRAIN_DIRECTION_";
	  } else {
		$sqlStk .= ",decode(ss.SEGMENT_TYPE, 0,(select ev.value from core_da cd,enum_types et,enum_values ev where cd.item_id=core.item_id and cd.revision_id=core.revision_id 
        and ev.enum_type=et.enum_type and ev.enum=cd.GRAIN_DIRECTION_ and et.type_name='GRAIN_DIRECTION_'),1,(select ev.value from prepreg_da pd,enum_types et,enum_values ev where pd.item_id=pp.item_id 
        and pd.revision_id=pp.revision_id 
        and ev.enum_type=et.enum_type and ev.enum=pd.GRAIN_DIRECTION_ and et.type_name='GRAIN_DIRECTION_'),'') GRAIN_DIRECTION_";
	  }
	  $sqlStk .= "
	  ,CORE.TG_MAX
	  ,pp.tg_max pp_tg
	   ,(select ev.value from enum_values ev,enum_types et where ev.enum_type=et.enum_type and et.type_name='VENDOR' and ev.enum=M.vendor) as vendor
	  ,0 c_job_layer
	  ,ss.item_id
	  ,ss.lmsize_
      ,pp.GLASS_DISSIPATION_FACTOR as df
from (" . $schema . "items root
      inner join (" . $schema . "items ism 
        inner join ((" . $schema . "(select sma.*,sda.MATERIAL_DESC_ from  segment_material sma, segment_material_da sda
where sma.item_id=sda.item_id and sma.revision_id=sda.revision_id and sma.sequential_index=sda.sequential_index) sm 
          inner join ((" . $schema . "material m 
            left outer join " . $schema . "prepreg pp
            on m.item_id = pp.item_id 
            and m.revision_id = pp.revision_id) 
            left outer join " . $schema . "core core
            on m.item_id = core.item_id 
            and m.revision_id = core.revision_id)
          on sm.material_item_id = m.item_id
          and sm.material_revision_id = m.revision_id) 
            inner join ( select seg1.*,seg2.lmsize_ from stackup_seg seg1, stackup_seg_da seg2 where seg1.item_id=seg2.item_id and seg1.revision_id=seg2.revision_id ) ss 
            on ss.ITEM_ID = sm.ITEM_ID 
            and ss.revision_id = sm.REVISION_ID) 
          on ism.ITEM_ID = sm.ITEM_ID
          and ism.LAST_CHECKED_IN_REV = sm.REVISION_ID) 
        on root.ITEM_ID = ism.ROOT_ID) 
          inner join (" . $schema . "items istk 
            inner join " . $schema . "stackup stk 
            on istk.ITEM_ID = stk.ITEM_ID 
            and istk.LAST_CHECKED_IN_REV = stk.REVISION_ID) 
          on root.ITEM_ID = istk.ROOT_ID
where root.ITEM_NAME = '$job'
  and root.item_type = 2
  and istk.item_type = 9
  and ism.item_type = 10
  and ss.copper_sides = 0
  and root.deleted_in_graph is null
  and istk.deleted_in_graph is null
  and ism.deleted_in_graph is null

union

select root2.ITEM_NAME as JobName
      ,m2.mrp_name
     -- ,m2.mrp_rev_description as description
	  ,decode(m2.mrp_rev_description, '', sm2.MATERIAL_DESC_, sm2.MATERIAL_DESC_) as description
      ,decode(ss2.SEGMENT_TYPE, 1, 'prepreg', 0, 'core', 3, 'foil', 'Other') as Seg_Type
      ,decode(ss2.COPPER_SIDES, 3, 'Both', 2, 'Bottom', 1, 'Top', 'None') as Cu_Sides
      ,decode(cl2.copper_type,3,0,cl2.DESIGN_LAYER_INDEX) as CuLayer
     -- ,decode(ss2.SEGMENT_TYPE, 0, core2.laminate_thickness, 0) as lam_thickness
    --  ,decode(ss2.SEGMENT_TYPE, 0, core2.laminate_thickness, 0) as embedded_lam_thickness
	  ,decode(ss2.SEGMENT_TYPE, 0, to_number(substr(M2.GENERIC_NAME ,0,instr(M2.GENERIC_NAME,'+')-1)),0) as lam_thickness
	  ,decode(ss2.SEGMENT_TYPE, 0, to_number(substr(M2.GENERIC_NAME ,0,instr(M2.GENERIC_NAME,'+')-1)),0) as embedded_lam_thickness
      ,decode(cl2.copper_type, 0, 'sig', 1, 'mix', 2, 'grd', 'blk') as CuType
	  ,round(CL2.REQUIRED_CU_WEIGHT/28.3495,3) as BaseCu";
	  if ($site == 'GZ' || $site == 'ZS') {
		 $sqlStk .= ",round(CL2.REQUIRED_CU_WEIGHT*1.2/28.3495,3)";
		/*$sqlStk .= ",decode(ss2.SEGMENT_TYPE,3, foil2.THICKNESS, 0, decode(cl2.LAYER_ORIENTATION, 0, core2.BOT_FOIL_THICKNESS, 1, core2.TOP_FOIL_THICKNESS, 2, 0) ,0) ";*/
	  } else {
		$sqlStk .= ",round(cla2.TOTAL_CU_THICKNESS_,3)";
	  }
	  $sqlStk .= " as TotalCu
      ,decode(cl2.LAYER_ORIENTATION, 0, 'dn', 1, 'up', 2, 'blk') as EtchUp
      ,ss2.STACKUP_SEG_INDEX as Seg_Index
      ,sm2.sequential_index as seq_index
	 -- ,M2.GENERIC_NAME
	   ,decode(M2.GENERIC_NAME, Null,(select item_name from items  where item_id=m2.item_id and LAST_CHECKED_IN_REV=m2.revision_id ),M2.GENERIC_NAME ) as GENERIC_NAME
	  ,0 RESIN_PERCENTAGE
	 -- ,(select ev.value from enum_values ev,enum_types et where ev.enum_type=et.enum_type and et.type_name='MATERIAL_FAMILY' and ev.enum=M2.FAMILY) as family
	 -- ,decode(M2.FAMILY, 0,((select ev.value from enum_values ev,enum_types et where ev.enum_type=et.enum_type and et.type_name='MATERIAL_FAMILY' and ev.enum=ss2.family_) ),(select ev.value from enum_values ev,enum_types et where ev.enum_type=et.enum_type and et.type_name='MATERIAL_FAMILY' and ev.enum=M2.FAMILY) ) as family
	   ,decode(M2.FAMILY, 0,
          (select lkb.value
          from items lk,lookup_table_ergo lka,lookup_table_entry_ergo lkb ,field_enum_translate fld
           where(lk.item_type = 50 And lk.item_id = lka.item_id And lk.last_checked_in_rev = lka.revision_id) 
          and lka.item_id=lkb.item_id and lka.revision_id=lkb.revision_id and  lk.item_name like '%AutomaticStackup:Core_Inplan_Werp%' 
          and intname='STACKUP_SEG' and fldname='FAMILY_' and fld.enum=ss2.family_ and lkb.key like  fld.value||'<|>%')
         ,(select lkb.value
          from items lk,lookup_table_ergo lka,lookup_table_entry_ergo lkb ,field_enum_translate fld
           where(lk.item_type = 50 And lk.item_id = lka.item_id And lk.last_checked_in_rev = lka.revision_id) 
          and lka.item_id=lkb.item_id and lka.revision_id=lkb.revision_id and  lk.item_name like '%AutomaticStackup:Core_Inplan_Werp%' 
          and intname='MATERIAL' and fldname='FAMILY' and fld.enum=M2.FAMILY and lkb.key like  fld.value||'<|>%') )as family
  ,CORE2.TG_MIN as TG
	 -- ,CORE2.LAMINATE_PERMITTIVITY PERMITTIVITY
	  ,decode(CORE2.LAMINATE_PERMITTIVITY ,0,to_number( (select lkb.value 
       from items lk,lookup_table_ergo lka,lookup_table_entry_ergo lkb 
        where(lk.item_type = 50 And lk.item_id = lka.item_id And lk.last_checked_in_rev = lka.revision_id) 
       and lka.item_id=lkb.item_id and lka.revision_id=lkb.revision_id and ( lk.item_name like '%Hooks:Core_Dk%' or lk.item_name like '%GZ_Material:Core_Dk_New%')
        and key like '%Dk%' 
        and key like '%'||(select ev.value from enum_values ev,enum_types et where ev.enum_type=et.enum_type and et.type_name='MATERIAL_FAMILY' and ev.enum=ss2.family_)
        ||core2.LAMINATE_THICKNESS||decode(ss2.CONSTRUCTION1,'N/A',decode(ss2.CONSTRUCTION,'N/A','',ss2.CONSTRUCTION),ss2.CONSTRUCTION1)||'<%')),CORE2.LAMINATE_PERMITTIVITY ) as PERMITTIVITY
	  ,ss2.PRESSED_THICKNESS
	  ,ss2.OVERALL_THICKNESS
	  ,cl2.COPPER_USAGE
	  ,sm2.seg_ordering_index
	  ,'' etch_cu_des
	 -- ,(select cd.construction_ from core_da cd where cd.item_id=core2.item_id and cd.revision_id=core2.revision_id) construction
	  , decode((select cd.construction_ from core_da cd where cd.item_id=core2.item_id and cd.revision_id=core2.revision_id),Null,decode(ss2.CONSTRUCTION1,'N/A',ss2.CONSTRUCTION,ss2.CONSTRUCTION1),'T',decode(ss2.CONSTRUCTION1,'N/A',ss2.CONSTRUCTION,ss2.CONSTRUCTION1),(select cd.construction_ from core_da cd where cd.item_id=core2.item_id and cd.revision_id=core2.revision_id)) construction
	  ,M2.SHEET_SIZE_X
      ,M2.SHEET_SIZE_Y";
	  if ($site == 'GZ' || $site == 'ZS') { 
		$sqlStk .= ",'Width' GRAIN_DIRECTION_";
	  } else {
		$sqlStk .= ",(select ev.value from core_da cd,enum_types et,enum_values ev where cd.item_id=core2.item_id and cd.revision_id=core2.revision_id 
        and ev.enum_type=et.enum_type and ev.enum=cd.GRAIN_DIRECTION_ and et.type_name='GRAIN_DIRECTION_') GRAIN_DIRECTION_";
	  }
	  $sqlStk .= ",CORE2.TG_MAX
	  ,0 pp_tg
	  ,(select ev.value from enum_values ev,enum_types et where ev.enum_type=et.enum_type and et.type_name='VENDOR' and ev.enum=M2.vendor) as vendor
	  ,cl2.layer_index c_job_layer
	  ,ss2.item_id
	  ,ss2.lmsize_
      ,decode(CORE2.LAMINATE_DISSIPATION_FACTOR ,0,to_number( (select lkb.value 
       from items lk,lookup_table_ergo lka,lookup_table_entry_ergo lkb 
        where(lk.item_type = 50 And lk.item_id = lka.item_id And lk.last_checked_in_rev = lka.revision_id) 
       and lka.item_id=lkb.item_id and lka.revision_id=lkb.revision_id and lk.item_name like '%Hooks:Core_Df%' 
        and key like '%Df%' 
        and key like '%'||(select ev.value from enum_values ev,enum_types et where ev.enum_type=et.enum_type and et.type_name='MATERIAL_FAMILY' and ev.enum=ss2.family_)
        ||core2.LAMINATE_THICKNESS||decode(ss2.CONSTRUCTION1,'N/A',ss2.CONSTRUCTION,ss2.CONSTRUCTION1)||'%')),CORE2.LAMINATE_DISSIPATION_FACTOR ) as df
from (" . $schema . "items root2 
      inner join ((" . $schema . "items ism2 inner join (" . $schema . "PUBLIC_GRAPHS_LINKS lnksscl2 inner join (" . $schema . "items icl2 inner join (" . $schema . "copper_layer cl2 inner join " . $schema . "copper_layer_da cla2 on cl2.ITEM_ID = cla2.item_id and cl2.REVISION_ID = cla2.REVISION_ID) on icl2.ITEM_ID = cl2.ITEM_ID and icl2.LAST_CHECKED_IN_REV = cl2.REVISION_ID) on lnksscl2.POINTS_TO = icl2.ITEM_ID) on lnksscl2.ITEM_ID = ism2.ITEM_ID)
        inner join ((" . $schema . "(select sma.*,sda.MATERIAL_DESC_ from  segment_material sma, segment_material_da sda
where sma.item_id=sda.item_id and sma.revision_id=sda.revision_id) sm2 
          inner join ((" . $schema . "material m2 
            left outer join " . $schema . "foil foil2 
            on m2.item_id = foil2.item_id 
            and m2.revision_id = foil2.revision_id) 
            left outer join " . $schema . "core core2
            on m2.item_id = core2.item_id 
            and m2.revision_id = core2.revision_id) 
          on sm2.material_item_id = m2.item_id
          and sm2.material_revision_id = m2.revision_id) 
            inner join (select seg.*,sega.family_
           ,(select value from field_enum_translate  where intname='STACKUP_SEG' and  fldname='CONSTRUCTION_DEFAULT_' and enum=sega.CONSTRUCTION_DEFAULT_) as  CONSTRUCTION
           , (select value from field_enum_translate  where intname='STACKUP_SEG' and fldname='CONSTRUCTION_SPECIAL_' and enum=sega.CONSTRUCTION_SPECIAL_) as  CONSTRUCTION1
            , sega.lmsize_
			from  stackup_seg seg,stackup_seg_da sega where seg.item_id=sega.item_id and seg.revision_id=sega.revision_id) ss2 
            on ss2.ITEM_ID = sm2.ITEM_ID 
            and ss2.revision_id = sm2.REVISION_ID) 
          on ism2.ITEM_ID = sm2.ITEM_ID
          and ism2.LAST_CHECKED_IN_REV = sm2.REVISION_ID) 
        on root2.ITEM_ID = ism2.ROOT_ID) 
          inner join (" . $schema . "items istk2 
            inner join " . $schema . "stackup stk2
            on istk2.ITEM_ID = stk2.ITEM_ID 
            and istk2.LAST_CHECKED_IN_REV = stk2.REVISION_ID) 
          on root2.ITEM_ID = istk2.ROOT_ID

where root2.ITEM_NAME = '$job'
  and root2.item_type = 2
  and lnksscl2.link_type in (22,23)
  and istk2.item_type = 9
  and ism2.item_type = 10
  and icl2.item_type = 3
  
order by seg_index, seg_ordering_index
";
?>