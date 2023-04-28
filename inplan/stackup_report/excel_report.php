<?php
error_reporting(0);
$job = $_GET['job_name'];
$site = $_GET['site'];
$unit = $_GET['unit'];
$tst = $_GET['tst'];
if (!$unit) $unit = 'mils';
$core_family = $_GET['cf'];
$copper_weight = $_GET['cw'];
$core_thk = $_GET['ctk'];
$core_size = $_GET['cs'];
$core_tg = $_GET['ct'];
$const = $_GET['cst'];
$pf = $_GET['pf'];
$pp_tg = $_GET['pt'];
$glass = $_GET['gl'];
$pp_size = $_GET['ps'];
$resin = $_GET['rs'];
$thk = $_GET['thk'];
$dk = $_GET['dk'];
$core_foil_tr = $_GET['ft'];
$cust_req_thk = $_GET['custk'];
$outer_cu_thk = $_GET['otk'];
$imp_spacing = $_GET['ips'];
$show_drill_table = $_GET['dtb'];
$cust_notes =$_GET['custn'];
$stk_notes =$_GET['stkn'];
$deci = $_GET['deci'];
$show_material_list = $_GET['ltb'];
$df = $_GET['df'];
$num_imps = 0;
$col=$df?1:0;
$col1=($pp_size ||$core_size)?1:0;
$col=$col+$col1;
if (!$deci) {
	if ($unit == 'mils') { 
		$deci = 2;
	} else {
		$deci = 3;
	}
}

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
	$cust_thk =  oci_result($rsInfo, 7);
	if ($unit == 'MM') {
		$cust_thk = round($cust_thk * 0.0254,$deci);
		if (oci_result($rsInfo, 8) <> oci_result($rsInfo, 9)) {
			$cust_thk = $cust_thk .'+' . round(oci_result($rsInfo, 8)*0.0254,$deci).'/-'.round(oci_result($rsInfo, 9)*0.0254,$deci) . ' MM Measured:' . oci_result($rsInfo, 10);
		} else {
			$cust_thk = $cust_thk .'+/-'.round(oci_result($rsInfo, 9)*0.0254,$deci) . ' MM Measured:' . oci_result($rsInfo, 10);
		}
	} else {
		$cust_thk = round($cust_thk ,$deci);
		if (oci_result($rsInfo, 8) <> oci_result($rsInfo, 9)) {
			$cust_thk = $cust_thk .'+' . round(oci_result($rsInfo, 8),$deci).'/-'.round(oci_result($rsInfo, 9),$deci) . ' mils Measured:' . oci_result($rsInfo, 10);
		} else {
			$cust_thk = $cust_thk .'+/-'.round(oci_result($rsInfo, 9),$deci) . ' mils Measured:' . oci_result($rsInfo, 10);
		}
	}
	$job_type  = oci_result($rsInfo, 11);
	$num_imps = oci_result($rsInfo, 13);
}

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../../plugins/PHPExcel/PHPExcel.php';


if ($deci == 3) {
	$num_format = PHPExcel_Style_NumberFormat::FORMAT_NUMBER_000;
} elseif ($deci == 4) {
	$num_format = PHPExcel_Style_NumberFormat::FORMAT_NUMBER_0000;
} else {
	$num_format = PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00;
}

$objPHPExcel= new PHPExcel();

$objPHPExcel->getProperties()->setCreator("TTM Technologies")
							 ->setLastModifiedBy("TTM Technologies")
							 ->setTitle("Stackup Report")
							 ->setSubject("Stackup Report")
							 ->setDescription("Stackup Report")
							 ->setKeywords("Stackup Report")
							 ->setCategory("Stackup Report");
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Graphical View');   
$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('TTM-'.$site.'                         '.'&P'.'                         '.'A member of TTM Technologies Group Company Confidential');
$objPHPExcel->getActiveSheet()->setShowGridlines(false);  
$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
$alignment = $objPHPExcel->getActiveSheet()->getDefaultStyle()->getAlignment();
$alignment->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$alignment->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(8);
$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(16.25);
$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(16.25);
$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(16.25);
$objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(16.25);


//$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(8.5);
/* Place Logo */
place_pic ('logo','A2',0);
$objPHPExcel->getActiveSheet()->setCellValue('K1','Date:');
$objPHPExcel->getActiveSheet()->setCellValue('L1',date('M.d.Y',time()));
$objPHPExcel->getActiveSheet()->mergeCells('D2:F2');
$objPHPExcel->getActiveSheet()->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->mergeCells('D3:F3');
$objPHPExcel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->setCellValue('D2','Customer P/N:');
$objPHPExcel->getActiveSheet()->mergeCells('G2:K2');
$objPHPExcel->getActiveSheet()->setCellValue('G2',$cust_pn);
$objPHPExcel->getActiveSheet()->setCellValue('D3','Internal P/N:');
$objPHPExcel->getActiveSheet()->mergeCells('G3:K3');
$job_via_pn = $job. '   ('.$via_pn.')';
if (!$via_pn) $job_via_pn = $job;
$objPHPExcel->getActiveSheet()->setCellValue('G3',$job_via_pn );
$objPHPExcel->getActiveSheet()->mergeCells('D4:F4');
$objPHPExcel->getActiveSheet()->getStyle('D4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->setCellValue('D4','Contact:');
$objPHPExcel->getActiveSheet()->setCellValue('G4',$user);
$objPHPExcel->getActiveSheet()->mergeCells('G4:H4');
$objPHPExcel->getActiveSheet()->setCellValue('I4','Phone:');
$objPHPExcel->getActiveSheet()->setCellValue('J4',getPhone($user));

$l = 5;
if ($cust_req_thk == 1) {
	$objPHPExcel->getActiveSheet()->mergeCells('E'.$l.':F'.$l);
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$l,'Customer Req Thk:');
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$cust_thk);
	$l +=1;
}

/* real stackup table */
$objPHPExcel->getActiveSheet()->setCellValue('A'.$l,'Layer');
$objPHPExcel->getActiveSheet()->setCellValue('B'.$l,'Cu Thick. ('.$unit.')');
$objPHPExcel->getActiveSheet()->setCellValue('C'.$l,'Cu Foil wt (oz)');
if ($dk and $thk and $df) {
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'DK');
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,'DF');
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,'Lam. Thick. ('.$unit.')');
	$objPHPExcel->getActiveSheet()->setCellValue('I'.$l,'Description');
   } elseif ($thk and $dk ) {
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'DK');
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,'Lam. Thick. ('.$unit.')');
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,'Description');
 } elseif ($dk and $df) {
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'DK');
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,'DF');
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,'Description');
 }  elseif ($thk and $df) {
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'DF');
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,'Lam. Thick. ('.$unit.')');
	$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,'Description');
 } elseif ($dk) {
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'DK');
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,'Description');
 }  elseif ($thk) {
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'Lam. Thick. ('.$unit.')');
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,'Description');
	}  elseif ($df) {
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'DF');
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,'Description');
} else {
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'Description');
}


$alignment = $objPHPExcel->getActiveSheet()->getStyle('A'.$l)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_BOTTOM);
$objPHPExcel->getActiveSheet()->getStyle('A'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objStyle=$objPHPExcel->getActiveSheet()->getStyle('A'.$l);
$objStyle->getFont()->setSize(8);
$objStyleA5=$objPHPExcel->getActiveSheet()->getStyle('A'.$l);
$objStyleA5->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.$l), 'A'.$l.':M'.$l);

$l +=1;
$objPHPExcel->getActiveSheet()->getRowDimension($l)->setRowHeight(2);
$l +=1;

/* fetch data */
$l_start = $l;
include("stackup_query.php");
	
