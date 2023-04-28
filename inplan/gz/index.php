<div class="col1" style="width:150px;margin-top:5px;">
<div class="box">
<div class="box_title"><h3><?php echo getLang('Data Navigate',$lang) ?></h3></div>
<div class="box_1">
<ul class="hot_box">

<li><a <?php if ($_GET['data'] =='Job Attributes'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=inplan&job_name=<?php echo $_GET['job_name']; ?>&data=Job Attributes<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('Job Attributes',$lang) ?></a></li>
<li><a <?php if ($_GET['data'] =='ECN'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=inplan&job_name=<?php echo $_GET['job_name']; ?>&data=ECN<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('ECN',$lang) ?></a></li>
<li><a <?php if ($_GET['data'] =='Traveller Routing'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=inplan&job_name=<?php echo $_GET['job_name']; ?>&data=Traveller Routing<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('Traveller Routing',$lang) ?></a></li>
<li><a <?php if ($_GET['data'] =='Stackup'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=inplan&job_name=<?php echo $_GET['job_name']; ?>&data=Stackup<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('Stackup',$lang) ?></a></li>
<li><a <?php if ($_GET['data'] =='Drill Table'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=inplan&job_name=<?php echo $_GET['job_name']; ?>&data=Drill Table<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('Drill Table',$lang) ?></a></li>
<li><a <?php if ($_GET['data'] =='Cam Instruction'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=inplan&job_name=<?php echo $_GET['job_name']; ?>&data=Cam Instruction<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('Cam Instruction',$lang) ?></a></li>
<li><a <?php if ($_GET['data'] =='Job Rev Compare'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=inplan&job_name=<?php echo $_GET['job_name']; ?>&data=Job Rev Compare<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('Job_Rev_Compare',$lang) ?></a></li>

<li><a <?php if ($_GET['data'] =='Drill Rev Compare'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=inplan&job_name=<?php echo $_GET['job_name']; ?>&data=Drill Rev Compare<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('Drill Rev Compare',$lang) ?></a></li>
<li><a <?php if ($_GET['data'] =='Stackup Rev Compare'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=inplan&job_name=<?php echo $_GET['job_name']; ?>&data=Stackup Rev Compare<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('Stackup Rev  Compare',$lang) ?></a></li>

<li><a <?php if ($_GET['data'] =='Job History'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=inplan&job_name=<?php echo $_GET['job_name']; ?>&data=Job History<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('Job History',$lang) ?></a></li>
<li><a <?php if ($_GET['data'] =='TQ'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=tq&job_name=<?php echo $_GET['job_name']; ?>&data=TQ<?php if ($lang) echo "&tq_pn=yes&lang=$lang";?>"><?php echo getLang('TQ',$lang) ?></a></li>
<li><a <?php if ($_GET['data'] =='X-Section'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=inplan&job_name=<?php echo $_GET['job_name']; ?>&data=X-Section<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('X-Section',$lang) ?></a></li>
<li><a <?php if ($_GET['data'] =='Drawing'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=inplan&job_name=<?php echo $_GET['job_name']; ?>&data=Drawing<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('Drawing',$lang) ?></a></li>

</ul>
</div>
</div>
</div>
<?php
	if ($_GET['data'] =='Job Attributes'){
	 echo "<div class='attrtab'>";
	 require "inplan/gz/showjobdata.php";
	 echo "</div>";
	} else if ($_GET['data'] =='Job History'){
	 echo "<div class='attrtab'>";
		require "inplan/gz/showjobhistory.php";
		echo "</div>";
	} else if ($_GET['data'] =='Stackup'){
	 echo "<div class='attrtab'>";
		require "inplan/gz/stackup.php";
		echo "</div>";
	} else if ($_GET['data'] =='Drill Table'){
		echo "<div class='attrtab'>";
		require "inplan/gz/drill.php";
		echo "</div>";
	} else if ($_GET['data'] =='ECN'){
		echo "<div class='attrtab'>";
		require "inplan/gz/ecn.php";
		echo "</div>";
	} else if ($_GET['data'] =='Traveller Routing'){
		echo "<div class='attrtab'>";
		require "inplan/gz/traveler.php";
		echo "</div>";
	} else if ($_GET['data'] =='Cam Instruction'){
		echo "<div class='attrtab'>";
		require "inplan/gz/caminstructions.php";
		echo "</div>";
	} else if ($_GET['data'] =='TQ'){
	    echo "<div class='attrtab'>";
		require "inplan/gz/tq.php";
		echo "</div>";
	} else if ($_GET['data'] =='Job Rev Compare' ){
	    echo "<div class='attrtab'>";
		require "inplan/gz/Job_Rev_Compare.php";
		echo "</div>";
	} else if ($_GET['data'] =='Drill Rev Compare' ){
	    echo "<div class='attrtab'>";
		require "inplan/gz/Drill_Rev_Compare.php";
		echo "</div>";
	} else if ($_GET['data'] =='Stackup Rev Compare' ){
	     echo "<div class='attrtab'>";
		require "inplan/gz/Stackup_Rev_Compare.php";
		echo "</div>";
	} else if ($_GET['data'] =='X-Section' ){
	  echo "<div class='attrtab'>";
		require "inplan/gz/x_section.php";
		echo "</div>";
	}else if ($_GET['data'] =='Drawing' ){
	  echo "<div class='attrtab'>";
		require "inplan/gz/Drawing.php";
		echo "</div>";
	}
?>