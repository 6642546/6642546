<div class="col1" style="width:150px;margin-top:5px;">
<div class="box">
<div class="box_title"><h3><?php echo getLang('Data Navigate',$lang) ?></h3></div>
<div class="box_1">
<ul class="hot_box">

<li><a <?php if ($_GET['data'] =='Job Attributes'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=inplan&job_name=<?php echo $_GET['job_name']; ?>&data=Job Attributes<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('Job Attributes',$lang) ?></a></li>
<li><a <?php if ($_GET['data'] =='Traveller Routing'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=inplan&job_name=<?php echo $_GET['job_name']; ?>&data=Traveller Routing<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('Traveller Routing',$lang) ?></a></li>
<li><a <?php if ($_GET['data'] =='Stackup'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=inplan&job_name=<?php echo $_GET['job_name']; ?>&data=Stackup<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('Stackup',$lang) ?></a></li>
<li><a <?php if ($_GET['data'] =='Drill Table'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=inplan&job_name=<?php echo $_GET['job_name']; ?>&data=Drill Table<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('Drill Table',$lang) ?></a></li>
<li><a <?php if ($_GET['data'] =='Cam Instruction'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=inplan&job_name=<?php echo $_GET['job_name']; ?>&data=Cam Instruction<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('Cam Instruction',$lang) ?></a></li>
<li><a <?php if ($_GET['data'] =='Job History'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=inplan&job_name=<?php echo $_GET['job_name']; ?>&data=Job History<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('Job History',$lang) ?></a></li>
<li><a <?php if ($_GET['data'] =='TQ'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=tq&job_name=<?php echo $_GET['job_name']; ?>&data=TQ<?php if ($lang) echo "&tq_pn=yes&lang=$lang";?>"><?php echo getLang('TQ',$lang) ?></a></li>
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
	}
?>