$rsStk = oci_parse($conn, $sqlStk);
oci_execute($rsStk, OCI_DEFAULT);
while(oci_fetch($rsStk)){
	$objPHPExcel->getActiveSheet()->getRowDimension($l)->setRowHeight(8.5);
	if(oci_result($rsStk, 4) === 'foil'){
		if ($pp){
			$pp_desc = "Prepreg ". $pp ;
			if ($pf){
				$pp_desc = "Prepreg ".$pp_family." ". $pp ;
			}
			if ($pp_tg)  $pp_desc .= ' ' . $pptg ;
			if ($ppsize)  $pp_desc .= $ppsize;
			
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$l,$pp_press_thk);
			/*if ($dk and $thk) {
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$pp_dk);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$pp_press_thk);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$pp_desc);
				$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
				$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode($num_format);
			} elseif ($dk) {
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$pp_dk);
				$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$pp_desc);
			}  elseif ($thk) {
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$pp_press_thk);
				$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode($num_format);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$pp_desc);
			} else {
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'  '.$pp_desc);
			}*/

			 if ($dk and $thk and $df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$pp_dk);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$pp_df);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$pp_press_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$l,$pp_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('H'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			        $objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('H'.$l)->getNumberFormat()->setFormatCode($num_format);
		     } elseif ($thk and $dk ) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$pp_dk);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$pp_press_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$pp_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode($num_format);
			 } elseif ($dk and $df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$pp_dk);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,oci_result($rsStk, 35));
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$pp_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			 }  elseif ($thk and $df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$pp_df);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$pp_press_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$pp_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode($num_format);
			 } elseif ($dk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$pp_dk);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$pp_desc);
			 }  elseif ($thk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$pp_press_thk);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode($num_format);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$pp_desc);
		 	 }  elseif ($df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$pp_df);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$pp_desc);
			 } else {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'  '.$pp_desc);
			}
			$pp = "";
			$pp_dk = 0;
			$pp_df = 0;
			set_pp_color($l,$col);
			set_row_style($l);
			place_pic ('prepreg','D'.$l,-2);
			$pp_pics[] = $l;
			
			$l++;
		}
		//eval foil description here:

		if (oci_result($rsStk, 6) !=1 and oci_result($rsStk, 6) !=$num_layers) {
				$segType = oci_result($rsStk, 9) . "_" . oci_result($rsStk, 12);
		} else {
				$segType = oci_result($rsStk, 9) . "_msk_" . oci_result($rsStk, 12);
		}
        $tmpTop = oci_result($rsStk, 6);
		$tmpTopRel = oci_result($rsStk, 32);
        $tmpCuTop = oci_result($rsStk, 10).' oz';
		$foil_desc = 'Foil '. $tmpCuTop ;
		if ($unit == 'mils') {
			$foil_cu_thk = sprintf('%.'.$deci.'f',oci_result($rsStk, 11));
		} else {
			$foil_cu_thk = sprintf('%.'.$deci.'f',oci_result($rsStk, 11)*0.0254);
		}
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$l,$tmpTop);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$l,$foil_cu_thk );
		$objPHPExcel->getActiveSheet()->getStyle('B'.$l)->getNumberFormat()->setFormatCode($num_format);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$l,$tmpCuTop);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$l,'');
		/*if ($dk and $thk) {
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'');
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,'');
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$foil_desc);
			} elseif ($dk) {
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'');
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$foil_desc);
			}  elseif ($thk) {
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'');
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$foil_desc);
			} else {
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'  '.$foil_desc);
		}*/

		if ($dk and $thk and $df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,"");
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,'');
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,"");
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$l,$foil_desc);				
		     } elseif ($thk and $dk ) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'');
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,'');
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$foil_desc);				
			 } elseif ($dk and $df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'');
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,'');
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$foil_desc);
			 }  elseif ($thk and $df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'');
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,'');
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$foil_desc);
			 } elseif ($dk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'');
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$foil_desc);
			 }  elseif ($thk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'');
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$foil_desc);
		 	 }  elseif ($df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'');
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$foil_desc);
			 } else {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'  '.$foil_desc);
				}
		//place_pic ($segType,'D'.$l,3);
		$place_foil[$l] = $segType;
		
		set_foil_color($l,$col);
		set_row_style($l);
		$layer_cell[$tmpTopRel] = $l;
		$l++;
		if ($add_foil_thick ==1 ) {
				$thick_over_lam +=oci_result($rsStk, 11);
				$add_foil_thick = 0;
			}
		$add_core_thick = 1;
		$add_pp_thick = 1;
	} elseif(oci_result($rsStk, 4) === 'core'){
			if ($add_core_thick ==1 ) {
				$thick_over_lam +=oci_result($rsStk, 7);
				$add_core_thick = 0;
			} 
			$add_pp_thick = 1;
			$add_foil_thick = 1;

			if ($pp){
				$pp_desc = "Prepreg ". $pp ;
				if ($pf){
						$pp_desc = "Prepreg ".$pp_family." ". $pp ;
				}
				if ($pp_tg)  $pp_desc .= ' ' . $pptg ;				
				if ($ppsize)  $pp_desc .= $ppsize;
				
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$l,$pp_press_thk);
				/*if ($dk and $thk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$pp_dk);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$pp_press_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$pp_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode($num_format);
				} elseif ($dk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$pp_dk);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$pp_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
				}  elseif ($thk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$pp_press_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$pp_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode($num_format);
				} else {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'  '.$pp_desc);
				}*/
             if ($dk and $thk and $df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$pp_dk);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$pp_df);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$pp_press_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$l,$pp_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('H'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			        $objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('H'.$l)->getNumberFormat()->setFormatCode($num_format);
		     } elseif ($thk and $dk ) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$pp_dk);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$pp_press_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$pp_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode($num_format);
			 } elseif ($dk and $df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$pp_dk);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$pp_df);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$pp_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			 }  elseif ($thk and $df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$pp_df);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$pp_press_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$pp_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode($num_format);
			 } elseif ($dk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$pp_dk);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$pp_desc);
			 }  elseif ($thk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$pp_press_thk);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode($num_format);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$pp_desc);
		 	 }  elseif ($df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$pp_df);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$pp_desc);
			 } else {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'  '.$pp_desc);
			}

				set_pp_color($l,$col);
				set_row_style($l);
				place_pic ('prepreg','D'.$l,-2);
				
				$l++;
				$pp = "";
				$pp_dk = 0;
				$pp_df = 0;
			}

			//eval core description here:
			$core_desc ='Core';
			if ($core_family){
				$core_desc .= ' ' .oci_result($rsStk, 17);
			}
			if ($core_thk) {
				if ($unit == 'mils') {
					$core_desc .= ' ' .sprintf('%.'.$deci.'f',round(oci_result($rsStk, 7),$deci)) . 'mils';
				} else {
					$core_desc .= ' ' .sprintf('%.'.$deci.'f',round(oci_result($rsStk, 7)*0.0254,$deci)) . 'mm';
				}
			}
			if ($const) {
				$core_desc .= ' ' . oci_result($rsStk, 25);
			}
			if ($copper_weight) {
				$core_desc .= '@cw@';
			}
			if ($core_tg) {
				$core_desc .= ' ' . oci_result($rsStk, 29);
			}
			if ($core_foil_tr) {
				if ($site == 'GZ'){
     		      $sql="select (select value from field_enum_translate where intname='COPPER_LAYER' and fldname='COPPER_VENDOR_' and field_enum_translate.enum=cpd.copper_vendor_ ) as copper_vendor
                    from public_links ln ,items cp,copper_layer_da cpd 
                     where (ln.link_type=22 or ln.link_type=23 ) and ln.item_id=".oci_result($rsStk, 33)." and ln.points_to =cp.item_id 
                    and cp.item_id=cpd.item_id and cp.last_checked_in_rev=cpd.revision_id ";
                    $rs = oci_parse($conn, $sql);
                    oci_execute($rs, OCI_DEFAULT);
					$tmp='';
                     while(oci_fetch($rs)){
                        if ($tmp==''){
							$tmp=oci_result($rs, 1);
						}else{
						   if ($tmp!=oci_result($rs, 1)&& $tmp!='N/A'){
							   $tmp=$tmp.'/'.oci_result($rs, 1);
						   }
						}
						  //$core_desc .= ' ' . oci_result($rs, 1);
					 }
					 if ($tmp!='N/A'){
					     $core_desc .= ' ' . $tmp;
					   } 
			  } else {
				$mrp_rev_description = oci_result($rsStk, 3);
				if (strstr($mrp_rev_description, ' RTF ')) {
					$core_desc .= ' ' . 'RTF';
				} elseif (strstr($mrp_rev_description, ' HPS ')) {
					$core_desc .= ' ' . 'HPS';
				} elseif (strstr($mrp_rev_description, ' VLP ')) {
					$core_desc .= ' ' . 'VLP';
				} elseif (strstr($mrp_rev_description, ' HVLP ')) {
					$core_desc .= ' ' . 'HVLP';
				} elseif (strstr($mrp_rev_description, ' HTE ')) {
					$core_desc .= ' ' . 'HTE';
				} elseif (strstr($mrp_rev_description, ' ED ')) {
					$core_desc .= ' ' . 'ED';
				} elseif (strstr($mrp_rev_description, ' VSP ')) {
					$core_desc .= ' ' . 'VSP';
				} elseif (strstr($mrp_rev_description, ' HS_M2_VSP ')) {
					$core_desc .= ' ' . 'HS_M2_VSP';
				} elseif (strstr($mrp_rev_description, ' HS-M2-VSP ')) {
					$core_desc .= ' ' . 'HS-M2-VSP';
				} elseif (strstr($mrp_rev_description, ' VLP2 ')) {
					$core_desc .= ' ' . 'VLP2';
				} elseif (strstr($mrp_rev_description, ' VLP3 ')) {
					$core_desc .= ' ' . 'VLP3';
				} elseif (strstr($mrp_rev_description, ' LP ')) {
					$core_desc .= ' ' . 'LP';
				} elseif (strstr($mrp_rev_description, ' HS-VSP ')) {
					$core_desc .= ' ' . 'HS-VSP';
				} elseif (strstr($mrp_rev_description, ' DT ')) {
					$core_desc .= ' ' . 'DT';
				} elseif (strstr($mrp_rev_description, ' TWS ')) {
					$core_desc .= ' ' . 'TWS';
				} elseif (strstr($mrp_rev_description, ' VLP2/RTF ')) {
					$core_desc .= ' ' . 'VLP2/RTF';
				} elseif (strstr($mrp_rev_description, ' HVLP/RTF ')) {
					$core_desc .= ' ' . 'HVLP/RTF';
				}
			}
				}
			if ($core_size) { 
				$grain = oci_result($rsStk, 28);
				if(oci_result($rsStk, 26)==0&&oci_result($rsStk, 27)==0){
				    $core_desc .= ' ' . oci_result($rsStk, 34);
				}else{
					if ($grain == 'Width') {
						if ($site == 'HY') {
							$core_desc .= ' ' . round(oci_result($rsStk, 26)/1000,2) . 'x' . round(oci_result($rsStk, 27)/1000,2) . 'G';
						} else {
							$core_desc .= ' ' . round(oci_result($rsStk, 26)/1000,2) . 'Gx' . round(oci_result($rsStk, 27)/1000,2);
						}
					} else {
						if ($site == 'HY') {
							$core_desc .= ' ' . round(oci_result($rsStk, 26)/1000,2) . 'Gx' . round(oci_result($rsStk, 27)/1000,2);
						} else {
							$core_desc .= ' ' . round(oci_result($rsStk, 26)/1000,2) . 'x' . round(oci_result($rsStk, 27)/1000,2).'G';
						}
					}
				}				 
			}
			
			

            $tmpTop = " ";
            $tmpBot = " ";
            $tmpCuTop = " ";
            $tmpCuBot = " ";
			$tmptoptoal = " ";
			$tmpbottoal = " ";
			$cuUsageTop = " ";
			$cuUsageBot = " ";
			if(oci_result($rsStk, 5)=== "Both"){
				$tmpdescription = oci_result($rsStk, 9);
                $tmpTop = oci_result($rsStk, 6);
				$tmpTopRel = oci_result($rsStk, 32);
                $tmpCuTop = round(oci_result($rsStk, 10),3)." oz";
				$tmpCuTopNum = oci_result($rsStk, 10);
				if ($unit == 'mils') { 
					$tmptoptoal = sprintf('%.'.$deci.'f',oci_result($rsStk, 11));
					$lam_thk = sprintf('%.'.$deci.'f',oci_result($rsStk, 7));
				} else {
					$tmptoptoal = sprintf('%.'.$deci.'f',oci_result($rsStk, 11)*0.0254);
					$lam_thk = sprintf('%.'.$deci.'f',oci_result($rsStk, 7)*0.0254);
				}
				$thick_over_lam +=oci_result($rsStk, 11);
				$cuUsageTop = oci_result($rsStk, 22);
				if ($tmpTop == 1) {
					$out_foil_type = $tmpdescription;
				}
				if(oci_fetch($rsStk)){
					$segType =  $tmpdescription . "_" . oci_result($rsStk, 9) . "_core";
                    $tmpBot = oci_result($rsStk, 6);
					$tmpBotRel = oci_result($rsStk, 32);
                    $tmpCuBot = round(oci_result($rsStk, 10),3)." oz";
					$tmpCuBotNum = oci_result($rsStk, 10);
					if ($unit == 'mils') { 
						$tmpbottoal = sprintf('%.'.$deci.'f',oci_result($rsStk, 11));
					} else {
						$tmpbottoal = sprintf('%.'.$deci.'f',oci_result($rsStk, 11)*0.0254);
					}
					$thick_over_lam +=oci_result($rsStk, 11);
					$cuUsageBot = oci_result($rsStk, 22);
					if ($copper_weight) {
						/*if ($tmpCuTopNum  <= $tmpCuBotNum ) {
							$core_desc =str_ireplace('@cw@', ' ' .$tmpCuTop .' / ' .  $tmpCuBot,$core_desc);
						} else {
							$core_desc =str_ireplace('@cw@', ' ' .$tmpCuBot .' / ' . $tmpCuTop ,$core_desc);
						}*/
						$core_desc =str_ireplace('@cw@', ' ' .$tmpCuTop .' / ' .  $tmpCuBot,$core_desc);
					}
				}
				set_row_style($l);
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$l,$tmpTop);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$l,$tmptoptoal);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$l,$tmpCuTop);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$l,'');
				$objPHPExcel->getActiveSheet()->getStyle('B'.$l)->getNumberFormat()->setFormatCode($num_format);
				set_core_color($l);
				$layer_cell[$tmpTopRel] = $l;
				$l++;
				set_row_style($l);
				$objPHPExcel->getActiveSheet()->getStyle('B'.$l)->getNumberFormat()->setFormatCode($num_format);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$l,$lam_thk);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'  '.$core_desc );
				/*if ($dk and $thk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 19));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$lam_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode($num_format);
				} elseif ($dk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 19));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
				}  elseif ($thk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$lam_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode($num_format);
				} else {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'  '.$core_desc);
				}*/
			if ($dk and $thk and $df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 19));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,oci_result($rsStk, 35));
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$lam_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('H'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			        $objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('H'.$l)->getNumberFormat()->setFormatCode($num_format);
		     } elseif ($thk and $dk ) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 19));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$lam_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode($num_format);
			 } elseif ($dk and $df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 19));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,oci_result($rsStk, 35));
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			 }  elseif ($thk and $df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 35));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$lam_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode($num_format);
			 } elseif ($dk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 19));
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$core_desc);
			 }  elseif ($thk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$lam_thk);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode($num_format);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$core_desc);
		 	 }  elseif ($df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 35));
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$core_desc);
			 } else {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'  '.$core_desc);
			}
				//place_pic ($segType,'D'.$l,0);
				$place_core[$l]= $segType;
				
				$l++;
				set_row_style($l);
				$objPHPExcel->getActiveSheet()->getStyle('B'.$l)->getNumberFormat()->setFormatCode($num_format);
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$l,$tmpBot);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$l,$tmpbottoal);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$l,$tmpCuBot);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$l,'');
				set_core_color($l);
				$layer_cell[$tmpBotRel] = $l;
				$l++;
			}
			elseif(oci_result($rsStk, 5)=== "Top"){
				$segType = oci_result($rsStk, 9) . "_" . oci_result($rsStk, 12) . "_core";
                $tmpTop = oci_result($rsStk, 6);
				$tmpTopRel = oci_result($rsStk, 32);
                $tmpCuTop = round(oci_result($rsStk, 10),3)." oz";
				if ($copper_weight) {
						$core_desc =str_ireplace('@cw@', ' ' .$tmpCuTop .' / ' .  '0',$core_desc);
				}
				if ($unit == 'mils') { 
					$tmptoptoal = sprintf('%.'.$deci.'f',oci_result($rsStk, 11));
					$lam_thk = sprintf('%.'.$deci.'f',oci_result($rsStk, 7));
				} else {
					$tmptoptoal = sprintf('%.'.$deci.'f',oci_result($rsStk, 11) * 0.0254);
					$lam_thk = sprintf('%.'.$deci.'f',oci_result($rsStk, 7)*0.0254);
				}
				
				$thick_over_lam +=oci_result($rsStk, 11);
				$tmpBot =" &nbsp";
				$cuUsageTop = oci_result($rsStk, 22);
				set_row_style($l);
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$l,$tmpTop);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$l,$tmptoptoal);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$l,$tmpCuTop);
				//$objPHPExcel->getActiveSheet()->setCellValue('D'.$l,oci_result($rsStk, 11));
				$objPHPExcel->getActiveSheet()->getStyle('B'.$l)->getNumberFormat()->setFormatCode($num_format);
				set_core_color($l);
				$layer_cell[$tmpTopRel] = $l;
				$l++;
				set_row_style($l);
				$objPHPExcel->getActiveSheet()->getStyle('B'.$l)->getNumberFormat()->setFormatCode($num_format);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$l,$lam_thk);
				/*if ($dk and $thk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 19));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$lam_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode($num_format);
				} elseif ($dk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 19));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
				}  elseif ($thk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$lam_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode($num_format);
				} else {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'  '.$core_desc);
				}*/

              if ($dk and $thk and $df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 19));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,oci_result($rsStk, 35));
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$lam_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('H'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			        $objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('H'.$l)->getNumberFormat()->setFormatCode($num_format);
		     } elseif ($thk and $dk ) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 19));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$lam_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode($num_format);
			 } elseif ($dk and $df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 19));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,oci_result($rsStk, 35));
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			 }  elseif ($thk and $df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 35));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$lam_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode($num_format);
			 } elseif ($dk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 19));
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$core_desc);
			 }  elseif ($thk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$lam_thk);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode($num_format);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$core_desc);
		 	 }  elseif ($df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 35));
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$core_desc);
			 } else {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'  '.$core_desc);
			}


				//place_pic ($segType,'D'.$l,0);
				$place_core[$l]= $segType;
				
				$l++;
			}
			elseif(oci_result($rsStk, 5)=== "Bottom"){
				$segType = oci_result($rsStk, 9) . "_" . oci_result($rsStk, 12) . "_core";
                $tmpBot = oci_result($rsStk, 6);
				$tmpBotRel = oci_result($rsStk, 32);
                $tmpCuBot = round(oci_result($rsStk, 10),3)." oz";
				if ($copper_weight) {
						$core_desc =str_ireplace('@cw@', ' ' .'0 / ' .  $tmpCuBot,$core_desc);
				}
				if ($unit == 'mils') { 
					$tmpbottoal = sprintf('%.'.$deci.'f',oci_result($rsStk, 11));
					$lam_thk = sprintf('%.'.$deci.'f',oci_result($rsStk, 7));
				} else {
					$tmpbottoal = sprintf('%.'.$deci.'f',oci_result($rsStk, 11)*0.0254);
					$lam_thk = sprintf('%.'.$deci.'f',oci_result($rsStk, 7)*0.0254);
				}
				
				$thick_over_lam +=oci_result($rsStk, 11);
				$tmpTop = " &nbsp";
				$cuUsageBot = oci_result($rsStk, 22);
				set_row_style($l);
				$objPHPExcel->getActiveSheet()->getStyle('B'.$l)->getNumberFormat()->setFormatCode($num_format);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$l,$lam_thk);
				/*if ($dk and $thk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 19));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$lam_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode($num_format);
				} elseif ($dk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 19));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
				}  elseif ($thk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$lam_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode($num_format);
				} else {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'  '.$core_desc);
				}*/
			 if ($dk and $thk and $df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 19));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,oci_result($rsStk, 35));
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$lam_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('H'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			        $objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('H'.$l)->getNumberFormat()->setFormatCode($num_format);
		     } elseif ($thk and $dk ) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 19));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$lam_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode($num_format);
			 } elseif ($dk and $df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 19));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,oci_result($rsStk, 35));
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			 }  elseif ($thk and $df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 35));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$lam_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode($num_format);
			 } elseif ($dk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 19));
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$core_desc);
			 }  elseif ($thk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$lam_thk);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode($num_format);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$core_desc);
		 	 }  elseif ($df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 35));
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$core_desc);
			 } else {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'  '.$core_desc);
			}
				//place_pic ($segType,'D'.$l,0);
				$place_core[$l]= $segType;
				
				$l++;
				set_row_style($l);
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$l,$tmpBot);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$l,$tmpbottoal);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$l,$tmpCuBot);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$l,'');
				$objPHPExcel->getActiveSheet()->getStyle('B'.$l)->getNumberFormat()->setFormatCode($num_format);
				set_core_color($l);
				$layer_cell[$tmpBotRel] = $l;
				$l++;
			} else{
				$place_core[$l]= 'core';
				set_row_style($l);
				if ($copper_weight) {
						$core_desc =str_ireplace('@cw@', ' ' . oci_result($rsStk, 24),$core_desc);
				}
				if ($unit == 'mils') { 
					$lam_thk = sprintf('%.'.$deci.'f',oci_result($rsStk, 7));
				} else {
					$lam_thk = sprintf('%.'.$deci.'f',oci_result($rsStk, 7)*0.0254);
				}
				$objPHPExcel->getActiveSheet()->getStyle('B'.$l)->getNumberFormat()->setFormatCode($num_format);
				/*if ($dk and $thk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 19));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$lam_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode($num_format);
				} elseif ($dk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 19));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
				}  elseif ($thk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$lam_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode($num_format);
				} else {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'  '.$core_desc);
				}*/
			if ($dk and $thk and $df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 19));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,oci_result($rsStk, 35));
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$lam_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('H'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			        $objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('H'.$l)->getNumberFormat()->setFormatCode($num_format);
		     } elseif ($thk and $dk ) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 19));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$lam_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode($num_format);
			 } elseif ($dk and $df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 19));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,oci_result($rsStk, 35));
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
			 }  elseif ($thk and $df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 35));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$lam_thk);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$l,$core_desc);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->getStyle('G'.$l)->getNumberFormat()->setFormatCode($num_format);
			 } elseif ($dk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 19));
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$core_desc);
			 }  elseif ($thk) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,$lam_thk);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode($num_format);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$core_desc);
		 	 }  elseif ($df) {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,oci_result($rsStk, 35));
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$l)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$l,$core_desc);
			 } else {
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$l,'  '.$core_desc);
			}
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$l,$lam_thk);
				set_core_color($l);
				
				$l++;
			}
			
		} else{
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
			
			if ($pp_size) {
				if(oci_result($rsStk, 26)==0 && oci_result($rsStk, 27)==0){
				   $ppsize = ' ' . oci_result($rsStk, 34);
				}else{
					$grain = oci_result($rsStk, 28);
					if ($grain == 'Width') {
						$ppsize = ' ' . round(oci_result($rsStk, 26)/1000,2) . 'Gx' . round(oci_result($rsStk, 27)/1000,2);
					} else {
						$ppsize = ' ' . round(oci_result($rsStk, 26)/1000,2) . 'x' . round(oci_result($rsStk, 27)/1000,2).'G';
					}
				}
			}

			if ($add_pp_thick ==1 ) {
				$thick_over_lam +=oci_result($rsStk, 8);
				$add_pp_thick = 0;
			} 
			
			$add_core_thick = 1;
			$add_foil_thick = 1;
			
			$pp_family = oci_result($rsStk, 17);
			if ($unit == 'mils') {
				$pp_over_thk = sprintf('%.'.$deci.'f',oci_result($rsStk, 20));
				$pp_press_thk = oci_result($rsStk, 21);
			} else {
				$pp_over_thk = sprintf('%.'.$deci.'f',oci_result($rsStk, 20)*0.0254);
				$pp_press_thk = oci_result($rsStk, 21)*0.0254;
			}
			
			if ($pp_tg == 1) { 
				$pptg = oci_result($rsStk, 30);
			} else {$pptg = '';}
			if ($dk) { 
				$pp_dk += round(oci_result($rsStk, 19) * (oci_result($rsStk, 7) / oci_result($rsStk, 20)),3);
			} else {$pp_dk = '';}
			if ($df) { 
				$pp_df =oci_result($rsStk, 35);
			} else {$pp_df = '';}
		}
}
if (count($place_foil) > 0) {
	$first_foil = 1;
	foreach ($place_foil as $key =>$value) {
		if ($first_foil) {
			place_pic($value,'D'.$key,5);
			$first_foil = 0;
		} else {
			place_pic($value,'D'.$key,5);
		}	
	}
}
$key='';
$value='';
foreach ($place_core as $key =>$value) {
	place_pic($value,'D'.$key,-5);
	set_pp_color($key,$col);
}

