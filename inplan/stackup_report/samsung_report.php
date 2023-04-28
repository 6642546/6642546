<?php
error_reporting(1);
$job = $_GET['job_name'];
$site = $_GET['site'];
$unit = $_GET['unit'];
if (!$unit) $unit = 'mils';

if (!$conn) require_once("../../oracle_conn.php");

ini_set("max_execution_time","-1"); 

include("job_info.php");
$rsInfo = oci_parse($conn, $sql);
oci_execute($rsInfo, OCI_DEFAULT);
while(oci_fetch($rsInfo)){ 
	$user = ucwords(strtolower(oci_result($rsInfo, 5)));
	$cust_pn = oci_result($rsInfo, 3) . ' REV ' . oci_result($rsInfo, 4);
	$via_pn = oci_result($rsInfo, 2);
	$num_layers = oci_result($rsInfo, 6);
	$cust_thk =  sprintf("%.3f",oci_result($rsInfo, 7)*0.0254);
	$customer = oci_result($rsInfo, 12);
	if (oci_result($rsInfo, 8) <> oci_result($rsInfo, 9)) {
			$tol = '+' . sprintf("%.3f",oci_result($rsInfo, 8)*0.0254).'/-'.sprintf("%.3f",oci_result($rsInfo, 9)*0.0254);
	} else {
			$tol ='+/-'.round((round(oci_result($rsInfo, 9),3)*100/round(oci_result($rsInfo, 7),3))).'%';
	}
}

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../../plugins/PHPExcel/PHPExcel.php';

if ($unit == 'mils') {
	$num_format = PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00;
} else {
	$num_format = PHPExcel_Style_NumberFormat::FORMAT_NUMBER_000;
}

$objPHPExcel= new PHPExcel();

$objPHPExcel->getProperties()->setCreator("Viasystems Inc")
							 ->setLastModifiedBy("Viasystems Inc")
							 ->setTitle("Stackup Report")
							 ->setSubject("Stackup Report")
							 ->setDescription("Stackup Report")
							 ->setKeywords("Stackup Report")
							 ->setCategory("Stackup Report");
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Sheet1');   
$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('Viaystems-'.$site.'                         '.'&P'.'                         '.'Viaystems Company Confidential');
$objPHPExcel->getActiveSheet()->setShowGridlines(false);  
$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
$alignment = $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment();
$alignment->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$alignment->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(15);
$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(12.75);


$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(27);
$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(19.5);
$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(19.5);
$objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(33);
$objPHPExcel->getActiveSheet()->getRowDimension('5')->setRowHeight(6.75);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(2.67+2);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(7.56+2);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(7+2);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(0.44+1);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(4+2);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(1.56+2);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(0.75+1);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(0.75+1);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(0.75+1);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(1.78+1);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(4+2);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(0.44+1);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(0);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(0);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(7.78+3);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(10.56+4);
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(2.7);
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(10.56+4);
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(7.78+3);
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(7.78+3);
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(7.78+3);
$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(7.78+3);


/* head */
$objPHPExcel->getActiveSheet()->mergeCells('A2:B2');
$objPHPExcel->getActiveSheet()->mergeCells('C2:G2');
$objPHPExcel->getActiveSheet()->mergeCells('H2:O2');
$objPHPExcel->getActiveSheet()->mergeCells('H3:O3');
$objPHPExcel->getActiveSheet()->mergeCells('H4:O4');
$objPHPExcel->getActiveSheet()->mergeCells('C3:G3');
$objPHPExcel->getActiveSheet()->mergeCells('C4:G4');

$objPHPExcel->getActiveSheet()->mergeCells('A3:B3');
$objPHPExcel->getActiveSheet()->mergeCells('A4:B4');

$objPHPExcel->getActiveSheet()->mergeCells('R2:R4');
$objPHPExcel->getActiveSheet()->mergeCells('T2:V2');
$objPHPExcel->getActiveSheet()->mergeCells('T3:V3');
$objPHPExcel->getActiveSheet()->mergeCells('T4:V4');

set_cell_color('A',2,'FFFFFF99');
set_cell_border('A2');
set_cell_border('B2');
$objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$objPHPExcel->getActiveSheet()->setCellValue('A2','Customer');
$objPHPExcel->getActiveSheet()->setCellValue('H2','Raw Material Supplier');
$objPHPExcel->getActiveSheet()->setCellValue('A3','Part Number');
$objPHPExcel->getActiveSheet()->setCellValue('H3','Material Type');
$objPHPExcel->getActiveSheet()->setCellValue('A4','Finish');
$objPHPExcel->getActiveSheet()->setCellValue('H4','Resin System');
set_cell_border('C2');
set_cell_border('D2');
set_cell_border('E2');
set_cell_border('F2');
set_cell_border('G2');
set_cell_border('S2');
set_cell_border('S3');
set_cell_border('S4');

$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A2'), 'H2:O2');
$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A2'), 'A3:B3');
$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A2'), 'H3:O3');
$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A2'), 'H4:O4');
$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A2'), 'A4:B4');
$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('C2'), 'P2');
$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('C2'), 'P3');
$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('C2'), 'P4');
$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('C2'), 'C3:G3');
$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('C2'), 'C4:G4');

