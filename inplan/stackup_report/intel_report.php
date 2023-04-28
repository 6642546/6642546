<?php
error_reporting(1);


$job = $_GET['job_name'];
$site = $_GET['site'];
$unit = $_GET['unit'];
$const = $_GET['cst'];
$resin = $_GET['rs'];
if (!$unit) $unit = 'mils';

if (!$conn) require_once("../../oracle_conn.php");
ini_set("max_execution_time","-1"); 

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../../plugins/PHPExcel/PHPExcel.php';

if ($unit == 'mils') {
	$num_format = PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00;
} else {
	$num_format = PHPExcel_Style_NumberFormat::FORMAT_NUMBER_000;
}

$objPHPExcel= new PHPExcel();
$objPHPExcel->getProperties()->setCreator("TTM Inc")
							 ->setLastModifiedBy("TTM Inc")
							 ->setTitle("Stackup Report")
							 ->setSubject("Stackup Report")
							 ->setDescription("Stackup Report")
							 ->setKeywords("Stackup Report")
							 ->setCategory("Stackup Report");
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Sheet1');   
$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('TTM-'.$site.'                         '.'&P'.'                         '.'TTM Company Confidential');
$objPHPExcel->getActiveSheet()->setShowGridlines(false);  
$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
$alignment = $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment();
$alignment->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$alignment->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(15);
$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(12.75);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8.29);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12.29);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15.43);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(23);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(11.14);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(28.43);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(28.43);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(5.14);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(6.86);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(8.71);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(6.86);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(8.71);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(7.71);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(9.14);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(7.71);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(8.71);

place_pic ('logo','A2',0);

//start row.
$l= 5;
$basic_info = array('Customer:','Project Name:','Revision:','Material:','Layer Structure:','Finished Thickness:','Approved Plants:','Date:');

foreach ($basic_info as $i => $value) {
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$l.':B'.$l);
	$objPHPExcel->getActiveSheet()->mergeCells('C'.$l.':L'.$l);
	set_cell_body('A'.$l,$value);
	$l+=1;
}

set_basic_value('C5','Intel');
set_basic_value('C11','MERIX');
set_basic_value('C12',date('Y.m.d',time()));

$l+=1;
$objPHPExcel->getActiveSheet()->setCellValue('D'.$l,'Customer Stack-up');
$objPHPExcel->getActiveSheet()->getStyle('D'.$l)->getFont()->setBold(true);
set_border_medium('D'.$l);
set_cell_color('D',$l,'FFCCFFCC');

$l+=1;
$objPHPExcel->getActiveSheet()->mergeCells('A'.$l.':B'.($l+2));
set_border_thin('A'.$l);
$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.$l), 'A'.$l.':B'.($l+2));
$objPHPExcel->getActiveSheet()->setCellValue('A'.$l,'Layer');
set_table_title('A'.$l);

$cols = array('','C','D','E','F','G');
$basic_title = array('','Cu Weight','Thickness (mils)','Proposed Thickness (mils)','Structure','Via Structure');

for ($i = 1;$i<=5;$i++) {
	$objPHPExcel->getActiveSheet()->mergeCells($cols[$i].$l.':'.$cols[$i].($l+2));
	set_border_thin($cols[$i].$l);
	$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle($cols[$i].$l), $cols[$i].$l.':'.$cols[$i].($l+2)); 
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$i].$l,$basic_title[$i]);
	set_table_title($cols[$i].$l);
}
$objPHPExcel->getActiveSheet()->getRowDimension($l+2)->setRowHeight(25.5);

$table_first = $l;
$l +=3; 

$objPHPExcel->getActiveSheet()->setCellValue('B'.$l,'Soldermask');
$objPHPExcel->getActiveSheet()->setCellValue('D'.$l,'0.50');
$objPHPExcel->getActiveSheet()->setCellValue('E'.$l,'0.40');

set_blue_style('D'.$l);
set_blue_style('E'.$l);

$info_sql =  "select job.num_layers from items i,job where i.item_type=2 and i.item_id=job.item_id and job.revision_id=i.last_checked_in_rev and i.item_name='$job'";
$stid = oci_parse($conn,$info_sql);
$r = oci_execute($stid, OCI_DEFAULT);
$result = oci_fetch_array($stid, OCI_RETURN_NULLS);		
$num_layers = $result[0];
		
