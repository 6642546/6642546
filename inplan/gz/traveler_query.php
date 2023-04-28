<html>
<?php 

$sqltrav_header="
select 
rownum,i.item_name as job_name,job_da.customer_pn_,Customer.customer_name,endcustomer.customer_code
             ,root_id
             ,job.NUM_LAYERS
			 ,job_da.DFM_CREATOR_
             ,job_da.DFM_CREATED_DATE_
             ,job_da.DFM_CHECKER_
             ,job_da.DFM_CHECK_DATE_
            -- ,job_da.SO_UNIT_
			  ,(select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
										and et.type_name='SO_UNIT_' and ev.enum=job_da.SO_UNIT_) as SO_UNIT_
             ,job_da.INPUT_NUMBER_
             ,job_da.FINISH_SURFACE_ERP_
             ,job_da.PROFILE_ERP_
             ,job_da.PPAP_LEVEL_
             ,job_da.LAMINATION_
			 ,job_da.ERP_UNIT_PER_ARRAY_
			 ,(select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
										and et.type_name='LAMINATION_' and ev.enum=job_da.LAMINATION_) as LAMINATION_
			 ,(select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
										and et.type_name='SPECIAL_ITEM_' and ev.enum=job_db.SPECIAL_ITEM_) as SPECIAL_ITEM_
             ,job_da.DATA_SHARE_
             ,JOB_DB.ERP_TECHNOLOGY_LEVEL_
			 ,JOB_DB.CI_IMP_REMARK_
             ,job_da.PRODUCT_LEVEL_
             ,job_da.DRAWING_REF_
             ,job_da.FABRICATION_SPEC_
             ,job_da.ERP_INNER_MIN_LW_SPC_
             ,job_da.ERP_OUTER_MIN_LW_SPC_
             ,job_da.ERP_HOLE_MIN_SIZE_TOL_
             ,JOB_DB.ERP_HOLE_MIN_SIZE_TOL_LASER_
             ,job_da.ERP_PROD_SPEC_07_OTHER_
			 ,job_db.DRILL_UNIT_
            -- ,job_da.BOARD_TYPE_SPECIAL_
			  ,(select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
										and et.type_name='BOARD_TYPE_SPECIAL_' and ev.enum=job_da.BOARD_TYPE_SPECIAL_) as BOARD_TYPE_SPECIAL_
			 ,(select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
										and et.type_name='ROHS_COMPLIANCE_' and ev.enum=job_da.ROHS_COMPLIANCE_) as ROHS_COMPLIANCE_
			,(select ev.value from enum_types et,enum_values ev where et.enum_type=ev.enum_type 
										and et.type_name='CATEGORY_' and ev.enum=job_da.CATEGORY_) as CATEGORY_
             ,job_da.PRODUCT_CODE_
             ,job_da.BASIC_JOB_DATA_REMARK_
			 ,(select value from FIELD_ENUM_TRANSLATE where fldname='CUST_REQ_VENDOR_' and enum=job_da.CUST_REQ_VENDOR_) as CUST_REQ_VENDOR_
			 ,(select value from FIELD_ENUM_TRANSLATE where fldname='CUST_REQ_FAMILY_' and enum=job_da.CUST_REQ_FAMILY_) as CUST_REQ_FAMILY_
			 ,(select value from FIELD_ENUM_TRANSLATE where fldname='CUST_REQ_VENDOR_' and enum=job_da.CUST_REQ_VENDOR_2_) as CUST_REQ_VENDOR_2_
			 ,(select value from FIELD_ENUM_TRANSLATE where fldname='CUST_REQ_FAMILY_' and enum=job_da.CUST_REQ_FAMILY_2_) as CUST_REQ_FAMILY_2_
			 ,(select value from FIELD_ENUM_TRANSLATE where fldname='CUST_REQ_VENDOR_' and enum=job_da.CUST_REQ_VENDOR_3_) as CUST_REQ_VENDOR_3_
			 ,(select value from FIELD_ENUM_TRANSLATE where fldname='CUST_REQ_FAMILY_' and enum=job_da.CUST_REQ_FAMILY_3_) as CUST_REQ_FAMILY_3_
			  ,(select value from FIELD_ENUM_TRANSLATE where fldname='PPAP_LEVEL__' and enum=job_db.PPAP_LEVEL__) as PPAP_LEVEL__
             ,job_da.BASIC_MATERIAL_REMARK_
			 ,job_db.CORNER1_
			 ,job_db.CORNER2_
			 ,job_db.CORNER3_
			 ,job_db.CORNER4_
                       from items i
						,part
						,job
						,job_da 
                        ,job_db
						,customer
						,customer endcustomer
					    where i.item_type=2
                      	and i.root_id=(select root_id from items where item_name='".$job."' and item_type=2)
					   -- and item_name='".$job."'
						and i.item_id=job.item_id
						and job.revision_id=i.LAST_CHECKED_IN_REV
						and job.item_id=job_da.item_id
						and job.customer_id=customer.cust_id
						and job.end_customer_id=endcustomer.cust_id
						and job.revision_id=job_da.revision_id
                        and job_da.item_id=job_db.item_id
                        and job_da.revision_id=job_db.revision_id
						and job.item_id=part.item_id
						and job.revision_id=part.revision_id
						and job.odb_site_id=1
                        and length(item_name)=12";
 $max_cu="select max(REQUIRED_CU_WEIGHT) as max_cu  from rpt_copper_layer_list where roo_id=i.root_id=(select root_id from items where item_name='".$job."' and item_type=2)";
 $ci_note="select C.CI_ORDERING_INDEX, C.PRE_INSTANTIATED_STRING from RPT_JOB_TOOL_LIST a,CI_NOTE c,CAM_INSTRUCTION b where 
                    a.item_id=b.item_id
					and a.revision_id=b.revision_id
					and b.item_id=c.item_id
					and b.revision_id=c.revision_id
					and b.SEQUENTIAL_INDEX=c.SEQUENTIAL_INDEX
					and a.root_id=(select root_id from items where item_name='".$job."' and item_type=2)";

$trav_gz="select job_name, root_id,process_name,item_id, revision_id, operation_code, description, traveler_ordering_index, sequential_index,    order_num from rpt_job_trav_sect_list where root_id=(select root_id from items where item_name='".$job."' and item_type=2) order BY order_num, traveler_ordering_index";
$trav_note="select note_string from  note_trav_sect where item_id= @@item_id@@  and revision_id= @@revision_id@@  and SECTION_SEQUENTIAL_INDEX= @@SEQUENTIAL_INDEX@@ order by section_ordering_index ";
$trav_attr="select description, value_as_string from  attr_trav_sect where item_id= @@item_id@@  and revision_id= @@revision_id@@  and SECTION_SEQUENTIAL_INDEX= @@SEQUENTIAL_INDEX@@ order by section_ordering_index ";
?>
</html>