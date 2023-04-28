<?php 
$sqlDrill = "
    SELECT   dbl.job_name, 
                  dbl.pcb_count bit_pcb_count,
                  dhi.pcb_count hole_pcb_count,
                  dhi.panel_count,
                  dbl.array_step_hole_count,
                  dbl.panel_step_hole_count,
                  dbl.generic_name,
                  dbl.bit_size, 
				  dhi.NAME,
                  dhi.remark_,
                  dhi.finished_size,
                  dhi.finished_size_tol_plus,
                  dhi.finished_size_tol_minus,
                  dhi.finished_length_tol_plus,
                  dhi.finished_length_tol_minus,
                  dhi.finished_length,
                  (select ev.value from enum_values ev,enum_types et where ev.enum_type=et.enum_type and et.type_name='NPTH_TYPE_' and ev.enum=dhi.npt_hole_type_) as npt_hole_type_,
                  dhi.actual_drill_size,
                  dbl.hole_index job_drill_hole_index,
                  dhi.hole_index drill_hole_index,
                  dhi.counter_sink_angle_,
                  dbl.drill_program_start_index,
                  dhi.counter_bore_depth_req_,
                  dbl.corner_radius,
                  dhi.TYPE,
                  dbl.drill_program_drill_technology,
                  dbl.drill_hole_sequential_index,
                  dbl.drill_program_name,
                  jptl.proc_mrp_name
             FROM rpt_drill_hole_info dhi 
                    inner join (rpt_job_drill_bit_list dbl 
                      inner join rpt_job_process_tool_list jptl 
                      on dbl.job_name = jptl.job_name 
                        AND dbl.tool_name = jptl.tool_name) 
                    on dbl.drill_program_item_id = dhi.item_id
                      AND dbl.drill_program_revision_id = dhi.revision_id
                      AND dbl.drill_hole_sequential_index = dhi.sequential_index
            WHERE dbl.job_name ='$job'
			and jptl.proc_mrp_name like '$process%'
            ORDER BY job_drill_hole_index, NAME
";
?>