if ($out_foil_type) {
	place_pic($out_foil_type.'_msk_up','D'.$l_start,5);
	place_pic($out_foil_type.'_msk_dn','D'.$l,-5);
}
$l_end = $l+1;
$objPHPExcel->getActiveSheet()->getRowDimension($l)->setRowHeight(2);

if (($site == 'HY' or $site=='GZ') and $outer_cu_thk == 1) {
	$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.($l_end-1)), 'A'.$l_end.':B'.$l_end );
	$objPHPExcel->getActiveSheet()->getStyle('A'.$l_end)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->mergeCells('B'.$l_end.':M'.$l_end);
	$objStyleB=$objPHPExcel->getActiveSheet()->getStyle('B'.$l_end);
	$objStyleB->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('B'.($l_end))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->setCellValue('A'.($l_end),'*');
	$objPHPExcel->getActiveSheet()->setCellValue('B'.($l_end),'The outer finished copper thickness showed on the stack-up is nominal thickness instead of minimum value. The nominal value is used to simulate stack-up . The minimum value will follow the spec or IPC 6012 which specified.');
	$objPHPExcel->getActiveSheet()->getRowDimension($l_end)->setRowHeight(25);
	$objStyle=$objPHPExcel->getActiveSheet()->getStyle('B'.$l_end);
	$objStyle->getFont()->setSize(8);
	$l_end +=2;
	set_row_style($l_end-1,2);
}

