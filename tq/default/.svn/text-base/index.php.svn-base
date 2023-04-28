<div class="col1" style="width:150px;margin-top:5px;">
<div class="box">
<div class="box_title"><h3><?php echo getLang('Data Navigate',$lang) ?></h3></div>
<div class="box_1">
<ul class="hot_box">

<li><a <?php if ($_GET['data'] =='TQ'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=tq&job_name=<?php echo $_GET['job_name']; ?>&data=TQ&tq_pn=yes<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('TQ',$lang) ?></a></li>

</ul>
</div>
</div>
</div>
<?php
	if ($_GET['data'] =='TQ'){
	 //echo "<div class='attrtab'><iframe id='frame_content' scrolling='no' frameborder='0' onload='this.height=100' //src='tq/default/tq.php?site=$site&job_name=".$_GET['job_name'];
	 //if ($lang) echo "&lang=$lang";
	 //echo "' width='100%'></iframe></div>";
		echo "<div class='attrtab'>";
		require "tq/default/tq.php";
		echo "</div>";
	}
?>