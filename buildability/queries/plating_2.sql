select 	 OL_IMP.part_number,
			 OL_IMP.layup_sequence,
			 OL_IMP.component_part_number,
			 OL_IMP.layer_count,
			 rpad(decode(trim(OL_IMP.top_bot_layer),'/',null,OL_IMP.top_bot_layer),10) top_bot_layer,
			 rpad(decode(trim(OL_IMP.top_bot_test_type),'/',null,OL_IMP.top_bot_test_type),25) top_bot_test_type,
			 rpad(decode(trim(OL_IMP.top_bot_target),'/',null,OL_IMP.top_bot_target),10) top_bot_target,
			 rpad(decode(trim(OL_IMP.top_bot_tolerance),'/',null,OL_IMP.top_bot_tolerance),10) top_bot_tolerance
from	(select part_layup.part_number,
			 part_layup.component_part_number,
			 part_layup.layer_count,
			 part_layup.layup_sequence,
			 tdr_top.layer tdr_top_layer,
			 tdr_bot.layer tdr_bot_layer,
			 f_get_decode('TDRTESTTYP',tdr_top.test_type) tdr_top_test_type,
			 f_get_decode('TDRTESTTYP',tdr_bot.test_type) tdr_bot_test_type,
			 tdr_top.target tdr_top_target,
			 tdr_bot.target tdr_bot_target,
			 tdr_top.tolerance tdr_top_tolerance,
			 tdr_bot.tolerance tdr_bot_tolerance,
			 rpad(rtrim(tdr_top.layer) || '/' || rtrim(tdr_bot.layer),10) top_bot_layer,
			 rpad(rtrim(f_get_decode('TDRTESTTYP',tdr_top.test_type))  || '/' || rtrim(f_get_decode('TDRTESTTYP',tdr_bot.test_type)),25) top_bot_test_type,
			 rpad(rtrim(tdr_top.target) || '/' || rtrim(tdr_bot.target),10) top_bot_target,
			 rpad(rtrim(tdr_top.tolerance) || '/' || rtrim(tdr_bot.tolerance),10) top_bot_tolerance
	from  (select /*+ ALL_ROWS ORDERED PUSH_SUBQ */
					distinct dpl.part_number part_number,
								dpl.compnt_part_number component_part_number,
							dpl.part_class,
							dpl.rm_usage,
							dp.layer_count,
							dpl.layup_sequence, 
							dpl."TOP_LAYER_NUMBER",
							dpl."BOTTOM_LAYER_NUMBER"
			from dwhpaRTlayup dpl,
				  dwh_paRT dp,
				  part_version pv
			where "PV"."PART_NUMBER" = '@@part_number@@' AND
					"PV"."VERSION" = f_get_part_version2("PV"."PART_NUMBER",10) AND
					dp.part_number = "PV"."PART_NUMBER" and 
					dpl.parent_part_number = "PV"."PART_NUMBER" and 
--					dpl.rm_usage in ('1','2') and
					(dpl."TOP_LAYER_NUMBER" = 1 or 
					dpl."BOTTOM_LAYER_NUMBER" = dp.layer_count)
			order by dpl.part_number,
						dpl.layup_sequence) part_layup,
			(select  "TDR_DATA"."PART_NUMBER" part_number,
						"TDR_DATA"."SIGNAL_LAYER" layer,
						"TDR_DATA"."TEST_TYPE" test_type,
						"TDR_DATA"."TARGET" target,
						"TDR_DATA"."TOLERANCE" tolerance
			 from	  "TDR_DATA",
					  dwh_paRT dp,
					  dwhpaRTlayup dpl,
					  part_version pv
			where "PV"."PART_NUMBER" = '@@part_number@@' AND
					"PV"."VERSION" = f_get_part_version2("PV"."PART_NUMBER",10) AND
					dpl.parent_part_number = "PV"."PART_NUMBER" and 
					dp.part_number = "PV"."PART_NUMBER" and 
--					dpl.rm_usage in ('1','2') AND
					"TDR_DATA"."PART_NUMBER" = "PV"."PART_NUMBER" and
					"TDR_DATA"."TDR_VERSION_VERSION" = "PV"."TDR_VERSION_VERSION" and
					"TDR_DATA"."SIGNAL_LAYER" = dpl."TOP_LAYER_NUMBER" and
					dpl."TOP_LAYER_NUMBER" = 1 and
					"TDR_DATA"."TEST_TYPE" in ('1','7')) tdr_top,
			(select  "TDR_DATA"."PART_NUMBER" part_number,
						"TDR_DATA"."SIGNAL_LAYER" layer,
						"TDR_DATA"."TEST_TYPE" test_type,
						"TDR_DATA"."TARGET" target,
						"TDR_DATA"."TOLERANCE" tolerance
			 from	  "TDR_DATA",
					  dwh_paRT dp,
					  dwhpaRTlayup dpl,
					  part_version pv
			where "PV"."PART_NUMBER" = '@@part_number@@' AND
					"PV"."VERSION" = f_get_part_version2("PV"."PART_NUMBER",10) AND
					dpl.parent_part_number = "PV"."PART_NUMBER" and 
					dp.part_number = "PV"."PART_NUMBER" and 
					dpl."BOTTOM_LAYER_NUMBER" = dp.layer_count and
--					dpl.rm_usage in ('1','2') AND
					"TDR_DATA"."PART_NUMBER" = "PV"."PART_NUMBER" and
					"TDR_DATA"."TDR_VERSION_VERSION" = "PV"."TDR_VERSION_VERSION" and
					"TDR_DATA"."SIGNAL_LAYER" = dpl."BOTTOM_LAYER_NUMBER" and
					"TDR_DATA"."TEST_TYPE" in ('1','7')) tdr_bot
	where  tdr_top.part_number (+) = part_layup.part_number
	AND	 tdr_top.layer (+) = part_layup.top_layer_number
	and    tdr_bot.part_number (+) = part_layup.part_number
	AND	 tdr_bot.layer (+) = part_layup.bottom_layer_number) OL_IMP
where OL_IMP.tdr_top_layer is not null
or    OL_IMP.tdr_bot_layer is not null
or		OL_IMP.tdr_top_test_type is not null
or		OL_IMP.tdr_bot_test_type is not null
or		OL_IMP.tdr_top_target is not null
or		OL_IMP.tdr_bot_target is not null
or		OL_IMP.tdr_top_tolerance is not null
or		OL_IMP.tdr_bot_tolerance is not null
order by 1,2,4
