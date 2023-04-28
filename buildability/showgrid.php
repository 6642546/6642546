<script type="text/javascript" src="buildability/scripts/showgrid.js"></script>
<div class="grid">
	<div class="box_b">
	<div class="content_textarea_h">
	<div class="box_title"><h4 class ="title_b_" style="margin-left:-15px;">Buildability Dispatch List</h4></div>
	<div class="box_1">
	<ul class="hot_box" style="height:45px">
		<div style="float:left;padding-left:5px;padding-top:12px;">
			Dispatch Group:<input class="easyui-combobox" type="text" id = "dispatch_g" style="width:110px;"/>
			</div>
			<div style="float:left;padding-left:5px;padding-top:12px;">
			<?php echo "Part Number:<input style=\"width:110px;\" type=\"text\" id=\"part_number\" value=".$_GET["keyword"]."></input>"; ?>
			</div>
			<div style="float:left;padding-left:5px;padding-top:5px;">
			Date From:<br/>
			Date To:
			</div>
			<div style="float:left;padding-left:5px;">
			<input type="text" id="datepicker1" class="easyui-datebox"></input><br/>
			<input type="text" id="datepicker2" class="easyui-datebox"></input>
			</div>
			<div style="float:left;padding-left:5px;padding-top:12px;">
			 Status:
			</div>
			<div style="float:left;padding-top:1px;">
			<input type="radio" name="status" checked="true" value="O"/>Open<br/>
			<input type="radio" name="status" value="C"/>Completed
			</div>
			<div style="float:left;padding-left:15px;padding-top:10px;">
				<a id="reset_btn" class="easyui-linkbutton" plain="true" iconCls="icon-back">Reset</a>
			</div>
			<div style="float:left;padding-left:15px;padding-top:10px;">
				<a id="search_btn_b" class="easyui-linkbutton" iconCls="icon-search">Search</a>
			</div>
			
	</ul>
	</div>
	</div>
	</div>
	<div id="buildgrid"></div> 
</div>
  