$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A2'), 'R2:R4');
$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('C2'), 'T2:V2');
$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('C2'), 'T3:V3');
$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('C2'), 'T4:V4');

$objPHPExcel->getActiveSheet()->setCellValue('R2','Stackup Proposal');
$objPHPExcel->getActiveSheet()->getStyle('R2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objStyleB=$objPHPExcel->getActiveSheet()->getStyle('R2');
$objStyleB->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->setCellValue('S2','Date');
$objPHPExcel->getActiveSheet()->setCellValue('S3','Prepared by');
$objPHPExcel->getActiveSheet()->setCellValue('S4','Approved by');
$objPHPExcel->getActiveSheet()->setCellValue('T2',date('Y.m.d',time()));
$objPHPExcel->getActiveSheet()->setCellValue('T3','');
$objPHPExcel->getActiveSheet()->setCellValue('T4','');

/* real stackup table */
//start row.
$l= 6;
$imp_start = $l;
$objPHPExcel->getActiveSheet()->mergeCells('A'.$l.':P'.($l+1));
$objPHPExcel->getActiveSheet()->setCellValue('A'.$l,'Stackup Construction');
$objPHPExcel->getActiveSheet()->getStyle('A'.$l)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A'.$l)->getFont()->setItalic(true);
set_cell_color('A',$l,'00CCFFCC');
set_cell_border('A'.$l);
$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.$l), 'A'.$l.':P'.($l+1));
$l+=2;
$objPHPExcel->getActiveSheet()->mergeCells('A'.$l.':L'.$l);
$objPHPExcel->getActiveSheet()->mergeCells('O'.$l.':P'.$l);
$objPHPExcel->getActiveSheet()->setCellValue('A'.$l,'Based On Fab Information');
$objPHPExcel->getActiveSheet()->setCellValue('O'.$l,'Viasystems');
set_cell_border('A'.$l);
$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.$l), 'A'.$l.':P'.$l);
$objPHPExcel->getActiveSheet()->getRowDimension($l)->setRowHeight(27);
$objPHPExcel->getActiveSheet()->getRowDimension($l+1)->setRowHeight(27);
$l+=1;
set_cell_border('A'.$l);
set_cell_border('B'.$l);
set_cell_border('C'.$l);
set_cell_border('E'.$l);
set_cell_border('O'.$l);
set_cell_border('P'.$l);
$objPHPExcel->getActiveSheet()->mergeCells('E'.$l.':K'.$l);
$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.$l), 'E'.$l.':K'.$l);
$objPHPExcel->getActiveSheet()->setCellValue('A'.$l,'Layer #');
$objPHPExcel->getActiveSheet()->setCellValue('B'.$l,'Base Cu wt.(Oz)');
$objPHPExcel->getActiveSheet()->setCellValue('C'.$l,'Type');
$objPHPExcel->getActiveSheet()->setCellValue('E'.$l,'Diagram of Layer Sequence');
$objPHPExcel->getActiveSheet()->setCellValue('O'.$l,'Glass Type');
$objPHPExcel->getActiveSheet()->setCellValue('P'.$l,'Predicted Thick.(mm)');
$objStyleB=$objPHPExcel->getActiveSheet()->getStyle('A'.$l);
$objStyleB->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.$l), 'A'.$l.':P'.$l);

$l+=1;
add_psr($l);

$l+=1;
add_plating($l);

/* fetch data */
$l+=1;
include("stackup_query.php");
	