/* fetch data */
$l+=1;
include("stackup_query.php");

$rsStk = oci_parse($conn, $sqlStk);

oci_execute($rsStk, OCI_DEFAULT);
while(oci_fetch($rsStk)){
	if (oci_result($rsStk, 6)){
		if (oci_result($rsStk, 6)==='1'){
		     $objPHPExcel->getActiveSheet()->setCellValue('B'.$l,'TOP');
		} elseif (oci_result($rsStk, 6)===$num_layers) {
		     $objPHPExcel->getActiveSheet()->setCellValue('B'.$l,'Bottom');
		}elseif (oci_result($rsStk, 9)=='grd') {
		     $objPHPExcel->getActiveSheet()->setCellValue('B'.$l,'GND');
		}elseif (oci_result($rsStk, 9)=='sig'){
		     $objPHPExcel->getActiveSheet()->setCellValue('B'.$l,'Signal');
		}
	}
	if(oci_result($rsStk, 4) === 'foil'){
		if ($pp){
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$l,'Prepreg');
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$pp);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$l,$pp_overall_thk );
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$l,$pp_press_thk);
			$l+=1;
			$pp = '';
		}
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$l,'L'.oci_result($rsStk, 6));
		$layer_line[oci_result($rsStk, 6)] = $l;
	    $objPHPExcel->getActiveSheet()->setCellValue('C'.$l,oci_result($rsStk, 10).'oz');
		$foil_cu_thk = sprintf("%.2f",oci_result($rsStk, 10)*1.2);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$l,$foil_cu_thk);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$l,sprintf("%.2f",oci_result($rsStk, 11)));
		if (oci_result($rsStk, 6) == 1 || oci_result($rsStk, 6) == $num_layers) {
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 10).'oz'.'+'.'plating');
		}
		$l+=1;
	} elseif(oci_result($rsStk, 4) === 'core') {
		if ($pp){
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$l,'Prepreg');
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$pp);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$l,$pp_overall_thk);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$l,$pp_press_thk);
			$l+=1;
			$pp = '';
		}
		if(oci_result($rsStk, 5)=== "Both"){ 
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$l,'L'.oci_result($rsStk, 6));
			$layer_line[oci_result($rsStk, 6)] = $l;
			if (oci_result($rsStk, 9)=='grd') {
				 $objPHPExcel->getActiveSheet()->setCellValue('B'.$l,'GND');
			}elseif (oci_result($rsStk, 9)=='sig'){
				 $objPHPExcel->getActiveSheet()->setCellValue('B'.$l,'Signal');
			} 
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$l,oci_result($rsStk, 10).'oz'); 
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$l,sprintf("%.3f",oci_result($rsStk, 10)*1.2));
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$l,sprintf("%.3f",oci_result($rsStk, 11)));
			if (oci_result($rsStk, 6) == 1 || oci_result($rsStk, 6) == $num_layers) {
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 10).'oz'.'+'.'plating');
			} else {
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,return_foil_tm(oci_result($rsStk, 3)));
			}
			$top_cu = oci_result($rsStk, 10);
			$l+=1;
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$l,'Core');
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$l,oci_result($rsStk, 7));
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$l,oci_result($rsStk, 7));
			$l+=1;
			if(oci_fetch($rsStk)){
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$l,'L'.oci_result($rsStk, 6));
				$layer_line[oci_result($rsStk, 6)] = $l;
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$l,oci_result($rsStk, 10).'oz');
				if (oci_result($rsStk, 9)=='grd') {
				     $objPHPExcel->getActiveSheet()->setCellValue('B'.$l,'GND');
				}elseif (oci_result($rsStk, 9)=='sig'){
			         $objPHPExcel->getActiveSheet()->setCellValue('B'.$l,'Signal');
				} 
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$l,sprintf("%.3f",oci_result($rsStk, 10)*1.2));
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$l,sprintf("%.3f",oci_result($rsStk, 11)));
				$bot_cu = oci_result($rsStk, 10);
				if (oci_result($rsStk, 6) == 1 || oci_result($rsStk, 6) == $num_layers) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 10).'oz'.'+'.'plating');
				} else {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,return_foil_tm(oci_result($rsStk, 3)));
				}
				$l+=1;
			}
			if ($const) {
				$objPHPExcel->getActiveSheet()->setCellValue('F'.($l-2),oci_result($rsStk, 7).'mil('.$top_cu.'/'.$bot_cu.')core '.oci_result($rsStk, 25));
			}else {
			  $objPHPExcel->getActiveSheet()->setCellValue('F'.($l-2),oci_result($rsStk, 7).'mil('.$top_cu.'/'.$bot_cu.')core');
			}
			
			
		} elseif(oci_result($rsStk, 5)=== "Top"){ 
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$l,'L'.oci_result($rsStk, 6));
			$layer_line[oci_result($rsStk, 6)] = $l;
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$l,oci_result($rsStk, 10).'oz');
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$l,sprintf("%.3f",oci_result($rsStk, 10)*1.2));
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$l,sprintf("%.3f",oci_result($rsStk, 11)));
			$top_cu = oci_result($rsStk, 10);
			if (oci_result($rsStk, 6) == 1 || oci_result($rsStk, 6) == $num_layers) {
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 10).'oz'.'+'.'plating');
			} else {
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,return_foil_tm(oci_result($rsStk, 3)));
			}
			$l+=1;
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$l,'Core');
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$l,oci_result($rsStk, 7));
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$l,oci_result($rsStk, 7));
			
			if ($const) {
				 $objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 7).'mil('.$top_cu.'/0'.')core '.oci_result($rsStk, 25));
			}else {
			    $objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 7).'mil('.$top_cu.'/0'.')core');
			}
			$l+=1;
		} elseif(oci_result($rsStk, 5)=== "Bottom"){ 
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$l,'Core');
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$l,oci_result($rsStk, 7));
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$l,oci_result($rsStk, 7));
			$l+=1;
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$l,'L'.oci_result($rsStk, 6));
			$layer_line[oci_result($rsStk, 6)] = $l;
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$l,oci_result($rsStk, 10).'oz');
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$l,sprintf("%.3f",oci_result($rsStk, 10)*1.2));
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$l,sprintf("%.3f",oci_result($rsStk, 11)));
			if (oci_result($rsStk, 6) == 1 || oci_result($rsStk, 6) == $num_layers) {
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 10).'oz'.'+'.'plating');
			} else {
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,return_foil_tm(oci_result($rsStk, 3)));
			}
			
			if ($const) {
				$objPHPExcel->getActiveSheet()->setCellValue('F'.($l-1),oci_result($rsStk, 7).'mil('.'0/'.oci_result($rsStk, 10).')core '.oci_result($rsStk, 25));
			}else {
			    $objPHPExcel->getActiveSheet()->setCellValue('F'.($l-1),oci_result($rsStk, 7).'mil('.'0/'.oci_result($rsStk, 10).')core');
			}
			$l+=1;
		} else {
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$l,'Dummy Core');
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 7).'mil dummy core');
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$l,oci_result($rsStk, 7));
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$l,oci_result($rsStk, 7));
			$l+=1;
		}
	} else {
			if ($pp){
				if ($resin){
					$pp .= "/" . oci_result($rsStk, 15)."(".oci_result($rsStk, 16).")";
				} else 	$pp .= "/" . oci_result($rsStk, 15);
			}  else {
				if ($resin){
					$pp = oci_result($rsStk, 15)."(".oci_result($rsStk, 16).")";
				} else {
					$pp = oci_result($rsStk, 15);
				}
			}
			
			$pp_press_thk = sprintf("%.2f",oci_result($rsStk, 21));
			$pp_overall_thk = sprintf("%.2f",oci_result($rsStk, 20));
	}
}

