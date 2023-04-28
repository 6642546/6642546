select nc.description drill_route_program, 
			dp.stack_height, 
--			decode(rtrim(dp.drill_program),'DP',rpad(f_get_decode('DRILLENTRY',"CF"."DRILL_ENTRY_MATERIAL"),10),'DV',rpad(f_get_decode('DRILLENTRY',"CF"."DRILL_ENTRY_MATERIAL_VF"),10),'DX',rpad(f_get_decode('DRILLENTRY',"CF"."DRILL_ENTRY_MATERIAL_PS"),10),rpad('',10)) Drill_entry,
--			decode(rtrim(dp.drill_program),'DP',rpad(f_get_decode('DRILLBACKR',"CF"."DRILL_ENTRY_BACKER"),10),'DV',rpad(f_get_decode('DRILLBACKR',"CF"."DRILL_ENTRY_BACKER_VF"),10),'DX',rpad(f_get_decode('DRILLBACKR',"CF"."DRILL_ENTRY_BACKER_PS"),10),rpad('',10)) Drill_backer,
			decode(rtrim(dp.drill_program),'DP',rpad(f_get_decode('DRILLENTRY',"CF"."DRILL_ENTRY_MATERIAL"),10),'DV',rpad(f_get_decode('DRILLENTRY',"CF"."DRILL_ENTRY_MATERIAL_VF"),10),rpad('',10)) Drill_entry,
			decode(rtrim(dp.drill_program),'DP',rpad(f_get_decode('DRILLBACKR',"CF"."DRILL_ENTRY_BACKER"),10),'DV',rpad(f_get_decode('DRILLBACKR',"CF"."DRILL_ENTRY_BACKER_VF"),10),rpad('',10)) Drill_backer,
			decode(sign(sum(decode(rtrim(nc.type),'B',1,'A',1,0))),1,'Yes','No') CDD,
			TO_CHAR(null) rout_bit_diameter
from  nc_program nc,
		drill_program dp,
		common_fields cf,
      part_version pv
where pv.part_number = '@@part_number@@' 
--		pv.PART_NUMBER  = IN_part_number         
AND   pv.version = f_get_part_version2(pv.PART_NUMBER,10)
and	cf.part_number = pv.part_number
and	cf.version = pv.common_fields_version
AND	dp."PART_NUMBER" = pv."PART_NUMBER"
AND	dp."DRILL_PROGRAM_VERSION_VERSION" = pv."DRILL_PROGRAM_VERSION_VERSION"
and 	nc.program (+) = dp.drill_program
group by pv.part_number,
			dp.drill_program, 
			nc.description, 
			dp.stack_height, 
			decode(rtrim(dp.drill_program),'DP',rpad(f_get_decode('DRILLENTRY',"CF"."DRILL_ENTRY_MATERIAL"),10),'DV',rpad(f_get_decode('DRILLENTRY',"CF"."DRILL_ENTRY_MATERIAL_VF"),10),rpad('',10)),
			decode(rtrim(dp.drill_program),'DP',rpad(f_get_decode('DRILLBACKR',"CF"."DRILL_ENTRY_BACKER"),10),'DV',rpad(f_get_decode('DRILLBACKR',"CF"."DRILL_ENTRY_BACKER_VF"),10),rpad('',10)),
			TO_CHAR(null)
union ALL
  SELECT  nc.description drill_route_program, 
			"ROUTE".stack_height, 
			TO_CHAR(null) Drill_entry, 
			TO_CHAR(null) Drill_backer,
			TO_CHAR(null) CDD,
			rpad("DECODE"."LONG_DESCRIPTION",15) "ROUT_BIT_DIAMETER"
    FROM "DECODE",
				nc_program nc,
			  "ROUTE",   
         common_fields, 
         "PART_VERSION"
   WHERE "PART_VERSION"."PART_NUMBER" = '@@part_number@@' and
--			pv.PART_NUMBER  = IN_part_number         
         ( "PART_VERSION"."VERSION" 	= f_get_part_version2("PART_VERSION"."PART_NUMBER",10)) and
         ( "ROUTE"."PART_NUMBER" = "PART_VERSION"."PART_NUMBER" ) AND  
         ( "ROUTE"."ROUTE_VERSION_VERSION" = "PART_VERSION"."ROUTE_VERSION_VERSION" ) and
			  "DECODE"."DECODE_TYPE_ID" (+) = 'ROUTBITDIA' AND
			  "DECODE"."SHORT_DESCRIPTION" (+) = "ROUTE"."BIT_DIAMETER" and
			 common_fields.part_number  = part_version.part_number and
			 common_fields.version = part_version.common_fields_version and
			 common_fields.part_status not in ('QT') and
			lower("DECODE"."LONG_DESCRIPTION") like '%deg%' and
			nc.program (+) = "ROUTE"."ROUT_PROGRAM"
order by 1
