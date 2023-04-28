select decode(sign(sum(decode(rtrim(nc.type),'B',1,'A',1,0))),1,'Yes','No') CDD
    ,dp.stack_height
    ,decode(rtrim(dp.drill_program),'DP',rpad(f_get_decode('DRILLENTRY',"CF"."DRILL_ENTRY_MATERIAL"),10),'DV',rpad(f_get_decode('DRILLENTRY',"CF"."DRILL_ENTRY_MATERIAL_VF"),10),rpad('',10)) Drill_entry,
    decode(rtrim(dp.drill_program),'DP',rpad(f_get_decode('DRILLBACKR',"CF"."DRILL_ENTRY_BACKER"),10),'DV',rpad(f_get_decode('DRILLBACKR',"CF"."DRILL_ENTRY_BACKER_VF"),10),rpad('',10)) Drill_backer
from  nc_program nc,
        drill_program dp,
    common_fields cf,
      part_version pv
where pv.part_number = '@@part_number@@'         
AND   pv.version = f_get_part_version2(pv.PART_NUMBER,10)
and    cf.part_number = pv.part_number
and    cf.version = pv.common_fields_version
AND    dp."PART_NUMBER" = pv."PART_NUMBER"
AND    dp."DRILL_PROGRAM_VERSION_VERSION" = pv."DRILL_PROGRAM_VERSION_VERSION"
and     nc.program (+) = dp.drill_program
group by pv.part_number,
			dp.drill_program, 
			dp.stack_height, 
			decode(rtrim(dp.drill_program),'DP',rpad(f_get_decode('DRILLENTRY',"CF"."DRILL_ENTRY_MATERIAL"),10),'DV',rpad(f_get_decode('DRILLENTRY',"CF"."DRILL_ENTRY_MATERIAL_VF"),10),rpad('',10)),
			decode(rtrim(dp.drill_program),'DP',rpad(f_get_decode('DRILLBACKR',"CF"."DRILL_ENTRY_BACKER"),10),'DV',rpad(f_get_decode('DRILLBACKR',"CF"."DRILL_ENTRY_BACKER_VF"),10),rpad('',10))
