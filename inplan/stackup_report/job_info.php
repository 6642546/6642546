<?php 
$sql = "select i.item_name
            ,job.mrp_name
            ,part.name
            ,part.cust_rev_name
            ,users.user_name
            ,job.num_layers
            ,S.CUSTOMER_THICKNESS
            ,S.CUSTOMER_THICKNESS_TOL_PLUS
            ,S.CUSTOMER_THICKNESS_TOL_MINUS
            ,(select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
            and et.type_name='THICKNESS_CALC_METHOD' and ev.enum=";
			if ($site == 'HY') {
				$sql .='job_da.REQ_THICKNESS_CALC_METHOD_';
			} else {
				$sql .='s.THICKNESS_CALC_METHOD';
			}
			
			$sql .=") calc
			,(select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
            and et.type_name='JOB_TYPE' and ev.enum=job.job_type) job_type
			,c.customer_name
			,(select count(imp.ITEM_ID)
				from items imst,
					 IMPEDANCE_CONSTRAINT imp
				where imst.item_type=9
				and i.root_id=imst.root_id
				and imst.item_id= imp.item_id
				and imp.revision_id=imst.last_checked_in_rev
				and imst.DELETED_IN_GRAPH is null) num_imps
		    from items i
            ,job
			,job_da
            ,part
            ,users
            ,items ist
            ,stackup s
			,customer c
        where i.item_type=2
        and i.item_id=job.item_id
        and job.revision_id=i.last_checked_in_rev
        and job.item_id=part.item_id
		and job_da.item_id=job.item_id
        and job_da.revision_id=job.revision_id
        and job.revision_id=part.revision_id
        and job.assigned_operator_id = USERS.USER_ID
		and job.customer_id=c.cust_id
        and ist.item_type = 9
        and ist.root_id=i.root_id
        and ist.item_id=s.item_id
        and s.revision_id=ist.last_checked_in_rev
        and IST.DELETED_IN_GRAPH is null
		and i.item_name='$job'";
?>