$rsStk = oci_parse($conn, $sqlStk);
oci_execute($rsStk, OCI_DEFAULT);
while(oci_fetch($rsStk)){
	set_row_style($l);
	if(oci_result($rsStk, 4) === 'foil'){
		$pp_over_thk = '';
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$l,oci_result($rsStk, 6));
		$layer_line[oci_result($rsStk, 6)] = $l;
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$l,oci_result($rsStk, 10));
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$l,'Foil');
		set_cell_color('C',$l,'FFFFFFFF');
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$l,'layer'.oci_result($rsStk, 6));
		set_cell_color('F',$l,'FF993366');
		set_cell_color('G',$l,'00CCCCCC');
		set_cell_color('J',$l,'FF993366');
		set_cell_color('I',$l,'00CCCCCC');
		set_cell_color('K',$l,'FFFFFFFF');
		$foil_cu_thk = sprintf("%.3f",oci_result($rsStk, 11)*0.0254);
		$objPHPExcel->getActiveSheet()->setCellValue('P'.$l,$foil_cu_thk);
		$l+=1;
	} elseif(oci_result($rsStk, 4) === 'core') {
		$family = oci_result($rsStk, 17);
		$vendor = oci_result($rsStk, 31);
		$pp_over_thk = '';
		if(oci_result($rsStk, 5)=== "Both"){ 
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$l,oci_result($rsStk, 6));
			$layer_line[oci_result($rsStk, 6)] = $l;
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$l,oci_result($rsStk, 10));
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$l,'layer'.oci_result($rsStk, 6));
			if (oci_result($rsStk, 9) == 'sig') {
				set_cell_color('E',$l,'FF993366'); 
				set_cell_color('K',$l,'FF993366');
			} else {
				set_cell_color('K',$l,'FFFFFFFF');
			}
			set_cell_color('F',$l,'FF993366');
			set_cell_color('G',$l,'00CCCCCC');
			set_cell_color('J',$l,'FF993366');
			set_cell_color('I',$l,'00CCCCCC');
			$objPHPExcel->getActiveSheet()->setCellValue('P'.$l,sprintf("%.3f",oci_result($rsStk, 11)*0.0254));
			$l+=1;
			set_row_style($l);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$l,'Core');
			$objPHPExcel->getActiveSheet()->mergeCells('E'.$l.':F'.$l);
			set_cell_border('E'.$l);
			set_cell_border('F'.$l);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);
			$objStyle=$objPHPExcel->getActiveSheet()->getStyle('E'.$l);
			$objFill = $objStyle->getFill();
			$objFill->setFillType(PHPExcel_Style_Fill::FILL_PATTERN_GRAY125);
			$objFill->setStartColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_BLACK ));
			$objFill->getEndColor()->setARGB('00CCFFCC');
			set_cell_color('G',$l,'00CCCCCC');
			set_cell_color('O',$l,'00CCFFCC');
			set_cell_color('P',$l,'00CCFFCC');
			$objPHPExcel->getActiveSheet()->mergeCells('J'.$l.':K'.$l);
			set_cell_border('J'.$l);
			set_cell_border('K'.$l);
			$objPHPExcel->getActiveSheet()->getStyle('J'.$l)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);
			set_cell_color('I',$l,'00CCCCCC');
			$objStyle=$objPHPExcel->getActiveSheet()->getStyle('J'.$l);
			$objFill = $objStyle->getFill();
			$objFill->setFillType(PHPExcel_Style_Fill::FILL_PATTERN_GRAY125);
			$objFill->setStartColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_BLACK ));
			$objFill->getEndColor()->setARGB('00CCFFCC');
			$objPHPExcel->getActiveSheet()->setCellValue('P'.$l,sprintf("%.3f",oci_result($rsStk, 7)*0.0254));
			$l+=1;
			if(oci_fetch($rsStk)){
				set_row_style($l);
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$l,oci_result($rsStk, 6));
				$layer_line[oci_result($rsStk, 6)] = $l;
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$l,oci_result($rsStk, 10));
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$l,'layer'.oci_result($rsStk, 6));
				if (oci_result($rsStk, 9) == 'sig') {
					set_cell_color('E',$l,'FF993366'); 
					set_cell_color('K',$l,'FF993366');
				} else {
					set_cell_color('K',$l,'FFFFFFFF');
				}
				set_cell_color('F',$l,'FF993366');
				set_cell_color('G',$l,'00CCCCCC');
				set_cell_color('J',$l,'FF993366');
				set_cell_color('I',$l,'00CCCCCC');
				$objPHPExcel->getActiveSheet()->setCellValue('P'.$l,sprintf("%.3f",oci_result($rsStk, 11)*0.0254));
				$l+=1;
			}
		} elseif(oci_result($rsStk, 5)=== "Top"){ 
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$l,oci_result($rsStk, 6));
			$layer_line[oci_result($rsStk, 6)] = $l;
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$l,oci_result($rsStk, 10));
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$l,'layer'.oci_result($rsStk, 6));
			if (oci_result($rsStk, 9) == 'sig') {
				set_cell_color('E',$l,'FF993366'); 
				set_cell_color('K',$l,'FF993366');
			} else {
				set_cell_color('K',$l,'FFFFFFFF');
			}
			set_cell_color('F',$l,'FF993366');
			set_cell_color('G',$l,'00CCCCCC');
			set_cell_color('J',$l,'FF993366');
			set_cell_color('I',$l,'00CCCCCC');
			$objPHPExcel->getActiveSheet()->setCellValue('P'.$l,sprintf("%.3f",oci_result($rsStk, 11)*0.0254));
			$l+=1;
			set_row_style($l);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$l,'Core');
			$objPHPExcel->getActiveSheet()->mergeCells('E'.$l.':F'.$l);
			set_cell_border('E'.$l);
			set_cell_border('F'.$l);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);
			$objStyle=$objPHPExcel->getActiveSheet()->getStyle('E'.$l);
			$objFill = $objStyle->getFill();
			$objFill->setFillType(PHPExcel_Style_Fill::FILL_PATTERN_GRAY125);
			$objFill->setStartColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_BLACK ));
			$objFill->getEndColor()->setARGB('00CCFFCC');
			set_cell_color('G',$l,'00CCCCCC');
			set_cell_color('O',$l,'00CCFFCC');
			set_cell_color('P',$l,'00CCFFCC');
			$objPHPExcel->getActiveSheet()->mergeCells('J'.$l.':K'.$l);
			set_cell_border('J'.$l);
			set_cell_border('K'.$l);
			$objPHPExcel->getActiveSheet()->getStyle('J'.$l)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);
			set_cell_color('I',$l,'00CCCCCC');
			$objStyle=$objPHPExcel->getActiveSheet()->getStyle('J'.$l);
			$objFill = $objStyle->getFill();
			$objFill->setFillType(PHPExcel_Style_Fill::FILL_PATTERN_GRAY125);
			$objFill->setStartColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_BLACK ));
			$objFill->getEndColor()->setARGB('00CCFFCC');
			$objPHPExcel->getActiveSheet()->setCellValue('P'.$l,sprintf("%.3f",oci_result($rsStk, 7)*0.0254));
			$l+=1;
		} elseif(oci_result($rsStk, 5)=== "Bottom"){ 
			set_row_style($l);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$l,'Core');
			$objPHPExcel->getActiveSheet()->mergeCells('E'.$l.':F'.$l);
			set_cell_border('E'.$l);
			set_cell_border('F'.$l);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);
			$objStyle=$objPHPExcel->getActiveSheet()->getStyle('E'.$l);
			$objFill = $objStyle->getFill();
			$objFill->setFillType(PHPExcel_Style_Fill::FILL_PATTERN_GRAY125);
			$objFill->setStartColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_BLACK ));
			$objFill->getEndColor()->setARGB('00CCFFCC');
			set_cell_color('G',$l,'00CCCCCC');
			set_cell_color('O',$l,'00CCFFCC');
			set_cell_color('P',$l,'00CCFFCC');
			$objPHPExcel->getActiveSheet()->mergeCells('J'.$l.':K'.$l);
			set_cell_border('J'.$l);
			set_cell_border('K'.$l);
			$objPHPExcel->getActiveSheet()->getStyle('J'.$l)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);
			set_cell_color('I',$l,'00CCCCCC');
			$objStyle=$objPHPExcel->getActiveSheet()->getStyle('J'.$l);
			$objFill = $objStyle->getFill();
			$objFill->setFillType(PHPExcel_Style_Fill::FILL_PATTERN_GRAY125);
			$objFill->setStartColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_BLACK ));
			$objFill->getEndColor()->setARGB('00CCFFCC');
			$objPHPExcel->getActiveSheet()->setCellValue('P'.$l,sprintf("%.3f",oci_result($rsStk, 7)*0.0254));
			$l+=1;
			set_row_style($l);
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$l,oci_result($rsStk, 6));
			$layer_line[oci_result($rsStk, 6)] = $l;
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$l,oci_result($rsStk, 10));
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$l,'layer'.oci_result($rsStk, 6));
			if (oci_result($rsStk, 9) == 'sig') {
				set_cell_color('E',$l,'FF993366'); 
				set_cell_color('K',$l,'FF993366');
			} else {
				set_cell_color('K',$l,'FFFFFFFF');
			}
			set_cell_color('F',$l,'FF993366');
			set_cell_color('G',$l,'00CCCCCC');
			set_cell_color('J',$l,'FF993366');
			set_cell_color('I',$l,'00CCCCCC');
			$objPHPExcel->getActiveSheet()->setCellValue('P'.$l,sprintf("%.3f",oci_result($rsStk, 11)*0.0254));
			$l+=1;
		} else {
			set_row_style($l);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$l,'Core');
			$objPHPExcel->getActiveSheet()->mergeCells('E'.$l.':F'.$l);
			set_cell_border('E'.$l);
			set_cell_border('F'.$l);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);
			$objStyle=$objPHPExcel->getActiveSheet()->getStyle('E'.$l);
			$objFill = $objStyle->getFill();
			$objFill->setFillType(PHPExcel_Style_Fill::FILL_PATTERN_GRAY125);
			$objFill->setStartColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_BLACK ));
			$objFill->getEndColor()->setARGB('00CCFFCC');
			set_cell_color('G',$l,'00CCCCCC');
			set_cell_color('O',$l,'00CCFFCC');
			set_cell_color('P',$l,'00CCFFCC');
			$objPHPExcel->getActiveSheet()->mergeCells('J'.$l.':K'.$l);
			set_cell_border('J'.$l);
			set_cell_border('K'.$l);
			$objPHPExcel->getActiveSheet()->getStyle('J'.$l)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);
			set_cell_color('I',$l,'00CCCCCC');
			$objStyle=$objPHPExcel->getActiveSheet()->getStyle('J'.$l);
			$objFill = $objStyle->getFill();
			$objFill->setFillType(PHPExcel_Style_Fill::FILL_PATTERN_GRAY125);
			$objFill->setStartColor(new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_BLACK ));
			$objFill->getEndColor()->setARGB('00CCFFCC');
			$objPHPExcel->getActiveSheet()->setCellValue('P'.$l,sprintf("%.3f",oci_result($rsStk, 7)*0.0254));
			$l+=1;
		}
	} else {
		if (!$pp_over_thk) {
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$l,'Pre-preg');
		}
		$pp_over_thk = sprintf("%.3f",oci_result($rsStk, 20)*0.0254);
		set_cell_color('G',$l,'00CCCCCC');
		set_cell_color('I',$l,'00CCCCCC');
		set_cell_color('J',$l,'FFFFFFFF');
		set_cell_color('K',$l,'FFFFFFFF');
		$objPHPExcel->getActiveSheet()->setCellValue('O'.$l,oci_result($rsStk, 15));
		$objPHPExcel->getActiveSheet()->setCellValue('P'.$l,$pp_over_thk);
		$l+=1;
	}
}

