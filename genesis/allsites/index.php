<div class="col1" style="width:150px;margin-top:5px;">
<div class="box">
<div class="box_title"><h3><?php echo getLang('Data Navigate',$lang) ?></h3></div>
<div class="box_1">
<ul class="hot_box">

<li><a <?php if ($_GET['data'] =='Search Job' || $_GET['data']==""){	echo "class='t_selected'"; }?> href="index.php?site=<?php echo $_GET['site']; ?>&action=genesis&data=Search Job<?php if ($lang) echo "&lang=$lang";?>"><?php echo getLang('Search Job',$lang) ?></a></li>
</ul>
</div>
</div>
</div>
<?php
	if ($_GET['data'] =='Search Job' || $_GET['data']==""){
	 require "genesis/allsites/search.php";
	
	}
?>