$objPHPExcel->getActiveSheet()->setCellValue('B'.$l,'Soldermask');
$objPHPExcel->getActiveSheet()->setCellValue('D'.$l,'0.50');
$objPHPExcel->getActiveSheet()->setCellValue('E'.$l,'0.40');
set_blue_style('D'.$l);
set_blue_style('E'.$l);

$l+=2;
$objPHPExcel->getActiveSheet()->mergeCells('A'.$l.':c'.$l);
set_basic_value('A'.$l,'Finished Thickness (mils)');
$objPHPExcel->getActiveSheet()->setCellValue('D'.$l,'=(SUM(D'.($table_first+3)  .':D'.($l-2).'))');
$objPHPExcel->getActiveSheet()->getStyle('D'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_000);
$objPHPExcel->getActiveSheet()->setCellValue('E'.$l,'=(SUM(E'.($table_first+3)  .':E'.($l-2).'))');
$objPHPExcel->getActiveSheet()->getStyle('E'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_000);
set_border_thin('A'.($table_first+3));
$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.($table_first+3)), 'A'.($table_first+3).':G'.$l);

/* Impedance */
include("impedance_query.php");
$rsImp = oci_parse($conn, ' select distinct CUSTOMER_REQUIRED_IMPEDANCE,CUST_REQUIRED_IMPED_TOL_PLUS ,CUST_REQUIRED_IMPED_TOL_MINUS,substr(MODEL_NAME, 0, 5)  from ('.$imp_query.') order by customer_required_impedance');
oci_execute($rsImp, OCI_DEFAULT);
while(oci_fetch($rsImp)){
	$num_imp +=1;
	$the_imps[$num_imp] = oci_result($rsImp, 1);
	$the_tol[$num_imp] = oci_result($rsImp, 2);
	$imps_mod[$num_imp] = get_model(oci_result($rsImp, 4));
	$imps_value[$num_imp] = oci_result($rsImp, 1).'ohm +/-'.(oci_result($rsImp, 2)*100/oci_result($rsImp, 1)).'%' ;
}
if ($num_imp) {
	$objPHPExcel->getActiveSheet()->mergeCells('H'.$table_first.':H'.($table_first+2));
	set_border_thin('H'.$table_first);
	$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('H'.$table_first), 'H'.$table_first.':H'.($table_first+2));
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$table_first,'Ref.');
	set_table_title('H'.$table_first);
	$imp_start = $table_first;

	$cols = array('','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
	$objPHPExcel->getActiveSheet()->mergeCells('E'.($imp_start-1).':'.$cols[$num_imp*2].($imp_start-1));
	$objPHPExcel->getActiveSheet()->setCellValue('E'.($imp_start-1),'HY Stack-up Information');
	$objPHPExcel->getActiveSheet()->getStyle('E'.($imp_start-1))->getFont()->setBold(true);
	set_cell_color('E',($imp_start-1),'FF993366');

	for ($i = 1;$i<=$num_imp;$i++) {
			$imp_om_col[$the_imps[$i]][$the_tol[$i]] = ($i-1)*2+1;
			$objPHPExcel->getActiveSheet()->mergeCells($cols[($i-1)*2+1].$imp_start.':'.$cols[$i*2].$imp_start);
			$objPHPExcel->getActiveSheet()->setCellValue($cols[($i-1)*2+1].$imp_start,$imps_mod[$i]);
			set_table_title($cols[($i-1)*2+1].$imp_start);
			$objPHPExcel->getActiveSheet()->mergeCells($cols[($i-1)*2+1].($imp_start+1).':'.$cols[$i*2].($imp_start+1));
			$objPHPExcel->getActiveSheet()->setCellValue($cols[($i-1)*2+1].($imp_start+1),$imps_value[$i]);
			set_table_title($cols[($i-1)*2+1].($imp_start+1));
			$objPHPExcel->getActiveSheet()->setCellValue($cols[($i-1)*2+1].($imp_start+2),'Target LW');
			set_table_title($cols[($i-1)*2+1].($imp_start+2));
			$objPHPExcel->getActiveSheet()->setCellValue($cols[$i*2].($imp_start+2),'Finished LW');
			set_table_title($cols[$i*2].($imp_start+2));
		}
		
	$rsImp = oci_parse($conn, $imp_query);
	oci_execute($rsImp, OCI_DEFAULT);
	while(oci_fetch($rsImp)){

		  if (strstr(oci_result($rsImp,2),'single')) {
			$objPHPExcel->getActiveSheet()->setCellValue($cols[$imp_om_col[oci_result($rsImp, 4)][oci_result($rsImp, 5)]].$layer_line[oci_result($rsImp, 3)],oci_result($rsImp, 7));
			$objPHPExcel->getActiveSheet()->setCellValue($cols[$imp_om_col[oci_result($rsImp, 4)][oci_result($rsImp, 5)]+1].$layer_line[oci_result($rsImp, 3)],oci_result($rsImp, 9));
		  } else {
		    $last = $objPHPExcel->getActiveSheet()->getCell($cols[$imp_om_col[oci_result($rsImp, 4)][oci_result($rsImp, 5)]].$layer_line[oci_result($rsImp, 3)])->getValue();
			if ($last) {
			    $objPHPExcel->getActiveSheet()->setCellValue($cols[$imp_om_col[oci_result($rsImp, 4)][oci_result($rsImp, 5)]].($layer_line[oci_result($rsImp, 3)]+1),oci_result($rsImp, 7).'/'.(oci_result($rsImp, 13)-oci_result($rsImp, 7)));
			    $objPHPExcel->getActiveSheet()->setCellValue($cols[$imp_om_col[oci_result($rsImp, 4)][oci_result($rsImp, 5)]+1].($layer_line[oci_result($rsImp, 3)]+1),oci_result($rsImp, 9).'/'.(oci_result($rsImp, 13)-oci_result($rsImp, 9)));
			}else {
			    $objPHPExcel->getActiveSheet()->setCellValue($cols[$imp_om_col[oci_result($rsImp, 4)][oci_result($rsImp, 5)]].$layer_line[oci_result($rsImp, 3)],oci_result($rsImp, 7).'/'.(oci_result($rsImp, 13)-oci_result($rsImp, 7)));
			    $objPHPExcel->getActiveSheet()->setCellValue($cols[$imp_om_col[oci_result($rsImp, 4)][oci_result($rsImp, 5)]+1].$layer_line[oci_result($rsImp, 3)],oci_result($rsImp, 9).'/'.(oci_result($rsImp, 13)-oci_result($rsImp, 9)));
			}
			$objPHPExcel->getActiveSheet()->setCellValue($cols[$imp_om_col[oci_result($rsImp, 4)][oci_result($rsImp, 5)]].'17','Target LW/SP');
			$objPHPExcel->getActiveSheet()->setCellValue($cols[$imp_om_col[oci_result($rsImp, 4)][oci_result($rsImp, 5)]+1].'17','Finished LW/SP');
		  }
		  $objPHPExcel->getActiveSheet()->getColumnDimension($cols[$imp_om_col[oci_result($rsImp, 4)][oci_result($rsImp, 5)]])->setWidth(11.27);
		  $objPHPExcel->getActiveSheet()->getColumnDimension($cols[$imp_om_col[oci_result($rsImp, 4)][oci_result($rsImp, 5)]+1])->setWidth(11.27);
	}
		$styleArray = array(  'borders' => array(  'allborders' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN, ), ), );  
		$objPHPExcel->getActiveSheet()->getStyle('H'.$imp_start.':'.$cols[$num_imp*2].$l)->applyFromArray($styleArray);
		
	$rsImp = oci_parse($conn, ' select distinct LAYER_INDEX,Top_model_layer,Bot_model_layer from ('.$imp_query.') order by LAYER_INDEX');
	oci_execute($rsImp, OCI_DEFAULT);
	while(oci_fetch($rsImp)){
		  if(!oci_result($rsImp, 2)){
			 $cur_ref = "L".oci_result($rsImp, 3);
		  }elseif (!oci_result($rsImp, 3)){
			 $cur_ref = "L".oci_result($rsImp, 2);
		  }else{
			 $cur_ref = "L".oci_result($rsImp, 2)." ".oci_result($rsImp, 3);
		  }
		 $objPHPExcel->getActiveSheet()->setCellValue('H'.$layer_line[oci_result($rsImp, 1)],$cur_ref);
	}	
}

