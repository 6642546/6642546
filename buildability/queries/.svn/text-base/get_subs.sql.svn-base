select 	decode(sign(instr(F_GET_SUBS_REC(part.PART_NUMBER, 0),'~')),1,substr(F_GET_SUBS_REC(part.PART_NUMBER, 0),instr(F_GET_SUBS_REC(part.PART_NUMBER, 0),'~')+1,rtrim(length(F_GET_SUBS_REC(part.PART_NUMBER, 0)))),'') subs
		from 	customer,
				work_order wo,
				common_fields cf,
				part_version pv,
				part
		where part.part_number = '@@part_number@@'
		and	pv.part_number (+) = part.part_number
		and	pv.version (+) = f_get_part_version2(part.part_number,10)
		and	cf.part_number (+) = pv.part_number
		and	cf.version (+) = pv.common_fields_version 
		and	customer.customer_number (+) = cf.owning_customer
		and	wo.order_number (+) = rpad(F_GET_LAST_MFG_JOB(rtrim(replace(part.down_rev_part_number,'-')), 10),10) 
		and	wo.status (+) = 'C'
		and	wo.ship_date (+) is not null