set_row_style($l_end,18);
$alignment = $objPHPExcel->getActiveSheet()->getStyle('A'.$l_end)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_BOTTOM);
$alignment = $objPHPExcel->getActiveSheet()->getStyle('C'.$l_end)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_BOTTOM);
$alignment = $objPHPExcel->getActiveSheet()->getStyle('E'.$l_end)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_BOTTOM);
$alignment = $objPHPExcel->getActiveSheet()->getStyle('G'.$l_end)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_BOTTOM);
$alignment = $objPHPExcel->getActiveSheet()->getStyle('H'.$l_end)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_BOTTOM);

if ($show_drill_table != 1) {
	$objPHPExcel->getActiveSheet()->setCellValue('A'.($l_end),'Layer');
	$objPHPExcel->getActiveSheet()->setCellValue('C'.($l_end),'Drill Type');
	/*$objPHPExcel->getActiveSheet()->setCellValue('E'.($l_end),'Via Fill');*/
	set_row_style($l_end,12);
}
if ($site == 'GZ' || $site == 'ZS') { 
	$objPHPExcel->getActiveSheet()->setCellValue('G'.($l_end),'=(SUM(G'.($l_start+1).':G'.($l-2).')+SUM(B'.($l_start+1).':B'.($l-2).'))');
} else {
	$objPHPExcel->getActiveSheet()->setCellValue('G'.($l_end),'=(SUM(D'.($l_start+1).':D'.($l-2).')+SUM(B'.($l_start+1).':B'.($l-2).'))');
}

$objPHPExcel->getActiveSheet()->getStyle('G'.$l_end)->getNumberFormat()->setFormatCode($num_format);
$objPHPExcel->getActiveSheet()->setCellValue('H'.($l_end),'Thickness over Laminate');
$objPHPExcel->getActiveSheet()->setCellValue('H'.($l_end+1),'Thickness over Copper');
$objPHPExcel->getActiveSheet()->getStyle('G'.($l_end+1))->getNumberFormat()->setFormatCode($num_format);
$objPHPExcel->getActiveSheet()->setCellValue('H'.($l_end+2),'Thickness over Soldermask');

$top_cu = $objPHPExcel->getActiveSheet()->getCell('B'.($l_start))->getValue();
if ($site == 'GZ' || $site == 'ZS') {
	$objPHPExcel->getActiveSheet()->setCellValue('G'.($l_end+1),'=(SUM(G'.($l_start+1).':G'.($l-2).')+SUM(B'.($l_start).':B'.($l-1).'))');
	if ($unit == 'mils') {
		$objPHPExcel->getActiveSheet()->setCellValue('G'.($l_end+2),'=1+(SUM(G'.($l_start+1).':G'.($l-2).')+SUM(B'.($l_start).':B'.($l-1).'))');
	} else {
		$objPHPExcel->getActiveSheet()->setCellValue('G'.($l_end+2),'=0.0254+(SUM(G'.($l_start+1).':G'.($l-2).')+SUM(B'.($l_start).':B'.($l-1).'))');
	}
	if ($top_cu == 0.6) {
		$outer_cu = 2;
	} elseif ($top_cu == 1.2) {
		$outer_cu = 2.6;
	} elseif ($top_cu == 0.4) {
		$outer_cu = 1.8;
	} else {
		$outer_cu = $top_cu * 1.2;
	}
	$objPHPExcel->getActiveSheet()->setCellValue('B'.($l_start),$outer_cu);
	$objPHPExcel->getActiveSheet()->setCellValue('B'.($l - 1),$outer_cu);
	$outer_desc = $objPHPExcel->getActiveSheet()->getCell('H'.($l_start))->getValue();
	$outer_desc .=' + Plating';
	$objPHPExcel->getActiveSheet()->setCellValue('H'.($l_start),$outer_desc);
	$objPHPExcel->getActiveSheet()->setCellValue('H'.($l - 1),$outer_desc);
} else {
	$objPHPExcel->getActiveSheet()->setCellValue('G'.($l_end+1),'=(SUM(D'.($l_start+1).':D'.($l-2).')+SUM(B'.($l_start+1).':B'.($l-2).')+'.($top_cu*2).')');
	if ($unit == 'mils') {
		$objPHPExcel->getActiveSheet()->setCellValue('G'.($l_end+2),'=1+(SUM(D'.($l_start+1).':D'.($l-2).')+SUM(B'.($l_start+1).':B'.($l-2).')+'.($top_cu*2).')');
	} else {
		$objPHPExcel->getActiveSheet()->setCellValue('G'.($l_end+2),'=0.0254+(SUM(D'.($l_start+1).':D'.($l-2).')+SUM(B'.($l_start+1).':B'.($l-2).')+'.($top_cu*2).')');
	}
	if (!$outer_cu_thk) {
		$objPHPExcel->getActiveSheet()->setCellValue('B'.($l_start),'CU+Plating');
		$objPHPExcel->getActiveSheet()->setCellValue('B'.($l - 1),'CU+Plating');
	}
}


