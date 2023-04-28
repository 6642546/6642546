select parts.part_number,
        parts.customer_part_number,
         parts.owning_customer_name,
         parts.deliverables_customer_name,
         parts.layer_count,
         parts.flat_size,
         parts.aspect_ratio,
	 parts.microvia_aspect_ratio,
         parts.images_per_flat,
         parts.exceeds_usable_area,
         parts.build_class_level,
         parts.inspection_class_level,
         parts.final_nom_thickness as "Final Nom Thickness(inch)",
         parts.final_thickness_tol as "final_thickness_tol(+/-inches)",
         parts.subs,
--         parts.old_subs,
         parts.down_rev_parts,
         parts.down_rev_part_number,
         parts.down_rev_6_month_yield,
         parts.BOM_COMPARE,
         parts.down_rev_last_order_number,
         parts.down_rev_last_order_ship_date,
         parts.material_types,
         parts.technology_types,
         (SELECT to_number(attribute9)
                        FROM inv.mtl_system_items_b@ERP_PROD
                        WHERE segment1 = RTRIM(parts.part_number)
                        AND organization_id = 105) planned_yield
from    (select part.part_number,
                cf.customer_part_number,
                 rpad(f_get_customer_name(cf.owning_customer),25) owning_customer_name,
                 rpad(f_get_customer_name(nvl(cf.deliverables_customer,cf.deliverables_customer2)),25) deliverables_customer_name,
                 F_GET_LAYER_CNT(part.part_number, 10) layer_count,
                 rpad(F_GET_FLAT_SIZE(part.part_number),10) flat_size,
                 cf.aspect_ratio,
		 cf.microvia_aspect_ratio,
                 cf.images_per_flat,
                 cf.exceeds_usable_area,
                 rpad(f_get_decode('SAMPLLEVEL',decode(cf.sampling_level,'2',cf.sampling_level,'3',cf.sampling_level,decode(rtrim(F_GET_SAMPLING_LEVEL(pv.part_number, pv.version)),'','2',null,'2',F_GET_SAMPLING_LEVEL(pv.part_number, pv.version)))),10) build_class_level,
                 rpad(f_get_decode('INSPLEV',customer.inspection_level),10) inspection_class_level,
                 f_get_board_thickness(pv.part_number, pv.board_thickness_version, '8') final_nom_thickness,
                 (f_get_board_thickness(pv.part_number, pv.board_thickness_version, '6') - f_get_board_thickness(pv.part_number, pv.board_thickness_version, '7')) / 2 final_thickness_tol,
                 rtrim(replace(part.down_rev_part_number,'-')) down_rev_part_number,
                 F_SUBPART_LIST(part.PART_NUMBER) subs,
--                 decode(sign(instr(F_GET_SUBS_REC(part.PART_NUMBER, 0),'~')),1,substr(F_GET_SUBS_REC(part.PART_NUMBER, 0),instr(F_GET_SUBS_REC(part.PART_NUMBER, 0),'~')+1,rtrim(length(F_GET_SUBS_REC(part.PART_NUMBER, 0)))),'')             old_subs,
                 F_GET_DOWN_REV_PARTS_REC(part.PART_NUMBER, 0) down_rev_parts,
                  rpad(F_GET_LAST_MFG_JOB(rtrim(replace(part.down_rev_part_number,'-')), 10),10) down_rev_last_mfg_job,
                 wo.order_number down_rev_last_order_number,
                 wo.ship_date down_rev_last_order_ship_date,
                 F_get_material_types_list(part.PART_NUMBER) material_types,
                 F_get_technology_types(part.part_number) technology_types,
                 F_COMPARE_BOM_W_DOWN_REV_PART(part.PART_NUMBER, rtrim(replace(part.down_rev_part_number,'-'))) BOM_COMPARE,
                 F_GET_PART_YIELD(rtrim(replace(part.down_rev_part_number,'-')),183) down_rev_6_month_yield
        from     customer,
                work_order wo,
                common_fields cf,
                part_version pv,
                part
        where part.part_number = '@@part_number@@'
        and    pv.part_number (+) = part.part_number
        and    pv.version (+) = f_get_part_version2(part.part_number,10)
        and    cf.part_number (+) = pv.part_number
        and    cf.version (+) = pv.common_fields_version 
        and    customer.customer_number (+) = cf.owning_customer
        and    wo.order_number (+) = rpad(F_GET_LAST_MFG_JOB(rtrim(replace(part.down_rev_part_number,'-')), 10),10) 
        and    wo.status (+) = 'C'
        and    wo.ship_date (+) is not null ) parts