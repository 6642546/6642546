select   rpad(f_get_decode('PLATNGTYPE',plv.plating_type),10) surface_finish
	 ,rpad(F_GET_DECODE_long('SOLDERMASK',"CF"."SOLDERMASK_TYPE"),25) "SOLDERMASK_TYPE"
from plate_version plv,
      common_fields cf,
      cat_fields cat,
      part_version pv
where "PV"."PART_NUMBER" = '@@part_number@@' AND
        "PV"."VERSION" = f_get_part_version2("PV"."PART_NUMBER",10) AND
        cf.part_number = pv.part_number and 
        cf.version = pv.common_fields_version and
        cat.part_number = pv.part_number and 
        cat.version = pv.cat_fields_version and
        plv.part_number = pv.part_number and
        plv.version = pv.plate_version_version
order by "PV"."PART_NUMBER"