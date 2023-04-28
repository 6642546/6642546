<html>
<?php 
	if (!$site) $site = $_GET["site"];
	if (file_exists("oracle_conn.php")){
		$pre_dir_scripts = "scripts";
		$pre_dir = "inplan/allsites/stackup";
		$logo_dir = "images";
		require("oracle_conn.php");
	
	} else {
		$pre_dir_scripts = "../../scripts";
		$pre_dir = "stackup";
		$logo_dir = "stackup";
		require("../../oracle_conn.php");
		echo "<script type='text/javascript' src='". $pre_dir_scripts . "/jquery-1.4.4.min.js'></script>";
	}
	echo "<script type='text/javascript' src='". $pre_dir . "/stackup.js'></script>";
?>



<style>
	<!-- 
		.header {
			width:720px;
			font: 12px/17px times,Arial,sans-serif;
		}
		img_ { 
			-webkit-transform-origin:top left; 
			-webkit-transform:scale(0.5); 
			-moz-transform-origin:top left; 
			-moz-transform:scale(0.5);
			zoom:0.5;
			} 	
			
		tr {
			font-size:12px;
		
		}

		td {
			padding-left:5px;
			
		
		}

		th {
			font-weight:bold;
			text-align:left;
		}

		.drill {
			position: absolute; 
			width:4px;
			left: 0px; top: 0px ;
			display:none;
			z-index:3;
		}
		.div_drill{
			position: absolute; left: 0px; top: 0px ;
			width:100%;
			height:100%;
			z-index:2;
		}

		.div_prepreg{
			position: absolute;
			background-image:url(<?php echo $pre_dir ?>/prepreg/prepreg_back.png);
			width:148px;
			_width:150px;
			height:100%;
			z-index:1;
			border:solid 1px black;
		}

		#drill_imp{
			padding-top:20px;
		}

		#imp_table td{
			border:solid 1px black;
		
		}

		#stackup_table td{
			text-align:left;
		}

		#stackup_table img{
			position: relative;
			z-index:2;
		}

		#drill_thk td {
			text-align:left;
		}

		#imp_table th{
			border:solid 1px black;
		
		}
		
		#setting_table td {
			border:solid 1px black;
			text-align:left;
			
		}

		#setting_table th {
			border:solid 1px black;
		}


		/* loading mask */
		.loadmask {
			z-index: 100;
			position: absolute;
			top:0;
			left:0;
			-moz-opacity: 0.5;
			opacity: .50;
			filter: alpha(opacity=50);
			background-color: #CCC;
			width: 100%;
			height: 100%;
			zoom: 1;
		}
		.loadmask-msg {
			z-index: 20001;
			position: absolute;
			top: 0;
			left: 0;
		   /* border:1px solid #6593cf;*/
			background:#ccc;
		}
		.loadmask-msg div {
			padding:12px 5px 10px 30px;
			background: #fff url('images/loading.gif') no-repeat 5px 12px;
			line-height: 16px;
			border:2px solid #6593CF;
			color:#222;
		   /* font:normal 11px tahoma, arial, helvetica, sans-serif;*/
			cursor:wait;
		}
		.masked {
			overflow: hidden !important;
		}
		.masked-relative {
			position: relative !important;
		}
		.masked-hidden {
			visibility: hidden !important;
		}
			
			-->
</style>
<div style="padding:5px;">
<?php 
//$job = "66602";
//$schema = "us_dev.";
//$name = "report";
//$password = "rpt";
//include_once("dbconnect.php");
$site = $_GET['site'];
$job =  $_GET["job_name"];

$core_rd = $_GET["corerd"];

$pp_fn = $_GET["pp_fn"];
$pp_rs = $_GET["pp_rs"];

$erp = $_GET["erp"];
$tg = $_GET["tg"];
$dk = $_GET["dk"];

if ($erp) $erp_title = "ERP Code";
if ($tg) $tg_title= "TG";
if ($dk) $dk_title= "DK";

$tg_w = 100;
$dk_w = 100;

if ($core_rd) { $tg_w= 30; $dk_w= 30; }
if ($erp) {$erp_w = 60;$tg_w=0; $dk_w=0;}
if ($tg) $tg_w= 30;
if ($dk) $dk_w= 30;