add_plating($l);
$l+=1;
add_psr($l);
$end_line = $l;
$l+=1;

$objPHPExcel->getActiveSheet()->mergeCells('A'.$l.':C'.$l);
$objPHPExcel->getActiveSheet()->mergeCells('A'.($l+1).':C'.($l+1));
$objPHPExcel->getActiveSheet()->getStyle('A'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
set_cell_border('A'.$l);
set_cell_color('A',$l,'FFFFFF99');
$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.$l), 'A'.($l).':C'.($l+1));
$objPHPExcel->getActiveSheet()->setCellValue('A'.$l,'Required PCB Thick.');
$objPHPExcel->getActiveSheet()->setCellValue('A'.($l+1),'Calculated Overall Thick.');

$objPHPExcel->getActiveSheet()->mergeCells('E'.$l.':J'.$l);
$objPHPExcel->getActiveSheet()->mergeCells('E'.($l+1).':J'.($l+1));
set_cell_border('E'.$l);
set_cell_color('E',$l,'00CCFFCC');
$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('E'.$l), 'E'.($l).':J'.($l+1));

$objPHPExcel->getActiveSheet()->mergeCells('K'.$l.':P'.$l);
$objPHPExcel->getActiveSheet()->mergeCells('K'.($l+1).':P'.($l+1));
$objPHPExcel->getActiveSheet()->getStyle('K'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
set_cell_border('K'.$l);
$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('K'.$l), 'K'.($l).':P'.($l+1));