$objPHPExcel->getActiveSheet()->getStyle('G'.($l_end+2))->getNumberFormat()->setFormatCode($num_format);
set_row_style($l_end+2,12);


/* Get drill information */
include("drill_query.php");
$rsDrill = oci_parse($conn, $sql);
oci_execute($rsDrill, OCI_DEFAULT);

$stack_via_count = 0;
$l_end +=1;
while(oci_fetch($rsDrill)){
	set_row_style($l_end,12);
	if ($show_drill_table != 1) { 
		$objPHPExcel->getActiveSheet()->setCellValue('A'.($l_end),oci_result($rsDrill, 2).' - '. oci_result($rsDrill, 3));
		$objPHPExcel->getActiveSheet()->setCellValue('C'.($l_end),oci_result($rsDrill, 4));
		/*if (oci_result($rsDrill, 5)!=1000) {
			$objPHPExcel->getActiveSheet()->setCellValue('E'.($l_end),'Yes');
		} else {
			$objPHPExcel->getActiveSheet()->setCellValue('E'.($l_end),'--');
		}*/
		$l_end++;
	}
	
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
	$drills[$d_index]['min_drill_size'] = oci_result($rsDrill, 11);
	if ($site == 'SJ' or $site == 'FG' or $site == 'MI') {
		$drills[$d_index]['min_pad_size'] = oci_result($rsDrill, 12);
		$drills[$d_index]['drill_depth_override'] = oci_result($rsDrill, 13);
		$drills[$d_index]['do_not_break_layer'] = oci_result($rsDrill, 16);
	}
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
if ($show_drill_table == 1) {
	$l_end +=3;
} elseif ($d_index == 1) {
	$l_end +=1;
}

$c_drillpgs = count($drills);
$t_c_drillpgs = $c_drillpgs - $stack_via_count + 1;
$d_index = 0;
$d_off_set_1 = 18;
$d_off_set_2 = 19;
$d_off_step = 18.5;
if ($t_c_drillpgs>5 and $t_c_drillpgs<=7) {
	$d_off_step = 12;
} elseif ($t_c_drillpgs>7 and $t_c_drillpgs<=10) {
	$d_off_step = 10;
} elseif ($t_c_drillpgs>10 and $t_c_drillpgs<=13) {
	$d_off_step = 8;
} elseif ($t_c_drillpgs>13 and $t_c_drillpgs<=15) {
	$d_off_step = 6;
} elseif ($t_c_drillpgs>16 and $t_c_drillpgs<=20) {
	$d_off_set_1 = 5;
	$d_off_set_2 = 6;
	$d_off_step = 6;
} elseif ($t_c_drillpgs>20 and $t_c_drillpgs<=30) {
	$d_off_set_1 = 5;
	$d_off_set_2 = 6;
	$d_off_step = 6;
} elseif ($t_c_drillpgs>30) { 
	$d_off_set_1 = 3;
	$d_off_set_2 = 4;
	$d_off_step = 4;
}
if ($c_drillpgs) {
	foreach ($drills as $drill =>$value) {
		$drill_type_pic = 'drill';
		if (strstr($value['vf_type'],'Non-Conductive')) {
			$drill_type_pic = 'non_c_drill';
		} elseif (strstr($value['vf_type'],'Conductive')) {
			$drill_type_pic = 'c_drill';
		} 
		if ($value['end_layer'] > $value['start_layer']) {
			$s_layer = $value['start_layer'];
			$e_layer = $value['end_layer'];
			if ($value['tech'] == 2) $drill_type_pic  = 'laser_drill_up';
		} else {
			$s_layer = $value['end_layer'];
			$e_layer = $value['start_layer'];
			if ($value['tech'] == 2) $drill_type_pic  = 'laser_drill_dn';
		}
		#2007
		//$total_height =  (abs($layer_cell[$value['end_layer']]-$layer_cell[$value['start_layer']])) * 8.5 *1.005* 26.5/21;
		$total_height =  (abs($layer_cell[$value['end_layer']]-$layer_cell[$value['start_layer']])) * 8.5 *1.005* 29/21;
		
		/* check free space */
		$has_free_space = 0;
		foreach ($free_spaces as $fs =>$s_val) {
			if ($s_val['total_height'] > $total_height and ($s_layer> $s_val['end_layer'] or $e_layer< $s_val['start_layer'])) {
				if ($s_layer > 1) {
					place_pic($drill_type_pic,$s_val['col'].($layer_cell[$s_layer]+1),-3,$s_val['off_set'],$total_height);
				} else {
					place_pic($drill_type_pic,$s_val['col'].($layer_cell[$s_layer]+1),1,$s_val['off_set'],$total_height);
				}
				$has_free_space = 1;
				unset($free_spaces[$fs]);
				break;
			}
		}
		if ($value['stack_via'] == 'Y') {
			place_pic($drill_type_pic,'D'.($layer_cell[$s_layer]+1),-3,$d_off_set_1,$total_height);
		} elseif ($has_free_space == 0) {
			if ($d_index%2 == 0) {
				if ($s_layer > $layer_count / 2) {
					place_pic($drill_type_pic,'D'.($layer_cell[$s_layer]+1),-3,$d_off_set_1,$total_height);
				} else {
					place_pic($drill_type_pic,'D'.($layer_cell[$s_layer]+1),1,$d_off_set_1,$total_height);
				}
				if ($e_layer - $s_layer +1 <> $num_layers) {
					$free_spaces_index +=1;
					$free_spaces[$free_spaces_index]['col'] = 'D'; 
					$free_spaces[$free_spaces_index]['start_layer'] = $s_layer;
					$free_spaces[$free_spaces_index]['end_layer'] = $e_layer; 						
					$free_spaces[$free_spaces_index]['col'] = 'D'; 
					$free_spaces[$free_spaces_index]['off_set'] = $d_off_set_1;
					$free_spaces[$free_spaces_index]['total_height'] = $total_height =  (abs($layer_cell[$num_layers]-$layer_cell[1])) * 8.5 *1.005* 26.5/21 - $total_height;
				}
				$d_off_set_1 += $d_off_step;
			} else {
				if ($s_layer > $layer_count / 2) { 
					place_pic($drill_type_pic,'E'.($layer_cell[$s_layer]+1),-3,$d_off_set_2,$total_height);
				} else {
					place_pic($drill_type_pic,'E'.($layer_cell[$s_layer]+1),1,$d_off_set_2,$total_height);
				}
				if ($e_layer - $s_layer +1 <> $num_layers) {
					$free_spaces_index +=1;
					$free_spaces[$free_spaces_index]['col'] = 'E'; 
					$free_spaces[$free_spaces_index]['start_layer'] = $s_layer;
					$free_spaces[$free_spaces_index]['end_layer'] = $e_layer; 
					$free_spaces[$free_spaces_index]['off_set'] = $d_off_set_2;
					$free_spaces[$free_spaces_index]['total_height'] = $total_height =  (abs($layer_cell[$num_layers]-$layer_cell[1])) * 8.5 *1.005* 26.5/21 - $total_height;
				}
				$d_off_set_2 += $d_off_step;
			}
			$d_index ++;
		}	
	}
}

/*  drill table */
if ($show_drill_table == 1) { 
	if ($back_drill) {
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$l_end.':L'.$l_end);
	} else {
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$l_end.':K'.$l_end);
	}
	$objPHPExcel->getActiveSheet()->setCellValue('A'.($l_end),'Drill Table');
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getFont()->setSize(11);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	if ($back_drill) { 
		$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.($l_end)), 'A'.$l_end.':L'.$l_end );
	} else {
		$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.($l_end)), 'A'.$l_end.':K'.$l_end );
	}
	
	$l_end +=1;
	$objPHPExcel->getActiveSheet()->setCellValue('A'.($l_end),'Start Layer');
	$objPHPExcel->getActiveSheet()->setCellValue('B'.($l_end),'End Layer');
	$objPHPExcel->getActiveSheet()->setCellValue('C'.($l_end),'Drill Type');
	$objPHPExcel->getActiveSheet()->setCellValue('D'.($l_end),'Plate Type');
	$objPHPExcel->getActiveSheet()->mergeCells('E'.$l_end.':G'.$l_end);
	/*$objPHPExcel->getActiveSheet()->setCellValue('E'.($l_end),'Via Fill');*/
	$objPHPExcel->getActiveSheet()->setCellValue('H'.($l_end),'Drill Size (min)');
	$objPHPExcel->getActiveSheet()->setCellValue('I'.($l_end),'Drill Depth');
	$objPHPExcel->getActiveSheet()->setCellValue('J'.($l_end),'Pad Size(min)');
	$objPHPExcel->getActiveSheet()->setCellValue('K'.($l_end),'Stacked Vias');
	if ($back_drill) { 
		$objPHPExcel->getActiveSheet()->setCellValue('L'.($l_end),'Do Not Break Layer');
		//$objPHPExcel->getActiveSheet()->setCellValue('M'.($l_end),'Backdrill Depth');
	}
	$objStyleB=$objPHPExcel->getActiveSheet()->getStyle('A'.$l_end);
	$objStyleB->getAlignment()->setWrapText(true);
	$objStyleB->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_BOTTOM);
	$objStyleB->getFont()->setSize(8);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	if ($back_drill) { 
		$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.($l_end)), 'A'.$l_end.':L'.$l_end );
	} else {
		$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.($l_end)), 'A'.$l_end.':K'.$l_end );
	}
	
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	if ($back_drill) { 
		$objPHPExcel->getActiveSheet()->duplicateStyle($objStyleB, 'A'.$l_end.':L'.$l_end );
	} else {
		$objPHPExcel->getActiveSheet()->duplicateStyle($objStyleB, 'A'.$l_end.':K'.$l_end );
	}
	
	$objPHPExcel->getActiveSheet()->getStyle('B'.($l_end))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$l_end)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	if ($back_drill) { 
		$objPHPExcel->getActiveSheet()->getStyle('L'.$l_end)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	} else {
		$objPHPExcel->getActiveSheet()->getStyle('K'.$l_end)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	}
	if ($c_drillpgs) {
		$l_end +=1;
		foreach ($drills as $drill =>$value) {
			set_row_style($l_end,15);
			$objPHPExcel->getActiveSheet()->setCellValue('A'.($l_end),$value['start_layer']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.($l_end),$value['end_layer']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.($l_end),$value['tech_s']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.($l_end),$value['drill_type']);
			$objPHPExcel->getActiveSheet()->mergeCells('E'.$l_end.':G'.$l_end);
			/*if ($value['vf_type'] !='None') {
				$objPHPExcel->getActiveSheet()->setCellValue('E'.($l_end),$value['vf_type']);
			} else {
				$objPHPExcel->getActiveSheet()->setCellValue('E'.($l_end),'--');
			}*/

			if ($unit == 'mils') {
				if ($value['drill_size_dfm']>0) {
					$objPHPExcel->getActiveSheet()->setCellValue('H'.($l_end),$value['drill_size_dfm']);
				} else {
					$objPHPExcel->getActiveSheet()->setCellValue('H'.($l_end),$value['min_drill_size']);
				}
				
				if ($value['min_pad_size']) $objPHPExcel->getActiveSheet()->setCellValue('J'.($l_end),$value['min_pad_size']);
				if ($value['drill_depth_override']>0) {
						$objPHPExcel->getActiveSheet()->setCellValue('I'.($l_end),$value['drill_depth_override']);
					} else {
						$objPHPExcel->getActiveSheet()->setCellValue('I'.($l_end),$value['drill_depth']);
				}
			} else {
				if ($value['drill_size_dfm']>0) {
					$objPHPExcel->getActiveSheet()->setCellValue('H'.($l_end),$value['drill_size_dfm']*0.0254);
				} else {
					$objPHPExcel->getActiveSheet()->setCellValue('H'.($l_end),$value['min_drill_size']*0.0254);
				}
				if ($value['min_pad_size']) $objPHPExcel->getActiveSheet()->setCellValue('J'.($l_end),$value['min_pad_size']*0.0254);
				if ($value['drill_depth_override']>0) {
						$objPHPExcel->getActiveSheet()->setCellValue('I'.($l_end),$value['drill_depth_override']*0.0254);
					} else {
						$objPHPExcel->getActiveSheet()->setCellValue('I'.($l_end),$value['drill_depth']*0.0254);
				}
			}
			$objPHPExcel->getActiveSheet()->setCellValue('K'.($l_end),$value['stack_via']);
			if ($value['tech_s'] == 'CDD') {
				if ($value['do_not_break_layer'] > 0) {
					$objPHPExcel->getActiveSheet()->setCellValue('L'.($l_end),$value['do_not_break_layer']);
				} else {
					if ($value['start_layer'] > $value['end_layer']) {
						$objPHPExcel->getActiveSheet()->setCellValue('L'.($l_end),($value['end_layer']-1));
					} else {
						$objPHPExcel->getActiveSheet()->setCellValue('L'.($l_end),($value['end_layer']+1));
					}
				}	
			}
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			if ($back_drill) {
				$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.($l_end)), 'A'.$l_end.':L'.$l_end );
			} else {
				$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.($l_end)), 'A'.$l_end.':K'.$l_end );
			}
			$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			if ($back_drill) { 
				$objPHPExcel->getActiveSheet()->duplicateStyle($objStyleB, 'A'.$l_end.':L'.$l_end );
			} else {
				$objPHPExcel->getActiveSheet()->duplicateStyle($objStyleB, 'A'.$l_end.':K'.$l_end );
			}
			
			$objPHPExcel->getActiveSheet()->getStyle('C'.($l_end))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$objPHPExcel->getActiveSheet()->getStyle('D'.($l_end))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

			$objPHPExcel->getActiveSheet()->getStyle('H'.$l_end)->getNumberFormat()->setFormatCode($num_format);
			$objPHPExcel->getActiveSheet()->getStyle('I'.$l_end)->getNumberFormat()->setFormatCode($num_format);
			$objPHPExcel->getActiveSheet()->getStyle('J'.$l_end)->getNumberFormat()->setFormatCode($num_format);
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$l_end)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
			if ($back_drill) { 
				$objPHPExcel->getActiveSheet()->getStyle('L'.$l_end)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
			} else {
				$objPHPExcel->getActiveSheet()->getStyle('K'.$l_end)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
			}
			$l_end ++;
		}
		$objPHPExcel->getActiveSheet()->getStyle('A'.$l_end)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
		if ($back_drill) { 
			$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.$l_end), 'A'.$l_end.':L'.$l_end );
		} else {
			$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.$l_end), 'A'.$l_end.':K'.$l_end );
		}
	}

}