$html2pdf = $_GET['html2pdf'];

$mat_erp = "";
$pp = "";
$pp_dk = "";
$pp_tg = "";
$pp_family = "";
$thick_over_lam = 0;
$add_core_thick = 1;
$add_pp_thick = 1;
$add_foil_thick = 1;
$out_foil_thick = 0;
$loop_index = 0;



$job_query = "select i.item_name
					,job.mrp_name
					,part.name
					,users.user_name
					,job.num_layers
			  from items i,
				job,
				part,
				users
			  where i.item_type=2
			  and i.item_id=job.item_id
			  and job.revision_id=i.last_checked_in_rev
			  and job.item_id=part.item_id
			  and job.revision_id=part.revision_id
			  and job.assigned_operator_id=users.user_id
			  and i.item_name='$job'";

$rsJob= oci_parse($conn, $job_query);
oci_execute($rsJob, OCI_DEFAULT);
oci_fetch($rsJob);
$num_layers = oci_result($rsJob, 5);
echo "<center><div class='header'>
		<div id ='html2pdf' style='display:none;'>$html2pdf</div>
		<div>
		<table id='setting_table' width='100%' style='border-collapse:collapse;border:solid 2px black;'>
			<tr><th colspan=2 style='text-align:center;border-bottom:solid 1px black;'>Report Settings</th></tr>
			<tr><td width='20%'>Laminate Description Field</td><td width='80%'>
				<input type=checkbox id='core_rd' $core_rd><label for=core_rd>Mrp Rev Description</label></input>
			</td></tr>
			<tr><td>Prepreg Description Field</td><td>
				<input type=checkbox id='pp_fn' $pp_fn><label for=pp_fn>Family Name</label></input>
				<input type=checkbox id='pp_rs' $pp_rs><label for=pp_rs>Resin %</label></input>
			</td></tr>
			<tr><td>Common Fields</td><td>
			    <input type=checkbox id='erp' $erp><label for=erp>ERP Code</label></input>
				<input type=checkbox id='tg' $tg><label for=tg>TG</label></input>
				<input type=checkbox id='dk' $dk><label for=dk>DK</label></input>
				<input type=button value='Refresh' id='refresh'></input>
			</td></tr>
		</table>
		</div><br/>
		
		<table width='100%'>
			<tr><td style='background: url($logo_dir/logo.png) no-repeat;height:65px;width:189px;'>&nbsp</td><td colspan=3 style='text-align:left;'><b style='padding-left:60px;'><font size=+2>Stackup Report</font></b></td></tr>
			<tr><td><font size=+0.5>Tooling Number:</font></td><td><font size=+0.5><u id='part_number'>$job</u></font></td><td><font size=+0.5>Customer P/N:</font></td><td><font size=+1><u>".oci_result($rsJob, 3)."</u></font></td></tr>
			<tr><td><font size=+1>Mrp Name:</font></td><td><font size=+0.5><u>".oci_result($rsJob, 2)."</u></font></td><td><font size=+0.5>Contact:</font></td><td><font size=+1><u>".ucwords(strtolower(oci_result($rsJob, 4)))."</u></font></td></tr>

		</table>
	  <br>
	
      <table id='stackup_table' cellpadding=0 colspacing=0 style='border-collapse:collapse'>";
	include("stackup_query.php");
	$rsStk = oci_parse($conn, $sqlStk);
	oci_execute($rsStk, OCI_DEFAULT);
        echo "<tr><th>Layer &nbsp </th><th>Cu <br/> Thick &nbsp <br/> (mils)</th><th>Cu Foil<br/>wt &nbsp  (oz)</th><th></th><th>Description</th><th style='width:$erp_w px;'> &nbsp $erp_title</th><th style='width:$tg_w px;'>&nbsp &nbsp  $tg_title</th><th style='width:$dk_w px;'> &nbsp &nbsp  $dk_title</th></tr>";
	while(oci_fetch($rsStk)){
		if ($loop_index ==0) {
			$out_foil_thick = oci_result($rsStk, 11) * 2;
			$loop_index +=1;
		}
		if(oci_result($rsStk, 4) === 'foil'){
			if (!$erp){
				if ($pp){
					$pp_desc = "Prepreg ". $pp ;
					if ($pp_fn){
						$pp_desc = "Prepreg ".$pp_family." ". $pp ;
					}
					
					 echo "<tr><td>&nbsp </td><td> &nbsp </td><td>&nbsp </td><td><div class='prepreg_bg'></div></td><td style='background-color:#CEFFCE;'>" .$pp_desc . "</td><td style='background-color:#CEFFCE;'></td><td style='background-color:#CEFFCE;'>&nbsp  $pp_tg</td><td style='background-color:#CEFFCE;'>&nbsp  $pp_dk</td></tr>";
					 $pp = "";
				}
			}

			//eval foil description here:
			$foil_desc = oci_result($rsStk, 3);
			if ($core_rd){
				$foil_desc = oci_result($rsStk, 3);
			} else {
				$foil_desc = oci_result($rsStk, 15);
			}

			if ($erp) $mat_erp = oci_result($rsStk, 2);
			if (oci_result($rsStk, 6) !=1 and oci_result($rsStk, 6) !=$num_layers) {
				$segType = "$pre_dir/foil/" . oci_result($rsStk, 9) . '_' . oci_result($rsStk, 12) . ".GIF";
			} else {
				$segType = "$pre_dir/foil/" . oci_result($rsStk, 9) . "_msk_" . oci_result($rsStk, 12) . ".GIF";
			}
			
                        $tmpTop = oci_result($rsStk, 6);
                        $tmpCuTop = sprintf("%.2f",   oci_result($rsStk, 10));
                        echo "<tr><td style='background-color:#FFFFB9;' id='layer_$tmpTop'>$tmpTop</td><td style='background-color:#FFFFB9;'><center>".sprintf("%.2f",oci_result($rsStk, 11))."</center></td><td style='background-color:#FFFFB9;'><center>$tmpCuTop oz</center></td><td><img id=img_$tmpTop src='$segType' /></td><td style='background-color:#FFFFB9;'> " . $foil_desc . "</td><td style='background-color:#FFFFB9;'> " . $mat_erp . "</td><td style='background-color:#FFFFB9;'></td><td style='background-color:#FFFFB9;'></td></tr>";
			if ($add_foil_thick ==1 ) {
				$thick_over_lam +=oci_result($rsStk, 11);
				$add_foil_thick = 0;
			}
			$add_core_thick = 1;
			$add_pp_thick = 1;
		}
		elseif(oci_result($rsStk, 4) === 'core'){
			if ($add_core_thick ==1 ) {
				$thick_over_lam +=oci_result($rsStk, 7);
				$add_core_thick = 0;
			} 
			$add_pp_thick = 1;
			$add_foil_thick = 1;

			if ($tg) $core_tg = oci_result($rsStk, 18);
			if ($dk) $core_dk = oci_result($rsStk, 19);
			if ($erp) $mat_erp = trim(oci_result($rsStk, 2));
			if (!$erp){
				if ($pp){
					$pp_desc = "Prepreg ". $pp ;
					if ($pp_fn){
						$pp_desc = "Prepreg ".oci_result($rsStk, 17)." ". $pp ;
					}
					
					 echo "<tr><td>&nbsp </td><td> &nbsp </td><td>&nbsp </td><td><div class='prepreg_bg'> &nbsp </div></td><td style='background-color:#CEFFCE;'>" .$pp_desc . "</td><td style='background-color:#CEFFCE;'>" .$mat_erp . "</td><td style='background-color:#CEFFCE;'>&nbsp  $pp_tg</td><td style='background-color:#CEFFCE;'>&nbsp  $pp_dk</td></tr>";
					 $pp = "";
				}
			}

			//eval core description here:
			$core_desc = oci_result($rsStk, 3);
			if ($core_rd){
				$core_desc = oci_result($rsStk, 3);
			} else {
				$core_desc = oci_result($rsStk, 15);
			}




                        $tmpTop = " ";
                        $tmpBot = " ";
                        $tmpCuTop = " ";
                        $tmpCuBot = " ";
						$tmptoptoal = " ";
						$tmpbottoal = " ";
			if(oci_result($rsStk, 5)=== "Both"){
				$tmpdescription = oci_result($rsStk, 9);
                                $tmpTop = oci_result($rsStk, 6);
                                $tmpCuTop = sprintf("%.2f",oci_result($rsStk, 10))." oz";
								$tmptoptoal = sprintf("%.2f",oci_result($rsStk, 11));
								$thick_over_lam +=oci_result($rsStk, 11);
				if(oci_fetch($rsStk)){
					$segType = "$pre_dir/core/" . $tmpdescription . "_" . oci_result($rsStk, 9) . "_core.GIF";
                                        $tmpBot = oci_result($rsStk, 6);
                                        $tmpCuBot = sprintf("%.2f",oci_result($rsStk, 10))." oz";
										$tmpbottoal = sprintf("%.2f",oci_result($rsStk, 11));
										$thick_over_lam +=oci_result($rsStk, 11);
				}
			}
			elseif(oci_result($rsStk, 5)=== "Top"){
				$segType = "$pre_dir/core/" . oci_result($rsStk, 9) . "_" . oci_result($rsStk, 12) . "_core.GIF";
                                $tmpTop = oci_result($rsStk, 6);
                                $tmpCuTop = sprintf("%.2f",oci_result($rsStk, 10))." oz";
								$tmptoptoal = sprintf("%.2f",oci_result($rsStk, 11));
								$thick_over_lam +=oci_result($rsStk, 11);
								$tmpBot =" &nbsp";
			}
			elseif(oci_result($rsStk, 5)=== "Bottom"){
				$segType = "$pre_dir/core/" . oci_result($rsStk, 9) . "_" . oci_result($rsStk, 12) . "_core.GIF";
                                $tmpBot = oci_result($rsStk, 6);
                                $tmpCuBot = sprintf("%.2f",oci_result($rsStk, 10))." oz";
								$tmpbottoal = sprintf("%.2f",oci_result($rsStk, 11));
								$thick_over_lam +=oci_result($rsStk, 11);
								$tmpTop = " &nbsp";
			}
			else{
				$segType = "$pre_dir/core/core.GIF";
				$tmpBot =" &nbsp";
                $tmpCuBot = " &nbsp";
				$tmpCuTop=" &nbsp";
				$tmptoal = " &nbsp";
				$tmpTop = " &nbsp";
			}
                        echo "<tr><td style='background-color:#FFFFB9;' id='layer_$tmpTop'>$tmpTop</td><td style='background-color:#FFFFB9;'><center>".$tmptoptoal."</center></td><td style='background-color:#FFFFB9;'><center>$tmpCuTop</center></td><td rowspan=3><img id='img_$tmpTop' src='$segType' /></td><td></td><td> </td><td> </td><td></td></tr>
                              <tr><td> </td><td> </td><td> </td><td style='background-color:#CEFFCE;'>" . $core_desc . "</td><td style='background-color:#CEFFCE;'>$mat_erp</td><td style='background-color:#CEFFCE;'>&nbsp $core_tg</td><td style='background-color:#CEFFCE;'>&nbsp $core_dk</td></tr>
                              <tr><td style='background-color:#FFFFB9;' id='layer_$tmpBot'>$tmpBot</td><td style='background-color:#FFFFB9;'><center>".$tmpbottoal."</center></td><td style='background-color:#FFFFB9;'><center>$tmpCuBot</center></td><td> </td><td></td><td></td><td></td></tr>
                              ";
		}
		else{
			
			
			if ($pp){
				if ($pp_rs){
					$pp .= "/" . oci_result($rsStk, 15)."(".oci_result($rsStk, 16).")";
				} else 	$pp .= "/" . oci_result($rsStk, 15);
			}  else {
				if ($pp_rs){
					$pp = oci_result($rsStk, 15)."(".oci_result($rsStk, 16).")";
				} else {
					$pp = oci_result($rsStk, 15);
				}
			
			}

			if ($add_pp_thick ==1 ) {
				$thick_over_lam +=oci_result($rsStk, 8);
				$add_pp_thick = 0;
			} 
			
			$add_core_thick = 1;
			$add_foil_thick = 1;

			$pp_family = oci_result($rsStk, 17);
			if ($tg) { 
				$pp_tg = oci_result($rsStk, 18);
			 } else {$pp_tg = '';}
			if ($dk) { 
				$pp_dk = oci_result($rsStk, 19);
			} else {$pp_dk = '';}

			if ($erp){
				$mat_erp = oci_result($rsStk, 2);
				echo "<tr><td>&nbsp </td><td> &nbsp </td><td>&nbsp </td><td></td><td style='background-color:#CEFFCE;'>" .oci_result($rsStk, 3) . "</td><td style='background-color:#CEFFCE;'>" .$mat_erp . "</td><td style='background-color:#CEFFCE;'>&nbsp  $pp_tg</td><td style='background-color:#CEFFCE;'>&nbsp  $pp_dk</td></tr>";
			}
		}
	}

	echo '</table>';

	// get drill information:
	$sql = "select i.item_name
			,DP.START_INDEX
			,DP.END_INDEX
			,(select ev.value from enum_values ev,enum_types et where ev.enum_type=et.enum_type
                and et.type_name='PLATE_TYPE' and EV.ENUM=DP.PLATE_TYPE ) DRILL_TYPE
			,DPD.EPOXY_FILL_
			,i.root_id
		from items i
			,items idp
			,drill_program dp
			,drill_program_da dpd
		where i.item_type=2
		and idp.item_type=5
		and i.root_id=idp.root_id
		and idp.item_id=dp.item_id
		and dp.revision_id=idp.last_checked_in_rev
		and IDP.DELETED_IN_GRAPH is null
		and dp.item_id=dpd.item_id
        and dp.revision_id=dpd.revision_id
		and i.item_name='$job'";
		$rsDrill = oci_parse($conn, $sql);
		oci_execute($rsDrill, OCI_DEFAULT);
		//echo '<div class="div_drill">';
		$drill_index = 1;
		
		$drill_text = "";
			while(oci_fetch($rsDrill)){
				$r_id=oci_result($rsDrill, 6);
				echo "<img id='drill_$drill_index' class='drill' src='$pre_dir/drill.png' start_layer='".oci_result($rsDrill, 2)."' end_layer='".oci_result($rsDrill, 3)."'/>";
				$drill_index+=1;
				if (oci_result($rsDrill, 5) == 1000) {$viafill="--";} else {$viafill="Yes";}
				$drill_text .="<tr><td>".oci_result($rsDrill, 2) ." - ".oci_result($rsDrill, 3)."</td><td>".oci_result($rsDrill, 4)."</td><td>".$viafill."</td></tr>";
			}
		//echo '</div>';
