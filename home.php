<div class="top_link">
	<a href="index.php?site=<?php echo $site; ?>&action=home<?php if ($lang) echo "&lang=$lang"; ?>">Home</a>
</div>

<div style="float:right">
<div class="col5" style="width:660px;margin-top:5px;">
	<div class="box">
		<div class="box_title"><h3>Welcome</h3></div>
			<div class="box_1">
				<ul class="hot_box">
					<p><b><i>Welcome to the Global Front End Engineering tooling web site.  This site is an integrated engineering data and systems platform that allows you to edit / view buildabilities, view InPlan job and associated attributes including stack up’s, customer specs, travelers, etc…at virtually any of the PCB manufacturing / engineering organizations in North America and Asia.  This site will continue to be developed for future enhancements such as Global TQ access and Genesis CAM attributes.</i></b>
					</p>
					<p><b><i>This site and all information residing on this site is proprietary to TTM Technologies Group Inc, any unauthorized access to this site or distribution of engineering data on this site is strictly forbidden. Please do not share the engineering reports, capabilities and documents with suppliers, customers, or other industry members without prior written approvals.</i></b>
					</p>
					<br/>
					<p style="text-align: right;margin-right:40px;" ><b><i>Global Front End Management</i></b></p>
					<br/>
					
				</ul>
			</div>
		</div>
</div>
</div>

<div style="float:left;clear:left">
<div style="width:280px;margin-top:5px;margin-left:10px">
	<div class="box">
		<div class="box_title"><h3>Home Navigate</h3></div>
			<div class="box_1">
				<ul class="hot_box">
					<li><a href="index.php?site=<?php echo $site; ?>&action=buildability<?php if ($lang) echo "&lang=$lang"; ?>">Buildability</a></li>
					<li><a href="http://feeweb-us/BuildabilityReport/index.html" target="_blank">Buildability Reports</a></li>
						<li><a href="index.php?site=<?php echo $site; ?>&action=specification<?php if ($lang) echo "&lang=$lang"; ?>">Customer Specification & Reference</a></li>
						<li><a href="index.php?site=<?php echo $site; ?>&action=inplan<?php if ($lang) echo "&lang=$lang"; ?>">InPlan</a></li>
						<!--li><a href="index.php?site=<?php echo $site; ?>&action=productivity<?php if ($lang) echo "&lang=$lang"; ?>">Productivity Report</a></li-->
						<li><a href="index.php?site=<?php echo $site; ?>&action=ul<?php if ($lang) echo "&lang=$lang"; ?>">UL Database</a></li>
						<li><a href="admin/index.php?site=<?php echo $site; ?>">Admin</a></li>
						<!--li><a href="index.php?site=<?php echo $site; ?>&action=genesis<?php if ($lang) echo "&lang=$lang"; ?>">Genesis</a></li-->
				</ul>
			</div>
		</div>
	</div>
<div style="width:280px;margin-top:10px;margin-left:10px">
	<div class="box">
		<div class="box_title"><h3>Quick Search</h3></div>
			<div class="box_1">
				<ul class="hot_box">
					<div id="search_area"> &nbsp;Search Area:
						<input type="radio" id="cur_site" name="search_area" value="Current Site" checked><label for="cur_site">Current Site</label></input>
						<input type="radio" id="cur_global" name="search_area" value="Global"><label for="cur_global">Global</label></input>
					</div>
					<br/>
					<div class=search>
						<div class="search_menu"><a href="#" id="menu1" class="menu_gg" onclick="javascript:doClick_menu(this)">InPlan</a>|<a href="#" id="menu2"  onclick="javascript:doClick_menu(this)">Spec</a>|<a href="#" id="menu3" onclick="javascript:doClick_menu(this)">Buildability</a>|<a href="#" id="menu4" onclick="javascript:doClick_menu(this)">TQ</a></div>
							<div class="menu_1" style="display:block;" id="menu_con1"></div>
							<div class="disable menu_2" id="menu_con2"></div>
							<div class="disable menu_3" id="menu_con3"></div>
							<div class="disable menu_4" id="menu_con4"></div>
							<div class="disable menu_5" id="menu_con5"></div>
								<table border="0" cellpadding="0" cellspacing="0" class="home_tab_search">
									<tr>
										<td>
											<input type="text" name="q" title="Search" class="home_searchinput" id="home_searchinput" size="10" value="- Quick Search -" onfocus='if(this.value=="- Quick Search -"){this.value="";}' onblur='if(this.value==""){this.value="- Quick Search -";}' />
										</td>
										<td>
											<input type="image" width="21" height="17" class="home_searchaction" alt="Search" src="images/magglass.gif" border="0" hspace="2"/>
										</td>
									</tr>
								</table>
					</div>
					<br/>
				</ul>
			</div>
		</div>
	</div>
