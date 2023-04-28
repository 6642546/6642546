<html>
<?php 
$sqlDrill = "
    select drill_program_name as name
          ,drill_progr_DRILL_TECHNOLOGY_t
          ,drill_progr_drill_type_t
          ,drill_progr_plate_type_t
          ,round(copper_plating_thickness, 2)
          ,drill_program_start_index
          ,drill_program_end_index
          ,round(drill_program_drill_depth, 2)
          ,max_finished_aspect_ratio
          ,panel_stack_count
          ,round(finished_size, 2)
          ,round(finished_size_tol_plus,2) || '/-' || round(finished_size_tol_minus, 2) as tolerance
          ,drill_hole_type_t
          ,round(actual_drill_size, 2)
          ,pcb_count
          ,panel_count

    from " . $schema . "RPT_JOB_DRILL_BIT_LIST where job_name = '$job'
    order by name, actual_drill_size
";
?>
</html>