?>

<div class="div_prepreg"></div>
<div id="drill_imp">
<table id="drill_thk" width='100%'>
	<tr><td width='60%'>
		<table width='100%'><tr>
			<th>Layers</th><th>Drill Type</th><th>Via Fill</th></tr>
				<?php
					echo $drill_text;
				?>
			</table>
	</td><td width='40%'>
		<table width='100%'>
		<tr><td><?php echo sprintf("%.2f",$thick_over_lam - $out_foil_thick) ?></td><td>Thickness over Laminate</td></tr>
		<tr><td><?php echo sprintf("%.2f",$thick_over_lam) ?></td><td>Thickness over Copper</td></tr>
		<tr><td><?php echo sprintf("%.2f",$thick_over_lam + 1) ?></td><td>Thickness over Soldermask</td></tr>
		</table>
	</td></tr>
</table>

<div>
<!--<table id="imp_table" border=1 width='100%' style='border-collapse:collapse;border:solid 2px black;'>
<tr><th style="text-align:center;border-bottom:solid 2px black;" colspan=11><font size=+1><Strong>Impedance Table</Strong></font></th></tr>
<tr><th>Layer</th><th>Structure Type</th><th>Coated<br/>Microstrip</th><th>Target<br/>Impedance<br/>(ohms)</th><th>Impedance<br/>Tolerance<br/>(ohms)</th>
<th>Target<br/>Linewidth<br/>(mils)</th><th>Edge<br/>Coupled<br/>Pitch *<br/>(mils)</th><th>Reference<br/>Layers</th><th>Modelled<br/>Linewidth<br/> (mils)</th><th>Modelled <br/>Impedance<br/>(ohms)</th><th>CoPlaner<br/>Space<br/>(mils)</th></tr>

