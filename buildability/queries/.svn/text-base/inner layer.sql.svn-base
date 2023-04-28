select /*+ ALL_ROWS ORDERED PUSH_SUBQ */
		distinct dpl.part_number part_number,
					dpl.compnt_part_number component_part_number,
				dpl.layup_sequence sequence, 
		      rpad(rtrim(dpl."TOP_LAYER_NUMBER") || '/' || rtrim(dpl."BOTTOM_LAYER_NUMBER"),10) top_bottom_LAYER,
		      rpad(rtrim(dpl."TOP_LAYER_CLAD_WEIGHT") || '/' || rtrim(dpl."BOTTOM_LAYER_CLAD_WEIGHT"),10) as "Top Bottom Clad Weight(OZ)",
				rpad(rtrim(dpl."MIN_TRACE_TOP") || '/' || rtrim(dpl."MIN_TRACE_BTM"),10) top_bottom_trace,
				rpad(rtrim(dpl."MIN_SPACE_TOP") || '/' || rtrim(dpl."MIN_SPACE_BTM"),10) top_bottom_space
from dwhpaRTlayup dpl,
	  dwh_paRT dp,
	  part_version pv
where "PV"."PART_NUMBER" = '@@part_number@@' AND
		"PV"."VERSION" = f_get_part_version2("PV"."PART_NUMBER",10) AND
		dp.part_number = "PV"."PART_NUMBER" and 
		dpl.part_number = "PV"."PART_NUMBER" and 
		dpl.rm_usage in ('1','2') and
		dpl."TOP_LAYER_NUMBER" <> 1 and 
		dpl."BOTTOM_LAYER_NUMBER" <> dp.layer_count
order by dpl.part_number,
			dpl.layup_sequence