/* Material list */
if ($show_material_list)  {
	$l_end +=1;
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$l_end.':M'.$l_end);
	$objPHPExcel->getActiveSheet()->setCellValue('A'.($l_end),'Material List');
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getFont()->setSize(11);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.($l_end)), 'A'.$l_end.':M'.$l_end );
	
	$l_end +=1;
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$l_end.':D'.$l_end);
	$objPHPExcel->getActiveSheet()->mergeCells('F'.$l_end.':M'.$l_end);
	$objPHPExcel->getActiveSheet()->setCellValue('A'.($l_end),'Type');
	$objPHPExcel->getActiveSheet()->setCellValue('E'.($l_end),'Count');
	$objPHPExcel->getActiveSheet()->setCellValue('F'.($l_end),'Material Name');
	$objStyleB=$objPHPExcel->getActiveSheet()->getStyle('A'.$l_end);
	$objStyleB->getAlignment()->setWrapText(true);
	$objStyleB->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_BOTTOM);
	$objStyleB->getFont()->setSize(8);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.($l_end)), 'A'.$l_end.':M'.$l_end );
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->duplicateStyle($objStyleB, 'A'.$l_end.':M'.$l_end );
	$objPHPExcel->getActiveSheet()->getStyle('B'.($l_end))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$l_end)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet()->getStyle('M'.$l_end)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	
	$l_end +=1;
	include("material_list.php");
	$rsMl = oci_parse($conn, $sql);
	oci_execute($rsMl, OCI_DEFAULT);
	while(oci_fetch($rsMl)){
		$objPHPExcel->getActiveSheet()->setCellValue('A'.($l_end),oci_result($rsMl, 1));
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$l_end.':D'.$l_end);
		$objPHPExcel->getActiveSheet()->mergeCells('F'.$l_end.':M'.$l_end);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.($l_end),oci_result($rsMl, 3));
		$mat_name = oci_result($rsMl, 4);
		if (oci_result($rsMl, 1) == 'Prepreg') {
			//$mat_name = trim(substr($mat_name ,0,strpos($mat_name ,'%')+1));
			//$mat_name = str_ireplace('Bonded','',$mat_name);
		} elseif (oci_result($rsMl, 1) == 'Core') {
			//$mat_name = preg_replace('/[0-9.]*x[0-9.]* Bonded/','',$mat_name);
		} else {
			//$mat_name = preg_replace('/[0-9.]*x[0-9.]* \(IN\)/','',$mat_name);
		}
		$mat_name = str_ireplace('Bonded','',$mat_name);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.($l_end),$mat_name);
		$objStyleB=$objPHPExcel->getActiveSheet()->getStyle('A'.$l_end);
		$objStyleB->getAlignment()->setWrapText(true);
		$objStyleB->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_BOTTOM);
		$objStyleB->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.($l_end)), 'A'.$l_end.':M'.$l_end );
		$objPHPExcel->getActiveSheet()->getStyle('A'.$l_end)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
		$objPHPExcel->getActiveSheet()->getStyle('M'.$l_end)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
		$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('C'.($l_end))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('E'.($l_end))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$l_end +=1;
	}
	$objPHPExcel->getActiveSheet()->getStyle('A'.$l_end)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.$l_end), 'A'.$l_end.':M'.$l_end );
}