<?php
	/*include("impedance_query.php");
	$rsImp = oci_parse($conn, $imp_query);
	oci_execute($rsImp, OCI_DEFAULT);
	while(oci_fetch($rsImp)){
		$ref_layer = "";
		if (oci_result($rsImp, 10) && oci_result($rsImp, 11)){
			$ref_layer = "(".oci_result($rsImp, 10).", ".oci_result($rsImp, 11).")";
		} else if (oci_result($rsImp, 10)){
			$ref_layer = "(".oci_result($rsImp, 10).")";
		} else if (oci_result($rsImp, 11)){
			$ref_layer = "(".oci_result($rsImp, 11).")";
		}

		$module = ucwords(strtolower(str_ireplace('_',' ',oci_result($rsImp, 2))));

		$coated = "---";
		if ((oci_result($rsImp, 3) == 1 || oci_result($rsImp, 3) == oci_result($rsImp, 12))  && strpos(oci_result($rsImp, 2),'uncoated')==false ) $coated ="Yes";

		$pitch = sprintf("%.2f",oci_result($rsImp, 13));

		$coplanar_space = sprintf("%.2f",oci_result($rsImp, 14));
		echo "<tr><td  align=center>".oci_result($rsImp, 3)."</td><td>".$module."</td><td align=center>".$coated."</td><td align=center>".sprintf("%.2f",oci_result($rsImp, 4))."</td><td  align=center>"."+/-".oci_result($rsImp, 5)."</td><td align=center>".sprintf("%.2f",oci_result($rsImp, 7))."</td><td align=center>$pitch</td><td align=center>".$ref_layer."</td><td  align=center>".sprintf("%.2f",oci_result($rsImp, 9))."</td><td  align=center>".sprintf("%.2f",oci_result($rsImp, 8))."</td><td  align=center>$coplanar_space</td></tr>";

	}
	if (!$ref_layer) {
		echo "<tr><td colspan=11 align=center style='color:red;'>No impedance information for this job.</td></tr>";
	}*/
