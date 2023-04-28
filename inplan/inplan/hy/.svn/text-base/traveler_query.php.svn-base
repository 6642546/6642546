<html>
<?php 
$sqltrav ="
SELECT root.item_name as job_name
      ,ip.item_name as traveler_name
      ,nvl(ts.description, ms.description) as operation_code
      ,nvl(ms.operation_code, 'none') as s_description
      ,nts.note_string
      ,ts.traveler_ordering_index as traveler_ordering_index
      ,nts.section_ordering_index as ORDER_NUM
	  ,MS.WORK_CENTER_CODE
	  ,ts.item_id
	  ,(select ev.value from enum_values ev,enum_types et 
        where ev.enum_type=et.enum_type and ev.enum=PROCESS.PROC_SUBTYPE
        and et.type_name='PROCESS_SUBTYPE') as proc_subtype_t
	  ,process.mrp_name
	  ,ts.sequential_index
FROM " . $schema . "items root 
  inner join (" . $schema . "items ip 
	inner join process inner join process_da on process.item_id=process_da.item_id and process.revision_id=process_da.revision_id on ip.item_id=process.item_id and process.revision_id=ip.last_checked_in_rev and ip.deleted_in_graph is null
    inner join (" . $schema . "links lnk 
      inner join ((" . $schema . "tool t 
        inner join " . $schema . "items it 
        on t.item_id = it.item_id 
        and t.revision_id = it.last_checked_in_rev
          and it.item_type = 8) 
        inner join (" . $schema . "traveler tr 
          inner join ((" . $schema . "trav_sect ts 
            left outer join " . $schema . "mrp_step ms 
            on ts.mrp_step_item_id = ms.item_id 
            and ts.mrp_step_revision_id = ms.revision_id)
            left outer join " . $schema . "note_trav_sect nts 
            on ts.item_id = nts.item_id 
            and ts.revision_id = nts.revision_id 
            and ts.sequential_index = nts.section_sequential_index)
          on tr.item_id = ts.item_id 
          and tr.revision_id = ts.revision_id) 
        on t.item_id = tr.item_id 
        and t.revision_id = tr.revision_id 
        and t.tool_type = 7) 
      on lnk.points_to = t.item_id 
      and lnk.link_type = 14) 
    on ip.item_id = lnk.item_id 
    and ip.deleted_in_graph is null 
    and ip.item_type = 7) 
  on root.item_id = ip.root_id 
  and root.deleted_in_graph is null 
  and root.item_type = 2 
WHERE root.item_name = '" . $job . "'
and (process.mrp_name like '".$process."%' or (process.mrp_name like '950%' and ".substr($process,0,3)."='950')) 
order by CASE WHEN mrp_name like '750%' THEN SUBSTR(mrp_name, 1, 10) 
                       WHEN proc_subtype_t = 'Inner Layers' THEN '1'
                       WHEN proc_subtype_t = 'Rout Material' THEN '2'
                       WHEN proc_subtype_t = 'Buried Via' THEN '3'
                       WHEN proc_subtype_t = 'Subassembly' THEN '4'
                       WHEN proc_subtype_t = 'Final Assembly' THEN '5'
                       WHEN proc_subtype_t = 'MASSLAM' THEN '5'
                       WHEN proc_subtype_t = 'MASSLAMD' THEN '5'
                       ELSE '6'
                  END, CASE WHEN mrp_name like '750%' THEN ip.item_name
                  ELSE LPAD(TO_CHAR(PROCESS_SEQ_), 2, '0') END,traveler_name, traveler_ordering_index,ORDER_NUM";
?>
</html>