<div style="width:280px;margin-top:10px;margin-left:10px">	
	<div class="box">
		<div class="box_title"><h3>Customer Profiles</h3></div>
			<div class="box_1">
				<ul class="hot_box">
					<li><a href="http://feeweb-ca/gcp/" target="_blank">Global Customer Profiles</a></li>
					<li><a href="http://aphype01/index.php?main_folder=PE%2FMI%2F03.Customer+Specs+and+Industry+Standards%2F01.Customer+Profile" target="_blank">HY Customer Profiles</a></li>
					<li><a href="\\apgzfp05\Users\PE folder\QS9000\MI Group home page\3.Customer help file\Customer Helpfile\1-Customer Helpfile homepage\Doc2.htm" target="_blank">GZ Customer Profiles</a></li>
					<li><a href="\\10.125.0.254\pe\Customer Help files" target="_blank">ZS Customer Profiles</a></li>
					<li><a href="http://itapps/cview/customers.htm" target="_blank">FG Customer Reference</a></li>
					<li><a href="http://feeweb-us/feeweb/index.php?site=SJ&action=specification" target="_blank">SJ Customer Profiles</a></li>
					<li><a href="http://viapoint/department/Eng/PCB/SitePages/Flysheets.aspx" target="_blank">ANA-STE Especs</a></li>
					<li><a href="http://www.howfarhowfast.com/dept/engineering/tools/AdvSearch/custProfileSearch.asp" target="_blank">TOR-DEN-CLE HFHF</a></li>
				</ul>
			</div>
		</div>
</div>
<div style="width:280px;margin-top:10px;margin-left:10px">	
	<div class="box">
		<div class="box_title"><h3>Links</h3></div>
			<div class="box_1">
				<ul class="hot_box">
					<li><a href="http://www.ttm.com/" target="_blank">TTM Web</a></li>
					<li><a href=" http://viapoint/SitePages/Home.aspx" target="_blank">ViaPoint</a></li>
					<li><a href="http://eap-prod.viasystems.pri:8080/OA_HTML/AppsLogin" target="_blank">Oracle</a></li>
					<li><a href="http://mxjira:8080/secure/Dashboard.jspa" target="_blank">JIRA</a></li>
					<li><a href="http://10.65.8.33:3369/Mat_Manager/Main.aspx" target="_blank">MAT-HY</a></li>
					<li><a href="http://gd3pec0599/Mat_GZ/Login.aspx" target="_blank">MAT-GZ</a></li>
					<li><a href="http://cat03/WipMonitor" target="_blank">Tooling FTT Monitor</a></li>
				</ul>
			</div>
		</div>
		<br/>
</div>
</div>


<!--div class="col5" style="width:660px;margin-top:5px;">
	<div class="box">
	<div class="box_title"><h3>Program Overview</h3></div>
	<div class="box_1">	
		<div class="infiniteCarousel">
			<div class="wrapper">
			<ul id='imgscroll'>
				<li><a href=""><img src='images/program/buildability.PNG' border='0' width='143' height='106' alt='Buildability'><span class="title">Buildability</span></a></li>
				<li><a href="/dedecms/uploads/a/pics/2010/0407/94.html"><img src='' border='0' width='143' height='106' alt='Specification'><span class="title">Specification</span></a></li>
				<li><a href="/dedecms/uploads/a/pics/2010/0407/93.html"><img src='' border='0' width='143' height='106' alt='InPlan'><span class="title">InPlan</span></a></li>
			</ul>
		</div>
		</div>
	</div>
</div>
</div-->