?>
</table>-->
<?php
//阻抗
$ic_para="select  ic.artwork_trace_width
       , ic.model_name
       , (select copp.layer_index from public_items rr , copper_layer copp where rr.item_id= ic.trace_layer_item_id and rr.item_id= copp.item_id and rr.revision_id= copp.revision_id  ) as layer_index
       , (select copp.layer_index from public_items rr , copper_layer copp where rr.item_id= ic.top_model_layer_item_id and rr.item_id= copp.item_id and rr.revision_id= copp.revision_id  ) as top_layer_index
       , (select copp.layer_index from public_items rr , copper_layer copp where rr.item_id= ic.bottom_model_layer_item_id and rr.item_id= copp.item_id and rr.revision_id= copp.revision_id  ) as bot_layer_index
       , ic.original_trace_width
       , ic.orig_trace_width_tol_plus
       ,ic.orig_trace_width_tol_minus
       , ic.calculated_trace_width
	   , ic.MFG_LINE_WIDTH_TOL_PLUS
	   ,ic.MFG_LINE_WIDTH_TOL_MINUS
        ,ic.artwork_trace_width
       , ic.customer_required_impedance
       , ic.cust_required_imped_tol_plus
	   ,ic.CUST_REQUIRED_IMPED_TOL_MINUS
       ,ic.impedance_comment
       ,(select icp.double_value from impedance_constraint_parameter icp 
       where icp.name='original_S' and icp.item_id=ic.item_id 
       and icp.revision_id=ic.revision_id 
       and icp.constraint_sequential_index=ic.sequential_index) as value1
