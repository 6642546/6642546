<html>
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
	--  ,M.GENERIC_NAME
	 ,decode(M.GENERIC_NAME, Null,(select item_name from items  where item_id=m.item_id and LAST_CHECKED_IN_REV=m.revision_id ),M.GENERIC_NAME ) as GENERIC_NAME
	  ,PP.RESIN_PERCENTAGE
	  ,(select ev.value from enum_values ev,enum_types et where ev.enum_type=et.enum_type and et.type_name='MATERIAL_FAMILY' and ev.enum=M.FAMILY) as family
	  ,pp.TG_MIN as TG
	  ,PP.RESIN_PERMITTIVITY PERMITTIVITY
	  ,sm.MATERIAL_DESC_ as description
from (" . $schema . "items root
      inner join (" . $schema . "items ism 
        inner join ((" . $schema . "(select sma.*,sda.MATERIAL_DESC_ from  segment_material sma, segment_material_da sda
where sma.item_id=sda.item_id and sma.revision_id=sda.revision_id) sm 
          inner join ((" . $schema . "material m 
            left outer join " . $schema . "prepreg pp
            on m.item_id = pp.item_id 
            and m.revision_id = pp.revision_id) 
            left outer join " . $schema . "core core
            on m.item_id = core.item_id 
            and m.revision_id = core.revision_id)
          on sm.material_item_id = m.item_id
          and sm.material_revision_id = m.revision_id) 
            inner join " . $schema . "stackup_seg ss 
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
   --   ,m2.mrp_rev_description as description
	  ,decode(m2.mrp_rev_description, '', sm2.MATERIAL_DESC_, sm2.MATERIAL_DESC_) as description
      ,decode(ss2.SEGMENT_TYPE, 1, 'prepreg', 0, 'core', 3, 'foil', 'Other') as Seg_Type
      ,decode(ss2.COPPER_SIDES, 3, 'Both', 2, 'Bottom', 1, 'Top', 'None') as Cu_Sides
      ,cl2.LAYER_INDEX as CuLayer
      ,decode(ss2.SEGMENT_TYPE, 0, core2.laminate_thickness, 0) as lam_thickness
      ,decode(ss2.SEGMENT_TYPE, 0, core2.laminate_thickness, 0) as embedded_lam_thickness
      ,decode(cl2.copper_type, 0, 'sig', 1, 'mix', 2, 'grd', 'blk') as CuType
	  ,round(CL2.REQUIRED_CU_WEIGHT/28.3495,3) as BaseCu
	  ,round(CL2.REQUIRED_CU_WEIGHT * 1.2/28.3495,3) as TotalCu
      ,decode(cl2.LAYER_ORIENTATION, 0, 'dn', 1, 'up', 2, 'blk') as EtchUp
      ,ss2.STACKUP_SEG_INDEX as Seg_Index
      ,sm2.sequential_index as seq_index
	 -- ,M2.GENERIC_NAME
	  ,decode(M2.GENERIC_NAME, Null,(select item_name from items  where item_id=m2.item_id and LAST_CHECKED_IN_REV=m2.revision_id ),M2.GENERIC_NAME ) as GENERIC_NAME
	  ,0 RESIN_PERCENTAGE
	  --,(select ev.value from enum_values ev,enum_types et where ev.enum_type=et.enum_type and et.type_name='MATERIAL_FAMILY' and ev.enum=M2.FAMILY) as family
	 ,decode(M2.FAMILY, 0,((select ev.value from enum_values ev,enum_types et where ev.enum_type=et.enum_type and et.type_name='MATERIAL_FAMILY' and ev.enum=ss2.family_) ),(select ev.value from enum_values ev,enum_types et where ev.enum_type=et.enum_type and et.type_name='MATERIAL_FAMILY' and ev.enum=M2.FAMILY) ) as family
	  ,CORE2.TG_MIN as TG
	 -- ,CORE2.LAMINATE_PERMITTIVITY PERMITTIVITY
      ,decode(CORE2.LAMINATE_PERMITTIVITY ,0,to_number( (select lkb.value 
       from items lk,lookup_table_ergo lka,lookup_table_entry_ergo lkb 
        where(lk.item_type = 50 And lk.item_id = lka.item_id And lk.last_checked_in_rev = lka.revision_id) 
       and lka.item_id=lkb.item_id and lka.revision_id=lkb.revision_id and lk.item_name like '%Hooks:Core_Dk%' 
        and key like '%Dk%' 
        and key like '%'||(select ev.value from enum_values ev,enum_types et where ev.enum_type=et.enum_type and et.type_name='MATERIAL_FAMILY' and ev.enum=ss2.family_)
        ||core2.LAMINATE_THICKNESS||decode(ss2.CONSTRUCTION,Null,ss2.CONSTRUCTION1,ss2.CONSTRUCTION)||'%')),CORE2.LAMINATE_PERMITTIVITY ) as PERMITTIVITY
	  ,sm2.MATERIAL_DESC_ as description
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
            ,(select ev.value from enum_values ev,enum_types et where ev.enum_type=et.enum_type and et.type_name='CONSTRUCTION_DEFAULT_' and ev.enum=sega.CONSTRUCTION_DEFAULT_) as  CONSTRUCTION
            , (select ev.value from enum_values ev,enum_types et where ev.enum_type=et.enum_type and et.type_name='CONSTRUCTION_SPECIAL_' and ev.enum=sega.CONSTRUCTION_SPECIAL_) as  CONSTRUCTION1
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
  
order by seg_index, seq_index, culayer
";
?>
</html>