/* Get drill information */
include("drill_query.php");
$rsDrill = oci_parse($conn, $sql);
oci_execute($rsDrill, OCI_DEFAULT);

while(oci_fetch($rsDrill)){
	$drills[$d_index]['start_layer'] = oci_result($rsDrill, 2);
	$drills[$d_index]['end_layer'] = oci_result($rsDrill, 3);
	$drills[$d_index]['tech'] = oci_result($rsDrill, 6);
	if (oci_result($rsDrill, 8) == 'Mechanical') {
		$drills[$d_index]['tech_s'] = 'Mech';
	} elseif (oci_result($rsDrill, 8) == 'Controlled Depth' and oci_result($rsDrill, 4) == 'NPTH') {
		$drills[$d_index]['tech_s'] = 'CDD';
	} else {
		$drills[$d_index]['tech_s'] = oci_result($rsDrill, 8);
	}
	$drills[$d_index]['drill_type'] = oci_result($rsDrill, 4);
	$drills[$d_index]['drill_depth'] = oci_result($rsDrill, 9);
	$drills[$d_index]['vf_type'] = oci_result($rsDrill, 10);

	if (oci_result($rsDrill, 4) == 'NPTH' and oci_result($rsDrill, 8) == 'Controlled Depth') {
		$back_drill = 1;
		$drills[$d_index]['drill_type'] = 'Backdrill';
	}
	if ($site == 'HY') {
		$drills[$d_index]['stack_via'] = oci_result($rsDrill, 12);
	} else {
		$drills[$d_index]['stack_via'] = oci_result($rsDrill, 14);
	}
	if ($drills[$d_index]['stack_via'] == 'Y') $stack_via_count+=1;
	$drills[$d_index]['drill_size_dfm'] = oci_result($rsDrill, 15);
	$layer_count = oci_result($rsDrill, 7);
	$d_index ++;
}

