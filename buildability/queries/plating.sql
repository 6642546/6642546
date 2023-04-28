select dp.parent_part_number, 
        dp.part_number, 
        dp.subpart_level,
        rpad(f_get_decode('PLATNGTYPE',plv.plating_type),10) surface_finish,
        rpad(f_get_decode_long('MIN_PAT_CU',min_pattern_copper),25) though_hole_cu_spec,
        rpad(f_get_decode('VIA_FILL', cf.via_fill_type),10) via_fill_type,
        F_GET_TOTAL_HOLES_WC("PV"."PART_NUMBER","PV"."DRILL_VERSION_VERSION",'0080') "via_fill_HOLE_COUNT",
        rpad(f_get_microvia(pv.part_number, pv.version),5) microvia,
        rpad(f_get_decode('TIE_BAR',"CF"."TIE_BAR_FLAG"),10) tie_bar,
        "CAT"."MIN_TRACE_WIDTH_OUTERS" "MIN_TRACE_OL",
        "CAT"."MIN_SPACING_OUTERS" "MIN_SPACE_OL",
        "CAT"."MIN_ANNULAR_RING_OUTERS" "ANNULAR_RING_OL"
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