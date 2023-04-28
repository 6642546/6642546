<script type="text/javascript" src="scripts/search_list.js"></script>
<script type="text/javascript" src="scripts/adv_search/jquery.cookie.js"></script>


<?php if (!empty($_COOKIE['FEEWEB_uName'])) { ?>
<link rel="stylesheet" type="text/css" href="scripts/adv_search/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="scripts/adv_search/themes/icon.css">
<script type="text/javascript" src="scripts/adv_search/jquery.easyui.min.js"></script>
<script type="text/javascript" src="scripts/adv_search/search.js"></script>
<?php  }?>


	<style type="text/css">
	ul,li{margin:0;padding:0;}
	li{list-style-type:none;}
	#timedate{list-style-type:none;}
	#nav ul li{float:left;display:inline;padding:0 5px 0 0;}
	.submit {float:right;}
	.simple_option {float:right}
	.test {height:16px;width:80px;}
	.condspan{padding:0 0 0 10px;}
	#cond1 {padding:0 0 0 10px;}
	#cond2 {padding:0 0 0 10px;display:none;}
	</style>

<?php  echo "<span id ='login_url'  href='./role/login.php?url=". str_ireplace("&","@",'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'])."' ></span>";?>
<div class="col1" style="width:98%;margin-top:5px;">
	<div class="box">
	<div class="box_title"><h3><?php echo getLang('Search or double click an item to view details',$lang) ?></h3></div>
		<div class="box_1">
			<ul class="hot_box">
			</ul>
		</div>
		<div style="line-height:28px;padding-left:10px;margin:0 9px 0 10px;background:url('images/table-bar.png')" class="search_bar">
		<?php if ($site=='GZ' ) {?>
			 <b>Quick Search</b> &nbsp <input type="radio" id="jb" name="s_site1" value='jb' checked ><label id ="jbl" for="job">Kalex P/N</label> &nbsp <input type="radio" id="cp" name="s_site1" value="cp"><label for="cp">Customer P/N</label> &nbsp <input type="radio" id="ec" name="s_site1" value="ec"><label for="ec">End Customer   Code</label>&nbsp <input type="text" style="width:200px" id="top_search_txt">&nbsp<input type="button" value="Search" id="top_search_btn">&nbsp<input type="button" value="Clear" id="top_search_cl">
		 <?php }else{ ?>
			  <b>Quick Search</b> &nbsp <input type="radio" id="cur_site" name="s_site" checked ><label id ="check_site" for="cur_site"><?php echo $site ?></label> &nbsp <input type="radio" id="global" name="s_site" value="Global"><label for="global">Global</label> &nbsp <input type="text" style="width:200px" id="top_search_txt">&nbsp<input type="button" value="Search" id="top_search_btn">&nbsp<input type="button" value="Clear" id="top_search_cl">&nbsp<input type="button" value="Adv Search" id="adv_search_cl">
		 <?php } ?>
		</div>
		<div class="grid">
		<table id="mygrid" style="display:none;"></table>
		</div>
	</div>