$c_drillpgs = count($drills);
$t_c_drillpgs = $c_drillpgs - $stack_via_count + 1;
$d_index = 0;
$d_off_set_1 = 18;
$d_off_set_2 = 19;
$d_off_step = 30;
if ($c_drillpgs) {
	foreach ($drills as $drill =>$value) {
		$drill_type_pic = 'intel_n_drill';
		if (strstr($value['vf_type'],'Non-Conductive')) {
			$drill_type_pic = 'intel_n_drill';
		} elseif (strstr($value['vf_type'],'Conductive')) {
			$drill_type_pic = 'intel_n_drill';
		} 
		if ($value['end_layer'] > $value['start_layer']) {
			$s_layer = $value['start_layer'];
			$e_layer = $value['end_layer'];
			if ($value['tech'] == 2) $drill_type_pic  = 'intel_laser_up';
		} else {
			$s_layer = $value['end_layer'];
			$e_layer = $value['start_layer'];
			if ($value['tech'] == 2) $drill_type_pic  = 'intel_laser_dn';
		}
		#2007
		//$total_height =  (abs($layer_cell[$value['end_layer']]-$layer_cell[$value['start_layer']])) * 8.5 *1.005* 26.5/21;
		$total_height =  (abs($layer_line[$value['end_layer']]-$layer_line[$value['start_layer']])) * 12.8 *1.006* 29/21;
		
		/* check free space */
		$has_free_space = 0;
		foreach ($free_spaces as $fs =>$s_val) {
			if ($s_val['total_height'] > $total_height and ($s_layer> $s_val['end_layer'] or $e_layer< $s_val['start_layer'])) {
				if ($s_layer > 1) {
					place_pic($drill_type_pic,$s_val['col'].($layer_line[$s_layer]),10,$s_val['off_set'],$total_height,25);
				} else {
					place_pic($drill_type_pic,$s_val['col'].($layer_line[$s_layer]),1,$s_val['off_set'],$total_height,25);
				}
				$has_free_space = 1;
				unset($free_spaces[$fs]);
				break;
			}
		}
		if ($value['stack_via'] == 'Y') {
			place_pic($drill_type_pic,'G'.($layer_line[$s_layer]),1,$d_off_set_1,$total_height,25);
		} elseif ($has_free_space == 0) {
			if ($s_layer > $layer_count / 2) {
					place_pic($drill_type_pic,'G'.($layer_line[$s_layer]),10,$d_off_set_1,$total_height,25);
			} else {
				place_pic($drill_type_pic,'G'.($layer_line[$s_layer]),1,$d_off_set_1,$total_height,25);
			}
			if ($e_layer - $s_layer +1 <> $num_layers) {
				$free_spaces_index +=1;
				$free_spaces[$free_spaces_index]['col'] = 'G'; 
				$free_spaces[$free_spaces_index]['start_layer'] = $s_layer;
				$free_spaces[$free_spaces_index]['end_layer'] = $e_layer; 						
				$free_spaces[$free_spaces_index]['col'] = 'G'; 
				$free_spaces[$free_spaces_index]['off_set'] = $d_off_set_1;
				$free_spaces[$free_spaces_index]['total_height'] = (abs($layer_line[$num_layers]-$layer_line[1])) * 8.5 *1.005* 26.5/21 - $total_height;
			}
			$d_off_set_1 += $d_off_step;
			$d_index ++;
		}	
	}
}