from items root , impedance_constraint ic 
where item_type=9
      and root.item_id= ic.item_id
      and  root.last_checked_in_rev= ic.revision_id
      and root.root_id=$r_id
  order by ic.trace_layer_item_id ,ic.sequential_index";
	$ic_t='<table id="imp_table" border=1 width="100%" style="border-collapse:collapse;border:solid 2px black;">
	  <tr><th><b><center>阻抗模块</center></b></th><th><b><center>层号</center></b></th><th><b><center>阻抗类型</center></b></th><th><b><center>参考层</center></b></th><th><b><center>CAD线宽/线隙</center></b></th><th><b><center>成品线宽</center></b></th><th><b><center>生产菲林</center></b></th><th><b><center>阻抗值</center></b></th><th><b><center>阻抗值</center></b></th></tr>';
   $has_ic=0;
   $ic_t.='<tr>
		<td> <center>(IMP Module)</center></td>
		<td><center>(Layer)</center></td>
		<td><center>单线/差分</center></td>
		<td><center>(Ref. Layer)</center></td>
		<td><center>(Mil)</center></td>
		<td><center>(Mil)</center></td>
		<td><center>(Mil)</center></td>
		<td><center><div>(成品 Finished Goods)</div><div>OHMS</div></center></td>
		<td><center><div>(蚀刻后 After Etch)</div><div>OHMS</div></center></td>
	    </tr>';
		$ic= oci_parse($conn, $ic_para);
        oci_execute($ic, OCI_DEFAULT);
       while ($row = oci_fetch_array($ic, OCI_RETURN_NULLS)){	     
          $mode=$row['MODEL_NAME'];
		  $ic_pic=' <img  src="images/SI-8000/small_' .$mode .'.png "/>';
		  
		  $layer_id="L".number_format($row['LAYER_INDEX'],0);
		  if ($row['VALUE1']) $ic_type='差分';
          else if ( $row['MODEL_NAME']==="broadside_coupled_stripline_2s") $ic_type='异面差分';
		  else $ic_type='单线';
	   if ( $row['MODEL_NAME']=== "embedded_microstrip_1b1a" or $row['MODEL_NAME']=== "edge_coupled_embedded_microstrip_1b1a" or $row['MODEL_NAME']=== "embedded_microstrip_2b1a"
	   or $row['MODEL_NAME']=== "embedded_microstrip_1b2a"  or $row['MODEL_NAME']=== "embedded_microstrip_1e1b2a") $ref_layer="L".number_format($row['BOT_LAYER_INDEX'],0);
	   else{
		   if (is_null($row['TOP_LAYER_INDEX']))
			   $ref_layer="L".number_format($row['BOT_LAYER_INDEX'],0);
		   else
              $ref_layer="L".number_format($row['BOT_LAYER_INDEX'],0)."/".$ref_layer="L".number_format($row['TOP_LAYER_INDEX'],0);
	   }
	   if($row['VALUE1'])
		   $space=number_format($row['ORIGINAL_TRACE_WIDTH'],2)."/".number_format($row['VALUE1'],1);
	   else
		   $space=number_format($row['ORIGINAL_TRACE_WIDTH'],2);

	       $finish_line_width=number_format($row['CALCULATED_TRACE_WIDTH'],2)."+".number_format($row['MFG_LINE_WIDTH_TOL_PLUS'],2)."/-".number_format($row['MFG_LINE_WIDTH_TOL_MINUS'],2);
		   $art_work=number_format($row['ARTWORK_TRACE_WIDTH'],2);
		   $f_ic=number_format($row['CUSTOMER_REQUIRED_IMPEDANCE'],2)."+".number_format($row['CUST_REQUIRED_IMPED_TOL_PLUS'],2)."/-".number_format($row['CUST_REQUIRED_IMPED_TOL_MINUS'],2);
		   $ic_comm=$row['IMPEDANCE_COMMENT'];
      $ic_t.="
	   <tr>
	 	<td style='word-break:break-all;word-wrap:break-word ' width='80px'>$ic_pic</td>
		<td><center>$layer_id</center></td>
		<td><center>$ic_type</center></td>
		<td><center>$ref_layer</center></td>
		<td><center>$space</center></td>
		<td><center>$finish_line_width</center></td>
		<td><center>$art_work</center></td>
		<td><center>$f_ic</center></td>
		<td><center>$ic_comm</center></td>
	    </tr>";
       $has_ic=1 ;
	  }
	 $bar='<div  class="head_bar">
		<p><center>阻抗控制要求 ( Impedance control required)</center></p>
	    </div>';
	//echo $r_id;
	if ($has_ic===1){
	 echo $bar;
     $ic_t.="<tr><td colspan='4'><b>阻抗控制备注 (Impedance control Remark):</b></td><td colspan='5'>$CI_IMP_REMARK_</td></tr>";
	 echo $ic_t.'</table>';
	}
?>
<p style="font-size:12px;">* &nbsp Edge Coupled Pitch is measured from the center line of one differential trace to the center line of the other.</p>
</div>
</div>
</div></center>
</div>

</html>