$objPHPExcel->getActiveSheet()->setCellValue('C2',$customer);
$objPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->setCellValue('C3',$cust_pn);
$objPHPExcel->getActiveSheet()->setCellValue('P2',$vendor);
$objPHPExcel->getActiveSheet()->setCellValue('P3',$family);
$objPHPExcel->getActiveSheet()->setCellValue('T3',$user);
$objPHPExcel->getActiveSheet()->setCellValue('E'.$l,$cust_thk);
$objPHPExcel->getActiveSheet()->getStyle('E'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_000);
$objPHPExcel->getActiveSheet()->setCellValue('E'.($l+1),'=(SUM(P11:P'.$l.'))');
$objPHPExcel->getActiveSheet()->getStyle('E'.($l+1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_000);
$objPHPExcel->getActiveSheet()->setCellValue('K'.$l,$tol);


/* Impedance */
include("impedance_query.php");
$rsImp = oci_parse($conn, ' select distinct CUSTOMER_REQUIRED_IMPEDANCE from ('.$imp_query.') order by customer_required_impedance');
oci_execute($rsImp, OCI_DEFAULT);
while(oci_fetch($rsImp)){
	$num_imp +=1;
	$the_imps[$num_imp] = oci_result($rsImp, 1);
}
if ($num_imp) {
	$cols = array('','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
	$objPHPExcel->getActiveSheet()->mergeCells('R'.$imp_start.':'.$cols[$num_imp*3].$imp_start);
	$right_col = $num_imp*3+2;
	$objPHPExcel->getActiveSheet()->setCellValue('R'.$imp_start,'Controlled Impedance Table( If required )');
	set_cell_border('R'.$imp_start);
	set_cell_color('R',$imp_start,'00CCFFCC');

	$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('R'.$imp_start), 'S'.($imp_start).':'.$cols[$num_imp*3].$imp_start);

	$imp_start +=1;
	for ($i = 1;$i<=$num_imp;$i++) {
		$imp_om_col[$the_imps[$i]] = ($i-1)*3+1;
		$objPHPExcel->getActiveSheet()->mergeCells($cols[($i-1)*3+1].$imp_start.':'.$cols[$i*3].$imp_start);
		$objPHPExcel->getActiveSheet()->setCellValue($cols[($i-1)*3+1].$imp_start,$the_imps[$i].' Ohms');
		set_cell_border($cols[($i-1)*3+1].$imp_start);
		set_cell_color($cols[($i-1)*3+1],$imp_start,'FFFFFF99');
		$objStyleB=$objPHPExcel->getActiveSheet()->getStyle($cols[($i-1)*3+1].$imp_start);
		$objStyleB->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->mergeCells($cols[($i-1)*3+2].($imp_start+1).':'.$cols[$i*3].($imp_start+1));
		$objPHPExcel->getActiveSheet()->setCellValue($cols[($i-1)*3+2].($imp_start+1),'Expected Value');
		$objPHPExcel->getActiveSheet()->setCellValue($cols[($i-1)*3+1].($imp_start+1),'Requirement');
		$right_row = $imp_start+1;
		$objPHPExcel->getActiveSheet()->setCellValue($cols[($i-1)*3+1].($imp_start+2),'Zo (Ohm)');
		$objPHPExcel->getActiveSheet()->setCellValue($cols[($i-1)*3+2].($imp_start+2),'Trace (mml)');
		$objPHPExcel->getActiveSheet()->setCellValue($cols[($i-1)*3+3].($imp_start+2),'Simulated Zo (Ohm)');
		$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle($cols[($i-1)*3+1].$imp_start), $cols[($i-1)*3+1].$imp_start.':'.$cols[$i*3].($imp_start+2));
		
	}
	$imp_start +=3;

	set_cell_border('R'.$imp_start);
	$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('R'.$imp_start), 'R'.$imp_start.':'.$cols[$num_imp*3].$end_line);


	$rsImp = oci_parse($conn, $imp_query);
	oci_execute($rsImp, OCI_DEFAULT);
	while(oci_fetch($rsImp)){
		$objPHPExcel->getActiveSheet()->setCellValue($cols[$imp_om_col[oci_result($rsImp, 4)]].$layer_line[oci_result($rsImp, 3)],oci_result($rsImp, 4));
		if (oci_result($rsImp, 13)) {
			$space = oci_result($rsImp, 13) - oci_result($rsImp, 7);
		}
		if ($space) {
			$objPHPExcel->getActiveSheet()->setCellValue($cols[$imp_om_col[oci_result($rsImp, 4)]+1].$layer_line[oci_result($rsImp, 3)],sprintf("%.3f",oci_result($rsImp, 7)*0.0254).'/'.sprintf("%.3f",$space*0.0254));
		} else {
			$objPHPExcel->getActiveSheet()->setCellValue($cols[$imp_om_col[oci_result($rsImp, 4)]+1].$layer_line[oci_result($rsImp, 3)],sprintf("%.3f",oci_result($rsImp, 7)*0.0254));
		}
		$objPHPExcel->getActiveSheet()->setCellValue($cols[$imp_om_col[oci_result($rsImp, 4)]+2].$layer_line[oci_result($rsImp, 3)],sprintf("%.2f",oci_result($rsImp, 8)));
	}

	//DK
	$objPHPExcel->getActiveSheet()->mergeCells($cols[$right_col].$right_row.':'.$cols[$right_col].($right_row+1));
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col].$right_row,'Material Glass Type');
	set_cell_border($cols[$right_col].$right_row);
	set_cell_color($cols[$right_col],$right_row,'FFFFFF99');
	$objStyleB=$objPHPExcel->getActiveSheet()->getStyle($cols[$right_col].$right_row);
	$objStyleB->getAlignment()->setWrapText(true);

	$objPHPExcel->getActiveSheet()->mergeCells($cols[$right_col+1].$right_row.':'.$cols[$right_col+6].$right_row);
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+1].$right_row,'Dk');
	$objPHPExcel->getActiveSheet()->mergeCells($cols[$right_col+7].$right_row.':'.$cols[$right_col+12].$right_row);
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+7].$right_row,'Df');
	$right_row +=1;
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+1].$right_row,'100MHz');
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+2].$right_row,'1GHz');
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+3].$right_row,'2GHz');
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+4].$right_row,'5GHz');
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+5].$right_row,'10GHz');
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+6].$right_row,'20GHz');
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+7].$right_row,'100MHz');
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+8].$right_row,'1GHz');
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+9].$right_row,'2GHz');
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+10].$right_row,'5GHz');
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+11].$right_row,'10GHz');
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+12].$right_row,'20GHz');
	$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle($cols[$right_col].($right_row-1)), $cols[$right_col].($right_row-1).':'.$cols[$right_col+12].($right_row));
	$right_row +=1;
	$pp_query = "select distinct M.GENERIC_NAME,P.RESIN_PERMITTIVITY
					from items i
							,items iseg
							,stackup_seg ss
							,segment_material sm
							,material m
							,prepreg p
					where i.item_type=2
							and iseg.item_type=10
							and i.root_id=iseg.root_id
							and ss.item_id=iseg.item_id
							and ss.revision_id=iseg.last_checked_in_rev
							and iseg.DELETED_IN_GRAPH is null
							and ss.item_id=sm.item_id
							and ss.revision_id=sm.revision_id
							and sm.material_item_id=m.item_id
							and sm.material_revision_id=m.revision_id
							and m.item_id=p.item_id
							and m.revision_id=p.revision_id
							and i.item_name='$job'";

	$rsPp = oci_parse($conn, $pp_query);
	oci_execute($rsPp, OCI_DEFAULT);
	while(oci_fetch($rsPp)){ 
		$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col].$right_row,oci_result($rsPp, 1));
		$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+2].($right_row),oci_result($rsPp, 2));
		set_cell_border($cols[$right_col].$right_row);
		$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle($cols[$right_col].$right_row), $cols[$right_col].$right_row.':'.$cols[$right_col+12].$right_row);
		$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+1].$right_row,'N/A');
		$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+3].$right_row,'N/A');
		$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+4].$right_row,'N/A');
		$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+5].$right_row,'N/A');
		$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+6].$right_row,'N/A');
		$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+7].$right_row,'N/A');
		$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+8].$right_row,'N/A');
		$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+9].$right_row,'N/A');
		$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+10].$right_row,'N/A');
		$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+11].$right_row,'N/A');
		$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+12].$right_row,'N/A');
		$right_row+=1;
	}

	$right_row+=1;
	$objPHPExcel->getActiveSheet()->mergeCells($cols[$right_col].$right_row.':'.$cols[$right_col+1].($right_row+1));
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col].$right_row,'Copper Foil Type');
	set_cell_border($cols[$right_col].$right_row);
	set_cell_color($cols[$right_col],$right_row,'FFFFFF99');
	$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle($cols[$right_col].$right_row), $cols[$right_col].$right_row.':'.$cols[$right_col+1].($right_row+1));

	$objPHPExcel->getActiveSheet()->mergeCells($cols[$right_col+2].$right_row.':'.$cols[$right_col+4].($right_row+1));
	set_cell_border($cols[$right_col+2].$right_row);
	$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle($cols[$right_col+2].$right_row), $cols[$right_col+2].$right_row.':'.$cols[$right_col+4].($right_row+1));

	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+2].$right_row,'Standard');
	$objPHPExcel->getActiveSheet()->getStyle($cols[$right_col+2].$right_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

	/*PSR*/
	$right_row+=3;
	$objPHPExcel->getActiveSheet()->mergeCells($cols[$right_col].$right_row.':'.$cols[$right_col].($right_row+1));
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col].$right_row,'PSR');
	set_cell_border($cols[$right_col].$right_row);
	set_cell_color($cols[$right_col],$right_row,'FFFFFF99');
	$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle($cols[$right_col].$right_row), $cols[$right_col].$right_row.':'.$cols[$right_col+5].($right_row));
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+1].$right_row,'Vendor');
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+2].$right_row,'Grade');
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+3].$right_row,'Thickness');
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+4].$right_row,'Dk (@ 1MHz)');
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+5].$right_row,'Df (@ 1MHz)');


	/* Get solder mask*/
	if ($site == 'GZ' || $site == 'ZS') {
		$sm_query = "select ev.value from
						   items i,
						   job_da j,
						   enum_types et,
						   enum_values ev
					where i.item_type=2
					and i.item_id=j.item_id
					and j.revision_id=i.last_checked_in_rev
					and et.enum_type=ev.enum_type
					and(( et.type_name='SOLDER_MASK_TYPE_' and ev.enum=j.solder_mask_type_ and j.OSWF_ESPRAY_REQD_=0)
					or ( et.type_name='ESPRAY_TYPE_' and ev.enum=j.OSWF_ESPRAY_INK_MODEL_ and j.OSWF_ESPRAY_REQD_=1))
					and i.item_name='$job'";
	} else {
		$sm_query = "select ev.value from
		   items i,
		   job_da j,
		   enum_types et,
		   enum_values ev
			where i.item_type=2
			and i.item_id=j.item_id
			and j.revision_id=i.last_checked_in_rev
			and et.enum_type=ev.enum_type
			and et.type_name='SM_TYPE_'
			and ev.enum=j.SOLDERMASK_TYPE_
			and i.item_name='$job'";
	}
	$rsSm = oci_parse($conn, $sm_query);
	oci_execute($rsSm, OCI_DEFAULT);
	while(oci_fetch($rsSm)){
		$sm_type = oci_result($rsSm, 1);
	}

	$sm_vendor = 'TAIYO';

	$right_row+=1;
	set_cell_border($cols[$right_col].$right_row);
	$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle($cols[$right_col].$right_row), $cols[$right_col].$right_row.':'.$cols[$right_col+5].($right_row));
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+1].$right_row,$sm_vendor);
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+2].$right_row,$sm_type);
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+3].$right_row,'5~50um');
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+4].$right_row,'3.9');
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+5].$right_row,'N/A');

	$right_row+=4;
	$objPHPExcel->getActiveSheet()->mergeCells($cols[$right_col].$right_row.':'.$cols[$right_col].($right_row+1));
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col].$right_row,'Etch Factor');
	set_cell_border($cols[$right_col].$right_row);
	set_cell_border($cols[$right_col].($right_row+1));
	set_cell_color($cols[$right_col],$right_row,'FFFFFF99');
	$objPHPExcel->getActiveSheet()->mergeCells($cols[$right_col+1].$right_row.':'.$cols[$right_col+1].($right_row+1));
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col+1].$right_row,'0.7');
	set_cell_border($cols[$right_col+1].$right_row);
	set_cell_border($cols[$right_col+1].($right_row+1));

	place_pic('etch',$cols[$right_col+3].$right_row,0);

	$right_row+=4;
	$objPHPExcel->getActiveSheet()->mergeCells($cols[$right_col].$right_row.':'.$cols[$right_col+1].($right_row+1));
	$objPHPExcel->getActiveSheet()->setCellValue($cols[$right_col].$right_row,'Result of Simulation Zo');
	set_cell_border($cols[$right_col].$right_row);
	set_cell_color($cols[$right_col],$right_row,'FFFFFF99');
	$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle($cols[$right_col].$right_row), $cols[$right_col].$right_row.':'.$cols[$right_col+1].($right_row+1));

	place_pic('imp',$cols[$right_col].($right_row+3),0);
}