$filename =$job. ".xls";
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$filename);
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
//$objWriter->save($filename);


function get_model($model){
	if ($model=='singl' or $model=='Singl'){
         return 'Single End';
	}else {
         return 'Diffential';
	}
}

function set_blue_style($cell){
  Global $objPHPExcel;
  set_table_title($cell);
  $objPHPExcel->getActiveSheet()->getStyle($cell)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
}

function set_basic_value($cell,$value) {
    Global $objPHPExcel;
	$objPHPExcel->getActiveSheet()->setCellValue($cell,$value);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
}

function set_border_thin ($cell) {
	Global $objPHPExcel;
	$objPHPExcel->getActiveSheet()->getStyle($cell)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
}

function set_border_medium ($cell) {
	Global $objPHPExcel;
	$objPHPExcel->getActiveSheet()->getStyle($cell)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
}

function set_cell_body ($cell,$value) {
	Global $objPHPExcel;
	$objPHPExcel->getActiveSheet()->setCellValue($cell,$value);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->getFont()->setBold(true);
}

function set_table_title($cell){
   Global $objPHPExcel;
   $objPHPExcel->getActiveSheet()->getStyle($cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
   $objPHPExcel->getActiveSheet()->getStyle($cell)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
   $objPHPExcel->getActiveSheet()->getStyle($cell)->getFont()->setBold(true);
   $objPHPExcel->getActiveSheet()->getStyle($cell)->getAlignment()->setWrapText(true);
  
}

function place_pic ($pic,$d_index,$offsety = 0,$offsetx = 0,$height = 0,$width = 0) {
	Global $objPHPExcel;
	$gdImage = @imagecreatefrompng(dirname(__FILE__).'/images/'.$pic.'.png');
	$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
	$objDrawing->setImageResource($gdImage);
	$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG);
	$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_PNG);
	$objDrawing->setResizeProportional(false);
	$objDrawing->setCoordinates($d_index);
	if ($height) {
		$objDrawing->setHeight($height.'pt');
	}
	if ($width) {
		$objDrawing->setWidth($width.'pt');
	}	
	$objDrawing->setOffsetY($offsety);
	$objDrawing->setOffsetX($offsetx);
	$objDrawing->getShadow()->setVisible(false); 
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
}

