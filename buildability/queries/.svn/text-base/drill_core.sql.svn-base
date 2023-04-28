select part.part_number,
                 rpad(f_get_customer_name(cf.owning_customer),25) owning_customer_name,
                 rpad(f_get_customer_name(nvl(cf.deliverables_customer,cf.deliverables_customer2)),25) deliverables_customer_name,
                 rpad(f_get_decode('SAMPLLEVEL',decode(cf.sampling_level,'2',cf.sampling_level,'3',cf.sampling_level,decode(rtrim(F_GET_SAMPLING_LEVEL(pv.part_number, pv.version)),'','2',null,'2',F_GET_SAMPLING_LEVEL(pv.part_number, pv.version)))),10) build_class_level,
                 rpad(f_get_decode('INSPLEV',customer.inspection_level),10) inspection_class_level,
                 F_get_material_types_list(part.PART_NUMBER) material_types,
		 f_get_board_thickness(pv.part_number, pv.board_thickness_version, '8') final_nom_thickness,
		 F_GET_LAYER_CNT(part.part_number, 10) layer_count
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
        and    wo.ship_date (+) is not null