if ($num_imps >0 ) {
	$l_end +=1;
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$l_end.':N'.$l_end);
	$objPHPExcel->getActiveSheet()->setCellValue('A'.($l_end),'Impedance Table');
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getFont()->setSize(11);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.($l_end)), 'A'.$l_end.':N'.$l_end );

	$l_end +=1;
	$objPHPExcel->getActiveSheet()->setCellValue('A'.($l_end),'Layer');
	$objPHPExcel->getActiveSheet()->setCellValue('B'.($l_end),'Structure Type');
	$objPHPExcel->getActiveSheet()->mergeCells('B'.$l_end.':D'.$l_end);
	$objPHPExcel->getActiveSheet()->setCellValue('E'.($l_end),'Coated Microstrip');
	$objPHPExcel->getActiveSheet()->setCellValue('F'.($l_end),'Target Impedance (ohms)');
	$objPHPExcel->getActiveSheet()->setCellValue('G'.($l_end),'Impedance Tolerance (ohms)');
	$objPHPExcel->getActiveSheet()->setCellValue('H'.($l_end),'Target Linewidth ('.$unit.')');
	if ($imp_spacing == 1) {
		$objPHPExcel->getActiveSheet()->setCellValue('I'.($l_end),'Differential Spacing * ('.$unit.')');
	} else {
		$objPHPExcel->getActiveSheet()->setCellValue('I'.($l_end),'Edge Coupled Pitch * ('.$unit.')');
	}
	$objPHPExcel->getActiveSheet()->setCellValue('J'.($l_end),'Reference Layers');
	$objPHPExcel->getActiveSheet()->setCellValue('K'.($l_end),'Modelled Linewidth ('.$unit.')');
    $objPHPExcel->getActiveSheet()->setCellValue('L'.($l_end),'Modelled linespacing('.$unit.')');
	$objPHPExcel->getActiveSheet()->setCellValue('M'.($l_end),'Modelled Impedance (ohms)');
	$objPHPExcel->getActiveSheet()->setCellValue('N'.($l_end),'CoPlaner Space ('.$unit.')');

	$objStyleB=$objPHPExcel->getActiveSheet()->getStyle('A'.$l_end);
	$objStyleB->getAlignment()->setWrapText(true);
	$objStyleB->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_BOTTOM);
	$objStyleB->getFont()->setSize(8);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.($l_end)), 'A'.$l_end.':M'.$l_end );
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->duplicateStyle($objStyleB, 'A'.$l_end.':N'.$l_end );
	$objPHPExcel->getActiveSheet()->getStyle('B'.($l_end))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$l_end)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet()->getStyle('M'.$l_end)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
    $objPHPExcel->getActiveSheet()->getStyle('N'.$l_end)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);


	$l_end +=1;
	include("impedance_query.php");
	$rsImp = oci_parse($conn, $imp_query);
	oci_execute($rsImp, OCI_DEFAULT);
	while(oci_fetch($rsImp)){
		set_row_style($l_end,15);
		$ref_layer = "";
		if (oci_result($rsImp, 10) && oci_result($rsImp, 11)){
			$ref_layer = "(".oci_result($rsImp, 10).", ".oci_result($rsImp, 11).")";
		} else if (oci_result($rsImp, 10)){
			$ref_layer = "(".oci_result($rsImp, 10).")";
		} else if (oci_result($rsImp, 11)){
			$ref_layer = "(".oci_result($rsImp, 11).")";
		}

		if ($site == 'GZ' || $site == 'ZS') {
			$module = oci_result($rsImp,2);
			if (strstr($module,'broadside_coupled')||strstr($module,'broadside_over_core')||strstr($module,'broadside_over_prepreg')) {
				$module = 'Broadside Coupled Differential';
			} elseif (strstr($module,'edge_coupled') or strstr($module,'diff')) {
				$module = 'Edge Coupled Differential';
			} else {
				$module = 'Single Ended';
			}
		} else {
			//$module = ucwords(strtolower(str_ireplace('_',' ',oci_result($rsImp, 2))));
			$module = oci_result($rsImp,2);
			if (strstr($module,'edge_coupled') or strstr($module,'diff')) {
				$module = 'Edge Coupled Differential';
			} else {
				$module = 'Single Ended';
			}
		}
		
		$coated = "---";
		if ((oci_result($rsImp, 3) == 1 || oci_result($rsImp, 3) == oci_result($rsImp, 12))  && strstr(oci_result($rsImp, 2),'uncoated')==false ) $coated ="Yes";

		$coplanar_space = '';
		if (oci_result($rsImp, 14)>0) {
			if ($unit == 'mils') {
				$coplanar_space = sprintf('%.'.$deci.'f',oci_result($rsImp, 14));
			} else {
				$coplanar_space = sprintf('%.'.$deci.'f',oci_result($rsImp, 14)*0.0254);
			}
		}
		
		$objPHPExcel->getActiveSheet()->setCellValue('A'.($l_end),oci_result($rsImp, 3));
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$l_end.':D'.$l_end);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.($l_end),$module);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.($l_end),$coated);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.($l_end),sprintf('%.'.$deci.'f',oci_result($rsImp, 4)));
		$objPHPExcel->getActiveSheet()->setCellValue('G'.($l_end),"+/-".oci_result($rsImp, 5));
		if ($unit == 'mils') {
			$objPHPExcel->getActiveSheet()->setCellValue('H'.($l_end),sprintf('%.'.$deci.'f',oci_result($rsImp, 7)));
		} else {
			$objPHPExcel->getActiveSheet()->setCellValue('H'.($l_end),sprintf('%.'.$deci.'f',oci_result($rsImp, 7)*0.0254));
		}
		if ($unit == 'mils') { 
			if ($imp_spacing == 1 and oci_result($rsImp, 15)>0) {
				$pitch = sprintf('%.'.$deci.'f',oci_result($rsImp, 15)); 
			} elseif ($imp_spacing == 1 and oci_result($rsImp, 16)>0) {
				$pitch = sprintf('%.'.$deci.'f',oci_result($rsImp, 16)); 
			} elseif ($imp_spacing == 1 and oci_result($rsImp, 13)>0) {
				$pitch = sprintf('%.'.$deci.'f',(oci_result($rsImp, 13)-oci_result($rsImp, 7))); 
			} elseif (oci_result($rsImp, 15)>0) {
				$pitch = sprintf('%.'.$deci.'f',(oci_result($rsImp, 15)+oci_result($rsImp, 7))); 
			} elseif (oci_result($rsImp, 16)>0) {
				$pitch = sprintf('%.'.$deci.'f',(oci_result($rsImp, 16)+oci_result($rsImp, 7))); 
			} else {
				$pitch = sprintf('%.'.$deci.'f',oci_result($rsImp, 13)); 
			}
			
		} else {
			if ($imp_spacing == 1 and oci_result($rsImp, 15)>0) {
				$pitch = sprintf('%.'.$deci.'f',oci_result($rsImp, 15)*0.0254); 
			} elseif ($imp_spacing == 1 and oci_result($rsImp, 16)>0) {
				$pitch = sprintf('%.'.$deci.'f',oci_result($rsImp, 16)*0.0254); 
			} elseif ($imp_spacing == 1 and oci_result($rsImp, 13)>0) {
				$pitch = sprintf('%.'.$deci.'f',(oci_result($rsImp, 13)-oci_result($rsImp, 7))*0.0254); 
			} elseif (oci_result($rsImp, 15)>0) {
				$pitch = sprintf('%.'.$deci.'f',(oci_result($rsImp, 15)+oci_result($rsImp, 7))*0.0254); 
			} elseif (oci_result($rsImp, 16)>0) {
				$pitch = sprintf('%.'.$deci.'f',(oci_result($rsImp, 16)+oci_result($rsImp, 7))*0.0254); 
			} else {
				$pitch = sprintf('%.'.$deci.'f',oci_result($rsImp, 13)*0.0254); 
			}
		}
		if ($site == 'GZ' || $site == 'ZS') { 
			if ($module == 'Single Ended') {
				$pitch = '';
			}
		} elseif ($site == 'HY') {
			if ($module == 'Single Ended') {
				$pitch = '/';
			}
		}
		$objPHPExcel->getActiveSheet()->setCellValue('I'.($l_end),$pitch);
		$objPHPExcel->getActiveSheet()->setCellValue('J'.($l_end),$ref_layer);
		if ($unit == 'mils') {
			$objPHPExcel->getActiveSheet()->setCellValue('K'.($l_end),sprintf('%.'.$deci.'f',oci_result($rsImp, 9)));
		} else {
			$objPHPExcel->getActiveSheet()->setCellValue('K'.($l_end),sprintf('%.'.$deci.'f',oci_result($rsImp, 9)*0.0254));
		}
        if ($imp_spacing == 1) {
            $val_w=$objPHPExcel->getActiveSheet()->getCell('H'.($l_end))->getValue();
            $val_s =$objPHPExcel->getActiveSheet()->getCell('I'.($l_end))->getValue();
            $val_mw=$objPHPExcel->getActiveSheet()->getCell('K'.($l_end))->getValue();
            if ($val_s) {
                   $val_ms=$val_w+$val_s-$val_mw;
                   $objPHPExcel->getActiveSheet()->setCellValue('L'.($l_end),sprintf('%.'.$deci.'f', $val_ms));
             }else{
                 $val_ms='';
                $objPHPExcel->getActiveSheet()->setCellValue('L'.($l_end), $val_ms);
             }
           	
		} else {
            $val_pitch =$objPHPExcel->getActiveSheet()->getCell('I'.($l_end))->getValue();
            $val_mw=$objPHPExcel->getActiveSheet()->getCell('K'.($l_end))->getValue();
            if( $val_pitch){
                 $val_ms=$val_pitch-$val_mw;
			     $objPHPExcel->getActiveSheet()->setCellValue('L'.($l_end),sprintf('%.'.$deci.'f', $val_ms));
            }else{
              $val_ms='';
			  $objPHPExcel->getActiveSheet()->setCellValue('L'.($l_end),$val_ms);
            }
           
		}
		$objPHPExcel->getActiveSheet()->setCellValue('M'.($l_end),sprintf('%.'.$deci.'f',oci_result($rsImp, 8)));
		if ($unit == 'mils') { 
			$objPHPExcel->getActiveSheet()->setCellValue('N'.($l_end),$coplanar_space);
		} else {
			$objPHPExcel->getActiveSheet()->setCellValue('N'.($l_end),$coplanar_space);
		}
		
		$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.($l_end)), 'A'.$l_end.':M'.$l_end );
		$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->duplicateStyle($objStyleB, 'A'.$l_end.':N'.$l_end );
		$objPHPExcel->getActiveSheet()->getStyle('B'.($l_end))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$l_end)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle('F'.($l_end))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$l_end)->getNumberFormat()->setFormatCode($num_format);
		$objPHPExcel->getActiveSheet()->getStyle('I'.$l_end)->getNumberFormat()->setFormatCode($num_format);
		$objPHPExcel->getActiveSheet()->getStyle('K'.$l_end)->getNumberFormat()->setFormatCode($num_format);
		$objPHPExcel->getActiveSheet()->getStyle('L'.$l_end)->getNumberFormat()->setFormatCode($num_format);	
	    $objPHPExcel->getActiveSheet()->getStyle('M'.$l_end)->getNumberFormat()->setFormatCode($num_format);	
		$objPHPExcel->getActiveSheet()->getStyle('A'.$l_end)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
		$objPHPExcel->getActiveSheet()->getStyle('M'.$l_end)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
        $objPHPExcel->getActiveSheet()->getStyle('N'.$l_end)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
			
		$l_end++;
	}
	$objPHPExcel->getActiveSheet()->getStyle('A'.$l_end)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
	$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.$l_end), 'A'.$l_end.':M'.$l_end );

	$l_end +=1;
	$objPHPExcel->getActiveSheet()->setCellValue('A'.($l_end),'*');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$l_end)->getFont()->setSize(8);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$l_end)->getFont()->setSize(8);
	if ($imp_spacing == 1) {
		$objPHPExcel->getActiveSheet()->setCellValue('B'.($l_end),'Differential Spacing is measured from the edge line of one differential trace to the edge line of the other.');
	} else {
		$objPHPExcel->getActiveSheet()->setCellValue('B'.($l_end),'Edge Coupled Pitch is measured from the center line of one differential trace to the center line of the other.');
	}

	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
}

