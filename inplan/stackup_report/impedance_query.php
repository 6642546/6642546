<?php
	$imp_query = "select i.item_name
					,IC.MODEL_NAME
					,CL.LAYER_INDEX
					,IC.CUSTOMER_REQUIRED_IMPEDANCE
					,IC.CUST_REQUIRED_IMPED_TOL_PLUS
					,IC.CUST_REQUIRED_IMPED_TOL_MINUS
					,IC.ORIGINAL_TRACE_WIDTH
					,IC.CALCULATED_IMPEDANCE
					,IC.CALCULATED_TRACE_WIDTH
					,(select cop_l.layer_index from items icop,copper_layer cop_l 
							where icop.item_type=3 and icop.item_id=cop_l.item_id and cop_l.revision_id=icop.last_checked_in_rev
							and icop.deleted_in_graph is null and icop.root_id=i.root_id and cop_l.item_id=IC.TOP_MODEL_LAYER_ITEM_ID ) as Top_model_layer
					 ,(select cop_l.layer_index from items icop,copper_layer cop_l 
							where icop.item_type=3 and icop.item_id=cop_l.item_id and cop_l.revision_id=icop.last_checked_in_rev
							and icop.deleted_in_graph is null and icop.root_id=i.root_id and cop_l.item_id=IC.BOTTOM_MODEL_LAYER_ITEM_ID ) as Bot_model_layer
					 ,job.num_layers
					 ,nvl((SELECT double_value
						FROM impedance_constraint_parameter icp where ICP.ITEM_ID=ic.item_id and icp.revision_id=ic.revision_id 
						and ICP.CONSTRAINT_SEQUENTIAL_INDEX = IC.SEQUENTIAL_INDEX and ICP.NAME='xcord2' ) * (case when IC.MODEL_NAME like '%coupled_coplanar%' then 2 else 1 end ),0) as pith
					 ,(SELECT double_value
						FROM impedance_constraint_parameter icp where ICP.ITEM_ID=ic.item_id and icp.revision_id=ic.revision_id 
						and ICP.CONSTRAINT_SEQUENTIAL_INDEX = IC.SEQUENTIAL_INDEX and (ICP.NAME='sig_gnd_spacing' or  lower(ICP.NAME)='original_cs') ) as space
					 ,(SELECT double_value
                        FROM impedance_constraint_parameter icp where ICP.ITEM_ID=ic.item_id and icp.revision_id=ic.revision_id 
                        and ICP.CONSTRAINT_SEQUENTIAL_INDEX = IC.SEQUENTIAL_INDEX and ICP.NAME='original_sig_sig_spacing' ) as s
					,(SELECT double_value
                        FROM impedance_constraint_parameter icp where ICP.ITEM_ID=ic.item_id and icp.revision_id=ic.revision_id 
                        and ICP.CONSTRAINT_SEQUENTIAL_INDEX = IC.SEQUENTIAL_INDEX and lower(ICP.NAME)='original_s'  ) as p_s
				from items i
					,items imp
					,impedance_constraint ic
					,items icl
					,copper_layer cl
					,job
				where i.item_type=2
				    and imp.item_type=9
				    and icl.item_type=3
				    and job.item_id=i.item_id
					and job.revision_id=i.last_checked_in_rev
					and i.root_id=imp.root_id
					and imp.item_id=ic.item_id
					and ic.revision_id=IMP.LAST_CHECKED_IN_REV
					and IMP.DELETED_IN_GRAPH is null
					and i.root_id=icl.root_id
					and icl.item_id=cl.item_id
					and cl.revision_id=icl.LAST_CHECKED_IN_REV
					and icl.DELETED_IN_GRAPH is null
					and IC.TRACE_LAYER_ITEM_ID = CL.ITEM_ID
					and i.item_name='" . $job . "' 
				order by CL.LAYER_INDEX,ic.SEQUENTIAL_INDEX";

?>