</div>
<?php if (!empty($_COOKIE['FEEWEB_uName'])) { ?>
<div id="inplan" class="easyui-window" closed="true" modal="true" data-options="iconCls:'icon-search'" title="Advanced Search" collapsible="false" style="width:910px;height:590px;" >
	<div class="easyui-layout" fit="true">
		<div region="north" border="false" style="height:345px;overflow:hidden;">	
			<div border="false" style="height:22px;padding:5px;background:#fff;border-bottom:1px solid #ccc;">			
				<div id="nav" >    
					<ul>      
                        <li><input id="time_range" type="checkbox" name="time_range"/>
							<label for="time_range">Time range</label>
						</li> 
						<span id="timedate" style="display:none;">
								    <li>
							           <input id = "day_start" class="easyui-datebox" style="width:100px;" data-options="formatter:myformatter,parser:myparser"></input>
							         </li>      
								    <li>-</li> 
                                    <li>
								      <input id = "day_end" class="easyui-datebox" style="width:100px;" data-options="formatter:myformatter,parser:myparser"></input>
								    </li>
						</span>
						<li style="float:right;">
						    <input id="tra_release" type="checkbox" name="tra_release" checked/>
							<label for="tra_release">Traveler Released</label>
							<span style="padding:0 5px 0 5px"></span>
							<button type="submit" name = "search1" id="search1" class="button">Search</button>
							<span style="padding:0 5px 0 5px"></span>
							<button type="submit" name = "exportc" id="exportc" class="button">Export Current</button>
							<span style="padding:0 5px 0 5px"></span>
							<button type="submit" name = "export" id="export" class="button">Export All</button>
						</li>	
					</ul>  
				</div>
				<div style="clear:both;"></div>
				</div>
				<div border="false" style="height:200px;padding:5px;background:#fff;border-bottom:1px solid #ccc;overflow-x:hidden;overflow-y:scroll;">
					<?php include('../feeweb/inplan/'.strtolower($site).'/adv_config.php') ?>
				</div>
				<div style="height:100px;width:35%;float:left;border-right:1px solid #ccc;overflow:auto;padding:3px;">
						        <ul id="contree" class="easyui-tree" data-options="url:'../feeweb/scripts/adv_search/tree_data.json'">
									
								</ul>
						  </div>
			                <div style="width:550px;height:95px;padding-top:5px;float:right;">
						        <div style="padding:8px;font-size:12px;">
						            <div>
							            <span> 
										    <label>Logic</label>
										</span>
										<span style="padding-left:33px;">
											<select class="easyui-combobox" name="state" id = "logic" style="width:75px;"panelHeight="45" editable = false  >
		                                        <option value="AND">AND</option>
		                                        <option value="OR">OR</option>
	                                        </select>
										</span>
							            <span class="condspan"> 
										    <label>Field</label>
										</span>
										<span class="condspan">
							                <input class="easyui-combobox" 
													        id = "confield"
															name="language"
															style="width:160px;"
															data-options="
																	url:'../feeweb/scripts/adv_search/combobox_field.json',
																	valueField:'value',
																	textField:'text',
																	editable:false 
															">
										</span>					
							            <span class="condspan"> <label>Relation</label></span><span class="condspan">
							                <input class="easyui-combobox" 
													        id = "relation" 
															name="select9"
															style="width:105px;"
															data-options="
																	url:'../feeweb/scripts/adv_search/combobox_rel.json',
																	valueField:'id',
																	textField:'text',
																	editable:false 
															">
							            </span>
							        </div>
						            <div style="padding:6px 0 6px 0;"> 
									    <label>Condition</label>	
						                <span id="cond1">						   
											<input id="condition" class="easyui-combobox" 
													style="width:465px;height:22px;"
													data-options="
														valueField:'id',
														textField:'text'
														">
								        </span>			
								        <span id="cond2">
					            	        <input id="condition2" class="easyui-combobox" 
															style="width:200px;height:22px;"
															data-options="
																	valueField:'id',
																	textField:'text'
															">
															<label>  -  </label>
															<input id="cond_between" class="easyui-combobox" 
															style="width:250px;height:22px;"
															data-options="
																	valueField:'id',
																	textField:'text'
															">	
								        </span>
							        </div>
							        <div>
									    <span>
										    <input type="checkbox" id = "case_sen" name="Case Sensitive"/> 
										</span>
										<span>
										    <label for="Case Sensitive">Case Sensitive</label>
										</span>
							            <span style="margin-left:22px">
										 <span ><button style="width:80px;" id = "clear"type="button">Clear</button></span>
							            <span class="condspan"><button style="width:130px;" id = "repl"type="button">Replace</button></span>
							            <span class="condspan" ><button style="width:50px;" id = "delcon" type="button">Del</button></span>
							            <span class="condspan"><button style="width:100px;" id = "add" type="button">Add</button></span>
							            </span>
							        </div>
						        </div>
						    </div>
						   <div style="clear:both;"></div>
		</div>
		<div region="center" border="false">	
				<table id="tt"></table>
		</div>
    </div>
</div>
<?php  }?>