<?php 
$sql = "select i.item_name
			,DP.START_INDEX
			,DP.END_INDEX
			,(select ev.value from enum_values ev,enum_types et where ev.enum_type=et.enum_type
                and et.type_name='PLATE_TYPE' and EV.ENUM=DP.PLATE_TYPE ) DRILL_TYPE
			,DPD.EPOXY_FILL_
			,dp.DRILL_TECHNOLOGY
			,job.num_layers
		from items i
			,job
			,items idp
			,drill_program dp
			,drill_program_da dpd
		where i.item_type=2
		and i.item_id=job.item_id
		and job.revision_id=i.last_checked_in_rev
		and idp.item_type=5
		and i.root_id=idp.root_id
		and idp.item_id=dp.item_id
		and dp.revision_id=idp.last_checked_in_rev
		and IDP.DELETED_IN_GRAPH is null
		and dp.item_id=dpd.item_id
        and dp.revision_id=dpd.revision_id
		and i.item_name='$job'";
?>