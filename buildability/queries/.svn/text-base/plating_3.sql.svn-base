select dp.parent_part_number, 
        dp.part_number, 
        dp.subpart_level,
        rtrim(dplt.compnt_part_number) || '/' || rtrim(dplb.compnt_part_number) top_bottom_component_part,
        rpad(rtrim(dplt."TOP_LAYER_NUMBER") || '/' || rtrim(dplb."BOTTOM_LAYER_NUMBER"),10) top_bottom_LAYER,
        rpad(rtrim(dplt.top_layer_description) || '/' || rtrim(dplb.bottom_layer_description),10) OL_plane,
        rpad(rtrim(dplt."TOP_LAYER_CLAD_WEIGHT") || '/' || rtrim(dplb."BOTTOM_LAYER_CLAD_WEIGHT"),10) top_bottom_CLAD_WEIGHT,
        rpad(rtrim(dplt."MIN_TRACE_TOP") || '/' || rtrim(dplb."MIN_TRACE_BTM"),10) top_bottom_trace,
        rpad(rtrim(dplt."MIN_SPACE_TOP") || '/' || rtrim(dplb."MIN_SPACE_BTM"),10) top_bottom_space
from     dwhpartlayup dplt,
        dwhpartlayup dplb,
      plate_version plv,
      common_fields cf,
      cat_fields cat,
      part_version pv,
        (select     dpl.parent_part_number, 
                    dpl.part_number, 
                    subpart_level, 
                    min(dpl.row_number) top_row_number, 
                    max(dpl.row_number) bot_row_number
        from dwhpartlayup dpl 
        where dpl.parent_part_number = '@@part_number@@'
        and (dpl.part_class = 'Raw Matl'
          or (dpl.part_class = 'Sub Part' and dpl.compnt_subpart_type in ('BB','CV','IL')))
        group by dpl.parent_part_number, dpl.part_number, subpart_level
        order by subpart_level) dp
where  dplt.parent_part_number = dp.parent_part_number
and    dplt.part_number = dp.part_number
and    dplt.row_number = dp.top_row_number
and      dplb.parent_part_number = dp.parent_part_number
and    dplb.part_number = dp.part_number
and    dplb.row_number = dp.bot_row_number 
and     "PV"."PART_NUMBER" = dp.part_number 
AND    "PV"."VERSION" = f_get_part_version2(dp.part_number,10) 
AND    cf.part_number = pv.part_number 
and     cf.version = pv.common_fields_version 
and    cat.part_number = pv.part_number 
and     cat.version = pv.cat_fields_version 
and    plv.part_number = pv.part_number 
and    plv.version = pv.plate_version_version