if ($job_type == 'RFQ') {
	$l_end +=1;
	$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.($l_end-1)), 'A'.$l_end.':B'.$l_end );
	$objPHPExcel->getActiveSheet()->mergeCells('B'.$l_end.':M'.$l_end);
	$objStyleB=$objPHPExcel->getActiveSheet()->getStyle('B'.$l_end);
	$objStyleB->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('B'.($l_end))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->setCellValue('A'.($l_end),'*');
	$objPHPExcel->getActiveSheet()->setCellValue('B'.($l_end),'This stack-up was created using estimated copper area percentages. (25% signal, 50% mix, 75% plane) Once data is received minor adjustments of traces and pre-preg thickness may occur.');
	$objPHPExcel->getActiveSheet()->getRowDimension($l_end)->setRowHeight(25);
}

if ($site !='HY' and $site !='GZ' and $site !='ZS') {
	include("process_info.php");
	$rsPro = oci_parse($conn, $sql);
	oci_execute($rsPro, OCI_DEFAULT);
	$l_end +=1;
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$l_end.':M'.$l_end);
	$objStyleB=$objPHPExcel->getActiveSheet()->getStyle('A'.$l_end);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$l_end)->getFont()->setSize(8);
	while(oci_fetch($rsPro)){
			$pro_txt .= oci_result($rsPro, 1).' = '.oci_result($rsPro, 2)."\n";
	}
	$objPHPExcel->getActiveSheet()->setCellValue('A'.($l_end),"Process Plating Info\n".rtrim($pro_txt,"\n") );
	$objStyleB->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getRowDimension($l_end)->setRowHeight(55);
}

if ($stk_notes == 1 or $cust_notes == 1) {
	$l_end +=1;
	$n_sql = "select root.item_name job_name
			  ,i.item_name
			  ,(select value from ENUM_VALUES where enum_type = 143 and sn.category = enum) category
			  ,sn.text
			 from items root
				inner join (items i
					inner join snap_note sn
					on i.item_id = sn.item_id
					and i.last_checked_in_rev = sn.revision_id)
				on i.root_id = root.item_id
				and root.item_type = 2
				and i.item_type = 55
				and root.deleted_in_graph is null
				and i.deleted_in_graph is null
			where root.item_name = '$job'
			  and sn.text not like 'Final Assembly - 1/%'
			  and sn.category in (1, 1001)";	
	$rsNotes = oci_parse($conn, $n_sql);
	oci_execute($rsNotes, OCI_DEFAULT);
	while(oci_fetch($rsNotes)){
		if (oci_result($rsNotes, 3) == 'Stackup') {
			$snote_txt .= oci_result($rsNotes, 4)."\n";
		} else {
			$cnote_txt .= oci_result($rsNotes, 4)."\n";
		}
	}
	if ($snote_txt <>'' or $cnote_txt<>'') {
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$l_end.':M'.$l_end);
		$objStyleB=$objPHPExcel->getActiveSheet()->getStyle('A'.$l_end);
		$objPHPExcel->getActiveSheet()->getStyle('A'.($l_end))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$l_end)->getFont()->setSize(8);
		$objStyleB->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getRowDimension($l_end)->setRowHeight(55);
	}
	if ($snote_txt<>'' and $stk_notes == 1) {
		$n_txt = 'Stackup Notes:'."\n".$snote_txt;
	}
	if ($cnote_txt<>'' and $cust_notes ==1) {
		$n_txt = $n_txt."\n".'Customer Notes:'."\n".$cnote_txt;
	}
	$objPHPExcel->getActiveSheet()->setCellValue('A'.($l_end),rtrim($n_txt,"\n") );	
}

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(5);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(5.43);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(8.5);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(5.57);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(7.57);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(7);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(7);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(7.71);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(6.43);

$filename =$job. ".xls";
header('Content-Type: application/vnd.ms-excel');
//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename='.$filename);
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//$objWriter->setPreCalculateFormulas(true);
//$objWriter->setOffice2003Compatibility(true);
$objWriter->save('php://output');

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
	$objStyle->getFont()->setSize(8);
	$objFill = $objStyle->getFill();
	$objFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objFill->getStartColor()->setARGB($color);
}

function set_foil_color ($l,$col=0) {
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
	if($col==1){
	set_fill_color('N',$l,$color);
	}
	if($col==2){
	set_fill_color('N',$l,$color);
	set_fill_color('O',$l,$color);
	}

}

function set_core_color ($l) {
	$color = 'FFFFFF99';
	set_fill_color('A',$l,$color);
	set_fill_color('B',$l,$color);
	set_fill_color('C',$l,$color);
}

function set_pp_color ($l,$col=0) {
	$color = '00CCFFCC';
	set_fill_color('F',$l,$color);
	set_fill_color('G',$l,$color);
	set_fill_color('H',$l,$color);
	set_fill_color('I',$l,$color);
	set_fill_color('J',$l,$color);
	set_fill_color('K',$l,$color);
	set_fill_color('L',$l,$color);
	set_fill_color('M',$l,$color);
	if($col==1){
	set_fill_color('N',$l,$color);
	}
	if($col==2){
	set_fill_color('N',$l,$color);
	set_fill_color('O',$l,$color);
	}

}

function set_row_style ($row,$height = 0) {
	Global $objPHPExcel;
	$objStyle=$objPHPExcel->getActiveSheet()->getStyle('A'.$row);
	$objStyle->getFont()->setSize(8);
	$objStyle=$objPHPExcel->getActiveSheet()->getStyle('B'.$row);
	$objStyle->getFont()->setSize(8);
	$objStyle=$objPHPExcel->getActiveSheet()->getStyle('C'.$row);
	$objStyle->getFont()->setSize(8);
	$objStyle=$objPHPExcel->getActiveSheet()->getStyle('D'.$row);
	$objStyle->getFont()->setSize(8);
	$objStyle=$objPHPExcel->getActiveSheet()->getStyle('E'.$row);
	$objStyle->getFont()->setSize(8);
	$objStyle=$objPHPExcel->getActiveSheet()->getStyle('F'.$row);
	$objStyle->getFont()->setSize(8);
	$objStyle=$objPHPExcel->getActiveSheet()->getStyle('G'.$row);
	$objStyle->getFont()->setSize(8);
	$objStyle=$objPHPExcel->getActiveSheet()->getStyle('H'.$row);
	$objStyle->getFont()->setSize(8);
	$objStyle=$objPHPExcel->getActiveSheet()->getStyle('I'.$row);
	$objStyle->getFont()->setSize(8);
	$objStyle=$objPHPExcel->getActiveSheet()->getStyle('J'.$row);
	$objStyle->getFont()->setSize(8);
	$objStyle=$objPHPExcel->getActiveSheet()->getStyle('K'.$row);
	$objStyle->getFont()->setSize(8);
	$objStyle=$objPHPExcel->getActiveSheet()->getStyle('L'.$row);
	$objStyle->getFont()->setSize(8);
	$objStyle=$objPHPExcel->getActiveSheet()->getStyle('M'.$row);
	$objStyle->getFont()->setSize(8);
	$objStyle=$objPHPExcel->getActiveSheet()->getStyle('N'.$row);
	$objStyle->getFont()->setSize(8);
	if ($height) {
		$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight($height);
	} else {
		$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(8.5);
	}
	
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