$filename =$job. ".xls";
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$filename);
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
//$objWriter->save($filename);

function add_plating ($l) {
	Global $objPHPExcel;
	set_cell_border('A'.$l);
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$l.':C'.$l);
	$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.$l), 'A'.$l.':C'.$l);
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$l,'Plating');
	$objPHPExcel->getActiveSheet()->getStyle('E'.$l)->getFont()->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_WHITE ));
	$objPHPExcel->getActiveSheet()->getStyle('E'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('K'.$l)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	set_cell_color('E',$l,'00CCCCCC');
	set_cell_color('F',$l,'00CCCCCC');
	set_cell_color('G',$l,'00CCCCCC');
	set_cell_color('J',$l,'00CCCCCC');
	set_cell_color('K',$l,'00CCCCCC');
	set_cell_color('I',$l,'00CCCCCC');
	set_il_cell_border('O'.$l);
	set_il_cell_border('P'.$l);
	$objPHPExcel->getActiveSheet()->setCellValue('P'.$l,0.03);
	$objPHPExcel->getActiveSheet()->getStyle('P'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_000);

}

function add_psr ($l) {
	Global $objPHPExcel;
	set_cell_border('A'.$l);
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$l.':C'.$l);
	$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.$l), 'A'.$l.':C'.$l);
	set_cell_border('O'.$l);
	set_cell_border('P'.$l);
	$objPHPExcel->getActiveSheet()->mergeCells('E'.$l.':K'.$l);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$l,'PSR');
	$objPHPExcel->getActiveSheet()->getStyle('E'.$l)->getFont()->setBold(true);
	set_cell_color('E',$l,'00CCFFCC');
	$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('E'.$l), 'F'.$l.':K'.$l);
	$objPHPExcel->getActiveSheet()->setCellValue('P'.$l,0.4*0.0254);
	$objPHPExcel->getActiveSheet()->getStyle('P'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_000);
	$objPHPExcel->getActiveSheet()->getStyle('K'.$l)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
}

