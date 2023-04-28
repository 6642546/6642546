<div class="col1" style="width:150px;margin-top:5px;">
<div class="box">
<div class="box_title"><h3><?php echo getLang('Data Navigate',$lang) ?></h3></div>
<div class="box_1">
<ul class="hot_box">

<li><a <?php if ($_GET['data'] =='Job Attributes'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=inplan&job_name=<?php echo $_GET['job_name']; ?>&data=Job Attributes<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('Job Attributes',$lang) ?></a></li>
<li><a <?php if ($_GET['data'] =='Traveller Routing'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=inplan&job_name=<?php echo $_GET['job_name']; ?>&data=Traveller Routing<?php if ($lang) echo "&lang=$lang"; if($_GET['process']){ echo "&process=".$_GET['process'];}?>"><?php echo getLang('Traveller Routing',$lang) ?></a></li>
<li><a <?php if ($_GET['data'] =='Stackup'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=inplan&job_name=<?php echo $_GET['job_name']; ?>&data=Stackup<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('Stackup',$lang) ?></a></li>
<li><a <?php if ($_GET['data'] =='Drill Table'){echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=inplan&job_name=<?php echo $_GET['job_name']; ?>&data=Drill Table<?php if ($lang){ echo "&lang=$lang";}if($_GET['process']){ echo "&process=".$_GET['process'];} ?>"><?php echo getLang('Drill Table',$lang) ?></a></li>
<li><a <?php if ($_GET['data'] =='Tool Table'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=inplan&job_name=<?php echo $_GET['job_name']; ?>&data=Tool Table<?php if ($lang) echo "&lang=$lang";if($_GET['process']){ echo "&process=".$_GET['process'];}?>"><?php echo getLang('Tool Table',$lang) ?></a></li>
<li><a <?php if ($_GET['data'] =='Cam Instruction'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=inplan&job_name=<?php echo $_GET['job_name']; ?>&data=Cam Instruction<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('Cam Instruction',$lang) ?></a></li>
<li><a <?php if ($_GET['data'] =='Sheet Cutting'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=inplan&job_name=<?php echo $_GET['job_name']; ?>&data=Sheet Cutting<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('Sheet Cutting',$lang) ?></a></li>
<li><a <?php if ($_GET['data'] =='Job History'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=inplan&job_name=<?php echo $_GET['job_name']; ?>&data=Job History<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('Job History',$lang) ?></a></li>
<li><a <?php if ($_GET['data'] =='TQ'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=tq&job_name=<?php echo $_GET['job_name']; ?>&data=TQ<?php if ($lang) echo "&tq_pn=yes&lang=$lang";?>"><?php echo getLang('TQ',$lang) ?></a></li>
</ul>
</div>
</div>
</div>
<?php
	if ($_GET['data'] =='Job Attributes'){
	 //echo "<div class='attrtab'><iframe id='frame_content' scrolling='no' frameborder='0' onload='this.height=100' //src='inplan/hy/showjobdata.php?site=$site&job_name=".$_GET['job_name'];
	// if ($lang) echo "&lang=$lang";
	// echo "' width='100%'></iframe></div>";
	echo "<div class='attrtab'>";
	require "inplan/hy/showjobdata.php";
	echo "</div>";
	
	} else if ($_GET['data'] =='Traveller Routing'){
	 //echo "<div class='attrtab'><iframe id='frame_content' scrolling='no' frameborder='0' onload='this.height=100' src='inplan/hy/traveler.php?site=$site&job_name=".$_GET['job_name']."&process=".$_GET['process'];
	 //if ($lang) echo "&lang=$lang";
	 //echo "' width='100%'></iframe></div>";
		echo "<div class='attrtab'>";
		require "inplan/hy/traveler.php";
		echo "</div>";
	} else if ($_GET['data'] =='Job History'){
	 //echo "<div class='attrtab'><iframe id='frame_content' scrolling='no' frameborder='0' onload='this.height=100' //src='inplan/hy/showjobhistory.php?site=$site&job_name=".$_GET['job_name'];
	 //if ($lang) echo "&lang=$lang";
	 //echo "' width='100%'></iframe></div>";
		echo "<div class='attrtab'>";
		require "inplan/hy/showjobhistory.php";
		echo "</div>";
	} else if ($_GET['data'] =='Stackup'){
	 //echo "<div class='attrtab'><iframe id='frame_content' scrolling='no' frameborder='0' src='inplan/hy/stackup.php?site=$site&job_name=".$_GET['job_name'];
	 //if ($lang) echo "&lang=$lang";
	 //echo "' width='100%'></iframe></div>";
		echo "<div class='attrtab'>";
		require "inplan/hy/stackup.php";
		echo "</div>";
	} else if ($_GET['data'] =='Drill Table'){
	 //echo "<div class='attrtab'><iframe id='frame_content' scrolling='no' frameborder='0' onload='this.height=100' //src='inplan/hy/drill.php?site=$site&job_name=".$_GET['job_name']."&process=".$_GET['process'];
	 //if ($lang) echo "&lang=$lang";
	 //echo "' width='100%'></iframe></div>";
		echo "<div class='attrtab'>";
		require "inplan/hy/drill.php";
		echo "</div>";
	} else if ($_GET['data'] =='Tool Table'){
	 //echo "<div class='attrtab'><iframe id='frame_content' scrolling='no' frameborder='0' onload='this.height=100' src='inplan/hy/tool.php?site=$site&job_name=".$_GET['job_name']."&process=".$_GET['process'];
	 //if ($lang) echo "&lang=$lang";
	 //echo "' width='100%'></iframe></div>";
		echo "<div class='attrtab'>";
		require "inplan/hy/tool.php";
		echo "</div>";
	} else if ($_GET['data'] =='Cam Instruction'){
	 //echo "<div class='attrtab'><iframe id='frame_content' scrolling='no' frameborder='0' onload='this.height=100' src='inplan/hy/caminstructions.php?site=$site&job_name=".$_GET['job_name'];
	 //if ($lang) echo "&lang=$lang";
	 //echo "' width='100%'></iframe></div>";
		echo "<div class='attrtab'>";
		require "inplan/hy/caminstructions.php";
		echo "</div>";
	} else if ($_GET['data'] =='TQ'){
	 //echo "<div class='attrtab'><iframe id='frame_content' scrolling='no' frameborder='0' onload='this.height=100' //src='inplan/hy/tq.php?site=$site&job_name=".$_GET['job_name'];
	 //if ($lang) echo "&lang=$lang";
	 //echo "' width='100%'></iframe></div>";
		echo "<div class='attrtab'>";
		require "inplan/hy/tq.php";
		echo "</div>";
	} else if ($_GET['data'] =='Sheet Cutting'){
	 //echo "<div class='attrtab'><iframe id='frame_content' scrolling='no' frameborder='0' onload='this.height=100' //src='inplan/hy/sheet_cutting.php?site=$site&job_name=".$_GET['job_name'];
	// if ($lang) echo "&lang=$lang";
	// echo "' width='100%'></iframe></div>";
		echo "<div class='attrtab'>";
		require "inplan/hy/sheet_cutting.php";
		echo "</div>";
	}
?>