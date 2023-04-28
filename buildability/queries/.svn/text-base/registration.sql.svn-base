select pv.PART_NUMBER,
		 cat.min_annular_ring_inners as "min_annular_ring_inners(inch)",
		 cat.min_annular_ring_outers as "min_annular_ring_outers(inch)",
		 F_get_drill_program_list(pv.PART_NUMBER) Drill_programs
from  cat_fields cat,
		part_version pv
where pv.PART_NUMBER = '@@part_number@@'         
AND   pv.version = f_get_part_version2(pv.PART_NUMBER,10)
AND 	cat."PART_NUMBER"	= "PV"."PART_NUMBER"
AND 	cat."VERSION"	= "PV"."CAT_FIELDS_VERSION"
