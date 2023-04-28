<html>
<div style="padding:10px;">
<?php 
	$site = $_GET['site'];
	$job =  $_GET["job_name"];
    echo "<div class='header'><center><b><font size=+2>Drilling Info: $job</font></b></center><br><br>";
?>
    <style>
     .drill_table{
		    width:100%;
			font: 14px/17px times,"Helvetica Neue",Helvetica,Arial,sans-serif;
			border-collapse:collapse;border:solid 2px #99BBDD;
		
		}

		.drill_table th{
			text-align:center;
			border:solid 1px #99BBDD;
		}

		.drill_table td{
			border:solid 1px #99BBDD;
			text-align: center;
		}

		.inplan_attri_hd{
			background:#ADADAD;
			color:white;
			height:25px;
			font-weight:bold;
			padding-left:10px;
			border-bottom:5px solid red;
		}

    </style>  
 <?php
	if (file_exists("oracle_conn.php")){
		$pre_dir_scripts = "scripts";
		$pre_dir = "inplan/allsites";
		$logo_dir = "images";
		$image_dir = ".";
		require("oracle_conn.php");
		require_once("lang.php");
	
	} else {
		$pre_dir_scripts = "../../scripts";
		$pre_dir = ".";
		$image_dir = "../..";
		$logo_dir = "stackup";
		require("../../oracle_conn.php");
		require_once("../../lang.php");
	}
    include("drill_query.php");
    $rsDrill = oci_parse($conn, $sqlDrill);
    oci_execute($rsDrill, OCI_DEFAULT);
    $tmpDrillName = "none";
    echo "<center><table class='drill_table'>";
    while(oci_fetch($rsDrill)){
        $drillName = oci_result($rsDrill, 1);
        $drillTech = oci_result($rsDrill, 2);
        $drillType = oci_result($rsDrill, 3);
        $drillPlate = oci_result($rsDrill, 4);
        $drillPlateThick = oci_result($rsDrill, 5);
        $drillStart = oci_result($rsDrill, 6);
        $drillEnd = oci_result($rsDrill, 7);
        $drillDepth = oci_result($rsDrill, 8);
        $drillAR = oci_result($rsDrill, 9);
        $drillStackCnt = oci_result($rsDrill, 10);

        $holeFinishSize = oci_result($rsDrill, 11);
        $holeTolerance = oci_result($rsDrill, 12);
        $holeType = oci_result($rsDrill, 13);
        $holeCamSize = oci_result($rsDrill, 14);
        $holePCBCnt = oci_result($rsDrill, 15);
        $holePanelCnt = oci_result($rsDrill, 16);

        if($drillName <> $tmpDrillName){
            if($tmpDrillName <> "none"){
                echo "<tr height='20px'><td colspan=6> </td></tr>";
            }
            $tmpAR = round($drillAR * 100)/100;

            echo "<tr height='20px' BGCOLOR='#58ACFA'><th class=inplan_attri_hd  colspan=6>$drillName</th></tr>";
            echo "<tr height='20px'><th>Drill&nbsp;Tech</th><th>Drill&nbsp;Type</th><th title='max_finished_aspect_ratio'>Aspect&nbsp;Ratio</th><th title='panel_stack_count'>Stack&nbsp;Height</th><th>Drill&nbsp;Depth</th><th>Plating&nbsp;(mils)</th></tr>";
            echo "<tr height='20px'><td>$drillTech</td><td>$drillType</td><td>$tmpAR</td><td>$drillStackCnt</td><td>$drillDepth</td><td>$drillPlateThick</td></tr>";
            echo "<tr height='10px'><td colspan=6></td></tr>";
            echo "<tr height='20px'><th>Finish&nbsp;size&nbsp;&nbsp;</th><th>Tolerance</th><th>Hole&nbsp;Type&nbsp;&nbsp;</th><th title='actual_drill_size'>Drill&nbsp;Size&nbsp;&nbsp;</th><th>PCB&nbsp;Count&nbsp;&nbsp;</th><th>Panel&nbsp;Count</th></tr>";
        }
        echo "<tr height='20px'><td>$holeFinishSize</td><td>$holeTolerance</td><td>$holeType</td><td>$holeCamSize</td><td>$holePCBCnt</td><td>$holePanelCnt</td></tr>";
        $tmpDrillName = oci_result($rsDrill, 1);
    }
    echo "</table></center></div>";
?>
</div>
</html>