function set_fill_color ($col,$row,$color) {
	Global $objPHPExcel;
	$objStyle=$objPHPExcel->getActiveSheet()->getStyle($col.$row);
	$objFill = $objStyle->getFill();
	$objFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objFill->getStartColor()->setARGB($color);
}

function set_cell_color ($col,$l,$color) {
	set_fill_color($col,$l,$color);
}

function return_foil_tm ($des) {
	if (strstr($des,'RTF')) {
		return 'RTF';
	} elseif (strstr($des,'HTE')) {
		return 'HTE';
	} elseif (strstr($des,'HPS')) {
		return 'HPS';
	}elseif (strstr($des,'VLP')) {
		return 'VLP';
	}elseif (strstr($des,'VSP')) {
		return 'VSP';
	} elseif (strstr($des,'HVLP')) {
		return 'HVLP';
	} elseif (strstr($des,'HS-VSP')) {
		return 'HS-VSP';
	} elseif (strstr($des,'HS-M1-VSP')) {
		return 'HS-M1-VSP';
	} elseif (strstr($des,'HS-M2-VSP')) {
		return 'HS-M2-VSP';
	} elseif (strstr($des,'HS1-M2-VSP')) {
		return 'HS1-M2-VSP';
	} elseif (strstr($des,'Mitsui HTE')) {
		return 'Mitsui HTE';
	} elseif (strstr($des,'Mitsui RTF')) {
		return 'Mitsui RTF';
	} 
}



?>