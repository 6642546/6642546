<?php
	/*
	 * here we will generate a report of panelization info for a range of Genesis revisions in MARS
	 */

	include("./lib/login.php");

	//This function encodes a (mm/dd/YYYY) to (YYYY-mm-dd)
	function encodeDate ($date) {
		$tab = explode ("/", $date);
		//$r = $tab[2]."-".$tab[0]."-".$tab[1];
		$r = $tab[0]."-".$tab[1]."-".$tab[2];
		return $r;
	}

	//This function decodes a  to (YYYY-mm-dd mm:hh:ss) to (mm/dd/YYYY)
	function decodeDate ($date) {
		$tab = explode (" ", $date);
		$newdate = $tab[0];
		$tab = explode ("-", $newdate);
		$r = $tab[1]."-".$tab[2]."-".$tab[0];
		return $r;
	}





	//This function encodes a coupon step name to a coupon category
	function couponCat ($coupon) {
		$couponType = 'other';
		$cats = array();
		$cats['ipcb'] = "/^d[pv][0-9]+.[0-9]+-[0-9]+-r[0-9.]+[-]*[0-9]*/";
		$cats['ist'] = "/^ist[0-9]+.*/";
		$cats['mvbeep'] = "/^mvbp.*/";
		$cats['mvplate'] = "/^mvpl-.*/";
		$cats['mvsetup'] = "/^setup.*/";
		$cats['tdr'] = "/^tdr[0-9]+/";
		$cats['backdrill'] = "/^backdrill.*/";
		$cats['pluritec'] = "/^pluritec.*/";
		$cats['ipc2221'] = "/^ipc_.+/";
		$cats['class3'] = "/^class3.*/";
		foreach ($cats as $name=>$patt){
			if (preg_match($patt, $coupon)) {
				$couponType = $name;
				break;
			}
		}
		return $couponType;
	}

	// run report by dates or by job?
	$byJob = 0;
	if($_GET['job'] !="") {
		$byJob = 1;
		$job_name = $_GET['job'];
	
	} else {
		$startDate = encodeDate($_GET['startDate']);
		$endDate = encodeDate($_GET['endDate']);
	}

	//echo $startDate . " --- " . $endDate;
	//exit;

	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=panel_utilization.xls");
	header("Pragma: no-cache");
	header("Expires: 0");

	echo "<table width=\"100%\" border=\"1\">\n";
	echo "<tr>\n";
	echo "<td colspan=\"3\" align=\"left\" style=\"max-width:50px;\"><b>Genesis Panel Utilization Report (FTT)</b></td>\n";
	echo "</tr>\n";
	if ($byJob == 0) {
		echo "<tr>\n";
		echo "<td colspan=\"3\" align=\"left\" ><b>$startDate to $endDate</b></td>\n";
		echo "</tr>\n";
	}
	echo "<tr>\n";
	echo "<td colspan=\"3\" align=\"center\" style=\"max-width:50px;\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align=\"center\" style=\"max-width:50px;\"><b>Part</b></td>\n";
	echo "<td align=\"center\" style=\"max-width:50px;\"><b>Date</b></td>\n";
	echo "<td align=\"center\" style=\"max-width:30px;\"><b>Layers</b></td>\n";
	echo "<td align=\"center\" style=\"max-width:50px;\"><b>Panel Size</b></td>\n";
	echo "<td align=\"center\" style=\"max-width:50px;\"><b>Array Size</b></td>\n";
	echo "<td align=\"center\" style=\"max-width:50px;\"><b>Image Size</b></td>\n";
	echo "<td align=\"center\" style=\"max-width:30px;\"><b>Array Qty</b></td>\n";
	echo "<td align=\"center\" style=\"max-width:30px;\"><b>Image Qty</b></td>\n";
	echo "<td align=\"center\" style=\"max-width:30px;\"><b>IPCB</b></td>\n";
	echo "<td align=\"center\" style=\"max-width:30px;\"><b>IST</b></td>\n";
	echo "<td align=\"center\" style=\"max-width:30px;\"><b>TDR</b></td>\n";
	echo "<td align=\"center\" style=\"max-width:30px;\"><b>IPC2221</b></td>\n";
	echo "<td align=\"center\" style=\"max-width:30px;\"><b>Pluritec</b></td>\n";
	echo "<td align=\"center\" style=\"max-width:30px;\"><b>MVPlate</b></td>\n";
	echo "<td align=\"center\" style=\"max-width:30px;\"><b>MVBeep</b></td>\n";
	echo "<td align=\"center\" style=\"max-width:30px;\"><b>MVSetup</b></td>\n";
	echo "<td align=\"center\" style=\"max-width:30px;\"><b>Backdrill</b></td>\n";
	echo "<td align=\"center\" style=\"max-width:30px;\"><b>Class3</b></td>\n";
	echo "<td align=\"center\" style=\"max-width:30px;\"><b>Other</b></td>\n";
	echo "<td align=\"center\" style=\"max-width:50px;\"><b>Array Area</b></td>\n";
	echo "<td align=\"center\" style=\"max-width:50px;\"><b>Image Area</b></td>\n";
	echo "<td align=\"center\" style=\"max-width:50px;\"><b>Coup Area</b></td>\n";
	echo "<td align=\"center\" style=\"max-width:50px;\"><b>Utilization</b></td>\n";
	echo "<td align=\"center\" style=\"max-width:50px;\"><b>Utilization with Coupons</b></td>\n";
	echo "</tr>\n";




	$db = new MarsConnect();
	$con = $db->connect();
	if ($byJob == 0) {
		$query = "SELECT drawer.name, panel.copper_layers, panel.size, panel.array_quant, panel.image_quant, panel.array_area, panel.image_area,";
		$query .= " panel.coupon_area, panel.panel_util, panel.panel_util_coupon, rev.cre_date, rev.id FROM drawer, folder, rev, panel";
		$query .= " WHERE drawer.siteid = 1 AND rev.id_folder = folder.id AND folder.id_drawer = drawer.id AND folder.name = 'Genesis_Job'";
		$query .= " AND panel.id_rev = rev.id AND folder.cre_date >= rev.cre_date AND rev.cre_date BETWEEN "."'".$startDate."'"." AND "."'".$endDate."' ORDER BY rev.cre_date";
	} else {

		$query = "SELECT drawer.name, panel.copper_layers, panel.size, panel.array_quant, panel.image_quant, panel.array_area, panel.image_area,";
		$query .= " panel.coupon_area, panel.panel_util, panel.panel_util_coupon, rev.cre_date, rev.id FROM drawer, folder, rev, panel";
		$query .= " WHERE drawer.siteid = 1 AND rev.id_folder = folder.id AND folder.id_drawer = drawer.id AND folder.name = 'Genesis_Job'";
		$query .= " AND panel.id_rev = rev.id AND folder.cre_date >= rev.cre_date AND drawer.name = "."'".$job_name."'";
	}

	//echo $query;
	$p_results = $con->query($query);
	//echo $p_results->num_rows;
	while( $p_row = $p_results->fetch_assoc()) {
		//skip jobs that are not actual part numbers
		if (!preg_match("/^81[0-9]+$/", $p_row['name'])) {
			continue;
		}
		$archived = decodeDate($p_row['cre_date']);


		$jobNum = $p_row['name'];
		$numLayers = $p_row['copper_layers'];
		$panelSize = $p_row['size'];
		$arrayQty = $p_row['array_quant'];
		$imageQty = $p_row['image_quant'];
		$arrayArea = $p_row['array_area'];
		if (strval($arrayArea) == '0') {
			$arrayArea = "";
		}
		$imageArea = $p_row['image_area'];
		if (strval($imageArea) == '0') {
			$imageArea = "";
		}
		$couponArea = $p_row['coupon_area'];
		$panelUtil = $p_row['panel_util'];
		$panelUtilCpn = $p_row['panel_util_coupon'];
		$revId = $p_row['id'];

		// now get coupon info from tables
		$cpnDict = array("ipcb"=>0,"ist"=>0,"tdr"=>0,"mvplate"=>0,"mvbeep"=>0,"mvsetup"=>0,"ipc2221"=>0,"pluritec"=>0,"backdrill"=>0,"class3"=>0,"other"=>0);
		$query = "SELECT name, qty_panel FROM coupon WHERE id_rev ="." ".$revId;
		$c_results = $con->query($query);
		while( $c_row = $c_results->fetch_assoc()) {
			$cpn = $c_row['name'];
			$cpnqty = $c_row['qty_panel'];
			$cpnCat = couponCat($cpn);
			$cpnDict[$cpnCat] += $cpnqty;
		}
		
		$ipcb = $cpnDict['ipcb'];
		$ist = $cpnDict['ist'];
		$tdr = $cpnDict['tdr'];
		$mvplate = $cpnDict['mvplate'];
		$mvbeep = $cpnDict['mvbeep'];
		$mvsetup = $cpnDict['mvsetup'];
		$ipc2221 = $cpnDict['ipc2221'];
		$pluritec = $cpnDict['pluritec'];
		$backdrill = $cpnDict['backdrill'];
		$class3 = $cpnDict['class3'];
		$other = $cpnDict['other'];		

		// now get step sizes for edit and array from tables
		$query = "SELECT xsize, ysize FROM step WHERE id_rev ="." ".$revId." "."AND name = 'edit'";
		$edit_results = $con->query($query);
		if ($edit_results->num_rows != 1) {
			$imageSize = "";
		} else {
			$imageRow = $edit_results->fetch_assoc();
			$imageSize = sprintf("%.3f x %.3f", $imageRow['xsize'], $imageRow['ysize']);
		}

		$query = "SELECT xsize, ysize FROM step WHERE id_rev ="." ".$revId." "."AND name = 'multipak'";
		$array_results = $con->query($query);
		if ($array_results->num_rows < 1) {
			$query = "SELECT xsize, ysize FROM step WHERE id_rev ="." ".$revId." "."AND name = 'array'";
			$array_results = $con->query($query);
		}
		if ($array_results->num_rows != 1) {
			$arraySize = "";
		} else {
			$arrayRow = $array_results->fetch_assoc();
			$arraySize = sprintf("%.3f x %.3f", $arrayRow['xsize'], $arrayRow['ysize']);
		}


		echo "<tr>\n";
		echo "<td align=\"center\" style=\"max-width:50px;\">$jobNum</td>\n";
		echo "<td align=\"center\" style=\"max-width:50px;\">$archived</td>\n";
		echo "<td align=\"center\" style=\"max-width:30px;\">$numLayers</td>\n";
		echo "<td align=\"center\" style=\"max-width:50px;\">$panelSize</td>\n";
		echo "<td align=\"center\" style=\"max-width:50px;\">$arraySize</td>\n";
		echo "<td align=\"center\" style=\"max-width:50px;\">$imageSize</td>\n";
		echo "<td align=\"center\" style=\"max-width:30px;\">$arrayQty</td>\n";
		echo "<td align=\"center\" style=\"max-width:30px;\">$imageQty</td>\n";
		echo "<td align=\"center\" style=\"max-width:30px;\">$ipcb</td>\n";
		echo "<td align=\"center\" style=\"max-width:30px;\">$ist</td>\n";
		echo "<td align=\"center\" style=\"max-width:30px;\">$tdr</td>\n";
		echo "<td align=\"center\" style=\"max-width:30px;\">$ipc2221</td>\n";
		echo "<td align=\"center\" style=\"max-width:30px;\">$pluritec</td>\n";
		echo "<td align=\"center\" style=\"max-width:30px;\">$mvplate</td>\n";
		echo "<td align=\"center\" style=\"max-width:30px;\">$mvbeep</td>\n";
		echo "<td align=\"center\" style=\"max-width:30px;\">$mvsetup</td>\n";
		echo "<td align=\"center\" style=\"max-width:30px;\">$backdrill</td>\n";
		echo "<td align=\"center\" style=\"max-width:30px;\">$class3</td>\n";
		echo "<td align=\"center\" style=\"max-width:30px;\">$other</td>\n";
		echo "<td align=\"center\" style=\"max-width:50px;\">$arrayArea</td>\n";
		echo "<td align=\"center\" style=\"max-width:50px;\">$imageArea</td>\n";
		echo "<td align=\"center\" style=\"max-width:50px;\">$couponArea</td>\n";
		echo "<td align=\"center\" style=\"max-width:50px;\">$panelUtil</td>\n";
		echo "<td align=\"center\" style=\"max-width:50px;\">$panelUtilCpn</td>\n";
		echo "</tr>\n";
		
	}

	echo "</table>\n"; 
?>