function place_pic ($pic,$d_index,$offsety = 0,$offsetx = 0,$height = 0) {
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

function set_foil_color ($l) {
	$color = 'FFFFFF99';
	set_fill_color('A',$l,$color);
	set_fill_color('B',$l,$color);
	set_fill_color('C',$l,$color);
	set_fill_color('D',$l,$color);
	set_fill_color('E',$l,$color);
	set_fill_color('F',$l,$color);
	set_fill_color('G',$l,$color);
	set_fill_color('H',$l,$color);
	set_fill_color('I',$l,$color);
	set_fill_color('J',$l,$color);
	set_fill_color('K',$l,$color);
	set_fill_color('L',$l,$color);
	set_fill_color('M',$l,$color);
}

function set_core_color ($l) {
	$color = 'FFFFFF99';
	set_fill_color('A',$l,$color);
	set_fill_color('B',$l,$color);
	set_fill_color('C',$l,$color);
}

function set_cell_color ($col,$l,$color) {
	set_fill_color($col,$l,$color);
}

function set_cell_border ($cell) {
	Global $objPHPExcel;
	$objPHPExcel->getActiveSheet()->getStyle($cell)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
}

function set_il_cell_border ($cell) {
	Global $objPHPExcel;
	$objPHPExcel->getActiveSheet()->getStyle($cell)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_DOTTED);
}

function set_row_style ($l) {
			Global $objPHPExcel;
			set_cell_border('A'.$l);
			set_cell_border('B'.$l);
			set_cell_border('C'.$l);
			set_il_cell_border('O'.$l);
			set_il_cell_border('P'.$l);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$l)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('K'.$l)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle('P'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_000);
}

function getPhone($search_user) {
			$ldap_url = '10.65.8.51';
			$ldap_domain = 'vspri';
			$ldap_dn = "DC=viasystems,DC=pri";
			$ds = ldap_connect( $ldap_url );
			ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
			$username = "tq.hype";
			$password = "Merix1"; 

			// now try a real login
			$login = ldap_bind( $ds, "$username@$ldap_domain", $password ); 
			try{
				$attributes = array("telephonenumber");
				$filter = "(&(objectCategory=person)(sAMAccountName=$search_user))";
				$result = ldap_search($ds, $ldap_dn, $filter, $attributes);
				$entries = ldap_get_entries($ds, $result);
				if($entries["count"] > 0){
					$phone = $entries[0]['telephonenumber'][0];
				}
			}catch(Exception $e){
				ldap_unbind($ds);
				return;
			}
			ldap_unbind($ds);
			return $phone;
		}

?>