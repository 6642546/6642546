<div class="col1" style="width:150px;margin-top:5px;">
<div class="box">
<div class="box_title"><h3><?php echo getLang('Data Navigate',$lang) ?></h3></div>
<div class="box_1">
<ul class="hot_box">

<li><a <?php if ($_GET['data'] =='Search Job' || $_GET['data']==""){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=genesis&data=Search Job<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('Search Job',$lang) ?></a></li>
<li><a <?php if ($_GET['data'] =='Copper Distribution Reports'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=genesis&data=Copper Distribution Reports<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('Copper Distribution Reports',$lang) ?></a></li>
<li><a <?php if ($_GET['data'] =='Panel Utilization Reports'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=genesis&data=Panel Utilization Reports<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('Panel Utilization Reports',$lang) ?></a></li>
<li><a <?php if ($_GET['data'] =='Layers Report'){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=genesis&data=Layers Report<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('Layers Report',$lang) ?></a></li>
</ul>
</div>
</div>
</div>
<?php
	if ($_GET['data'] =='Search Job' || $_GET['data']==""){
	 require "genesis/us/search.php";
	
	} else if ($_GET['data'] =='Copper Distribution Reports'){
	 echo "<div class='attrtab'><iframe id='frame_content' scrolling='no' frameborder='0' onload='this.height=100' src='genesis/us/genesis_tools/cu_dist.inc.php?site=$site";
	 if ($lang) echo "&lang=$lang";
	 echo "' width='100%'></iframe></div>";
	} else if ($_GET['data'] =='Panel Utilization Reports'){
	 echo "<div class='attrtab'><iframe id='frame_content' scrolling='no' frameborder='0' src='genesis/us/genesis_tools/panel_util.inc.php?site=$site";
	 if ($lang) echo "&lang=$lang";
	 echo "' width='100%'></iframe></div>";
	} else if ($_GET['data'] =='Layers Report'){
	 echo "<div class='attrtab'><iframe id='frame_content' scrolling='no' frameborder='0' onload='this.height=100' src='genesis/us/genesis_tools/layers_rpt.inc.php?site=$site";
	 if ($lang) echo "&lang=$lang";
	 echo "' width='100%'></iframe></div>";
	} 
?>