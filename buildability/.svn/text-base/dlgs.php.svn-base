<!-- input pn window -->
<div id="input_window" class="easyui-window" closed="true" modal="true" title="PT Input Form" collapsible="false" minimizable="false" maximizable="false" style="width:450px;height:580px;">
			<div class="easyui-layout" fit="true">
			<div region="center" border="false" style="padding:10px;background:#fff;border:1px solid #ccc;">
			<table>
				<tr>
					<td><label>Part Number :</label></td><td><input id="input_pn" name = "input_pn_" onkeyup="enter_create_new(event)"></input></td>
				</tr>
				<tr>
					<td>Xact values status color :</td><td><select class="easyui-combobox" id="iw_xact_color" editable="false" required="true" value="" type="text" listHeight="100px" style="width:155px;">
							<option value="None">None</option>
							<option value="Green">Green</option>
							<option value="Yellow">Yellow</option>
							<option value="Red">Red</option>
					</select></td>
				</tr>
				<tr>
					<td><label>Plating :</label></td><td></td>
				</tr>
				<tr>
					<td>Wrap Spec :</td><td><select class="easyui-combobox" id="iw_warp_spec" type="text" listHeight="100px" style="width:155px;">
							<option value="Class II">Class II</option>
							<option value="Class III">Class III</option>
							<option value="Waived">Waived</option>
							<option value="Other (see notes)">Other (see notes)</option>
					</select></td>
				</tr>
				<tr>
					<td colspan=2><input type="checkbox" id="iw_dictated_drills">Dictated drills ?</input></td>
				</tr>
				<tr>
					<td>Surface Cu spec :</td><td><input type="text" id="iw_surface_cu_spec"></input></td>
				</tr>

			</table>
			<div>
					<label>Finishing tolerances :</label>
					<div>
					<input type="checkbox" id="iw_slot_length">Is the slot length < 2 X bit diameter?</input>
					</div>
					<div>
					<input type="checkbox" id="iw_overlapping">Are there overlapping holes requiring burr removal?</input>
					</div>
					<div>
					<input type="checkbox" id="iw_sotlt">Score offset tolerance less than +/- .003 inches?</input>
					</div>
					<div>
					<input type="checkbox" id="iw_rswlt">Remaining score web less than .006 inches?</input>
					</div>
					<div>
					<input type="checkbox" id="iw_rwtlt">Remaining web tolerance less than +/- .003 inches?</input>
					</div>
					<div>
					<input type="checkbox" id="iw_setetlt">Score edge to edge tolerance less than +/- .005 inches?</input>
					</div>
					<div>
					<input type="checkbox" id="iw_dtsetlt">Datum to score edge tolerance less than +/- .005 inches?</input>
					</div>
					<div>
					Enter score overrun value from score calculator :<input id="overrun_value" class="easyui-numberbox" style="width:90px;"/>
					</div>
					<div>
					<input type="checkbox" id="iw_atlt">Angle tolerance less than +/- .5 degrees?</input>
					</div>
					<div>
					<input type="checkbox"id="iw_rmttlt">Remaining mill material thickness tolerance less than +/- .007 inches?</input>
					</div>
					<div>
					<input type="checkbox" id="iw_betme">Board edge to mill edge tolerance less than +/- .005 inches?</input>
					</div>
					<div>
					<input type="checkbox" id="iw_zaxis">Z-axis clearance to Cu feature less than .007 inches?</input>
					</div>
					
					
			</div>
			<div>
					<label>Soldermask error :</label>
					<div>
					<input type="checkbox" id="iw_sm_mm">Multi-layer/multicolor SM or nomen?</input>
					</div>
					<div>
					<input type="checkbox" id="iw_sm_smd">Are soldermask SMD annular ring, SMDs clearance bridge, or SM slivers Genesis check rules violated?</input>
					</div>
			</div>

					
			</div> 
			<div region="south" border="false" style="text-align:right;height:30px;line-height:30px;">
				<a class="easyui-linkbutton" iconCls="icon-ok" href="javascript:void(0)" onclick="creat_new()">OK</a>
				<a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="close_input()">No</a>
			</div>
			</div>
</div>

<!-- sign up window -->
<div id="sign_up_window" class="easyui-window" closed="true" modal="true" title="Login..." collapsible="false" minimizable="false" maximizable="false" style="width:340px;height:230px;">
			<div class="easyui-layout" fit="true">
			<div region="center" border="false" style="padding:10px;background:#fff;border:1px solid #ccc;">
			<table align="center" style="margin-top:30px;">
			<tr>
				<td><strong>Username:</strong></td><td><input id="user_name" style="width:150px;margin:5px 10px;" onkeyup="sign_up_focus_next(event)"/></td>
			</tr>
			<tr>
				<td><strong>Password:</strong> </td><td><input id="pass_word" type="password" style="width:150px;margin:5px 10px;" onkeyup="enter_login(event)"/></td>
			</tr>
			<tr>
				<td colspan=2><input id="remember_login" type="checkbox">Remember my login on this computer</input><td><td><td>
			</tr>
			<tr>
				<td colspan=2>Not a member? <a id="send_mail" href="javascript:void(0)" style="color:blue;">Send</a> an E-mail to Admin.<td><td><td>
			</tr>
			</table>
			</div>
			<div region="south" border="false" style="text-align:right;height:30px;line-height:30px;">
				<a class="easyui-linkbutton" iconCls="icon-ok" href="#" id="log_in">OK</a>
				<a class="easyui-linkbutton" iconCls="icon-cancel" href="#" id="cancel">No</a>
			</div>
			</div>
</div>

<!-- change site window -->
<div id="change_site_window" class="easyui-window" closed="true" modal="true" title="Change Site..." collapsible="false" minimizable="false" maximizable="false" style="width:300px;height:150px;">
			<div class="easyui-layout" fit="true">
			<div region="center" border="false" style="padding:10px;background:#fff;border:1px solid #ccc;">
				<dl>
					<dt><label for="color">Choose site:</label><span id="action" style="display: none"><?php echo $_GET['action'] ?></span></dt>
						<dd>
							<input type="radio" name="site" id="HY" value="HY" <?php if ($site=="HY"){echo 'checked="true"';}?>/><label for="HY">HY</label>
							<input type="radio" name="site" id="HZ" value="HZ" <?php if ($site=="HZ"){echo 'checked="true"';}?>/><label for="HZ">HZ</label>
							<input type="radio" name="site" id="GZ" value="GZ" <?php if ($site=="GZ"){echo 'checked="true"';}?>/><label for="GZ">GZ</label>
							<input type="radio" name="site" id="ZS" value="ZS" <?php if ($site=="ZS"){echo 'checked="true"';}?>/><label for="ZS">ZS</label>
							<input type="radio" name="site" id="SJ" value="SJ" <?php if ($site=="SJ"){echo 'checked="true"';}?>/><label for="SJ">SJ</label>
							<input type="radio" name="site" id="FG" value="FG" <?php if ($site=="FG"){echo 'checked="true"';}?>/><label for="FG">FG</label>
					</dd>
				 </dl>
			</div>
			<div region="south" border="false" style="text-align:right;height:30px;line-height:30px;">
				<a class="easyui-linkbutton" iconCls="icon-ok" href="javascript:void(0)" onclick="change_this_site()">OK</a>
				<a class="easyui-linkbutton" iconCls="icon-cancel" href="javascript:void(0)" onclick="close_change_site()">No</a>
			</div>
			</div>
</div>


<!-- add member window -->
<div id="add_member_window" class="easyui-window" closed="true" modal="true" title="Config member..." collapsible="false" minimizable="false" maximizable="false" style="width:340px;height:280px;">
			<div class="easyui-layout" fit="true">
			<div region="center" border="false" style="padding:10px 70px 0;background:#fff;border:1px solid #ccc;">
		
			<div>
            <label for="name">User Name:</label>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td><select class="easyui-combobox" type="text" id="add_mem_uname" name="name" listHeight="100px" style="float:left;width:155px;"></select></td>
					<td><img src="images/user.gif" style="float:left;padding:2px;cursor:pointer;" onclick="getuserdata()"/></td>
				</tr>
			</table>
			</div>
			<div>
            <label for="disp_name">Display Name:</label>
            <input class="easyui-validatebox" type="text" id="add_mem_dispname" name="disp_name" required="true"></input>
			</div>
			<div>
            <label for="email">Email:</label>
            <input class="easyui-validatebox" type="text" id="add_mem_email" name="email" validType="email"></input>
			</div>
			<div>
            <label for="role">Role:</label>
			<input class="easyui-combobox" 
					style ="width:155px;"
					id = "role"
					name="role"
					editable="false"
					url="buildability/role_data.json" 
					valueField="id" 
					textField="text" 
					panelHeight="auto"/>
			</div>

			<div>
            <label for="obso">Obsolete:</label>
			<select class="easyui-combobox" 
					style ="width:155px;"
					id = "obsolete"
					name = "obso"
					editable="false"
					panelHeight="auto">
					<option value="0" selected>No</option>
					<option value="1">Yes</option>
			</select>
			</div>
    
			</div>
			<div region="south" border="false" style="text-align:right;height:30px;line-height:30px;">
				<a class="easyui-linkbutton" iconCls="icon-ok" href="#" id="add_member_ok">OK</a>
				<a class="easyui-linkbutton" iconCls="icon-cancel" href="#" id="add_member_no">No</a>
			</div>
			</div>
</div>

<!--config email window -->
<div id="config_email_window" class="easyui-window" closed="true" modal="true" title="Config email..." collapsible="false" minimizable="false" maximizable="false" style="width:340px;height:300px;">
			<div class="easyui-layout" fit="true">
			<div region="center" border="false" style="padding:10px 10px 0;background:#fff;border:1px solid #ccc;">
		
			<div>
            <label for="name" style="font-weight:bold;">General CC List:</label>
				<td><select class="easyui-combobox" type="text" id="email_cc_list" name="name" multiple="true" listHeight="100px" style="width:300px;"></select>
			</div>
			<div>
			<label for="new" style="font-weight:bold;">New Buildability To List:</label>
				<td><select class="easyui-combobox" type="text" id="email_new_list" name="new" multiple="true" listHeight="100px" style="width:300px;"></select>
			</div>
			<div>
			<label for="del" style="font-weight:bold;">Delete Buildability To List:</label>
				<td><select class="easyui-combobox" type="text" id="email_del_list" name="del" multiple="true" listHeight="100px" style="width:300px;"></select>
			</div>
			<div>
			<label for="close" style="font-weight:bold;">Close Buildability To List:</label>
				<td><select class="easyui-combobox" type="text" id="email_close_list" name="close" multiple="true" listHeight="100px" style="width:300px;"></select>
			</div>
			<div>
			<label for="status" style="font-weight:bold;">Status change To List:</label>
				<td><select class="easyui-combobox" type="text" id="email_status_list" name="status" multiple="true" listHeight="100px" style="width:300px;"></select>
			</div>
    
			</div>
			<div region="south" border="false" style="text-align:right;height:30px;line-height:30px;">
				<a class="easyui-linkbutton" iconCls="icon-ok" href="#" id="con_email_ok">OK</a>
				<a class="easyui-linkbutton" iconCls="icon-cancel" href="#" id="con_email_no">No</a>
			</div>
			</div>
</div>

<!-- change site window -->
<div id="about_window" class="easyui-window" closed="true" modal="true" title="About" collapsible="false" minimizable="false" maximizable="false" style="width:300px;height:250px;">
			<div class="easyui-layout" fit="true">
			<div region="center" border="false" style="padding:10px;background:#fff;border:1px solid #ccc;">
					<h4>Buildability Web Tool</h4>
					<ul>
						<li>Version:0.0.1</li>
						<li>Written by:Kyle.Jiang</li>
						<li>E-mail:kyle.jiang@viasystems.com</li>
					</ul>
					<p>Admin Contact Info:</p>
					<ul>
						<li>FG:</li>
						<li>Matzelle, Anne;Bateman, Sheryl;Camp, Tracy</li>
					</ul>
			</div>
			<div region="south" border="false" style="text-align:right;height:30px;line-height:30px;">
				<a class="easyui-linkbutton" href="javascript:void(0)" onclick="close_about()">OK</a>
			</div>
			</div>
</div>

<div id="search_window" class="easyui-window" closed="true" modal="true" title="Advanced Search" collapsible="false" minimizable="false" maximizable="false" style="width:600px;height:550px;">
			<div class="easyui-layout" fit="true">
			<div region="center" border="false" style="padding:10px;background:#fff;border:1px solid #ccc;">
					<table class="list">
						<tr><td colspan=2 style="text-align: center;">Search Criteria</tr>
						<tr>
							<td width="30%"><input type="radio" name="criteria" id="note" value="Note" checked="true"/><label for="note">Comment and note</label></td>
							<td width="70%"></td>
						</tr>
						<tr>
							<td width="30%"><input type="radio" name="criteria" id="entry" value="entry"/><label for="note">Eng area entry field</label></td>
							<td width="70%">
							<select id="entry_feild" type="text" listHeight="100px" style="width:200px;">
								<option value='Xact values status color'>Xact values status color</option>
								<option value='Is the slot length &lt; 2 X bit diameter'>Is the slot length &lt; 2 X bit diameter</option>
								<option value='Wrap spec'>Wrap spec</option>
								<option value='Surface Cu spec'>Surface Cu spec</option>
								<option value='Score offset tolerance less than +/- .003 inches'>Score offset tolerance less than +/- .003 inches</option>
								<option value='Remaining score web less than .006 inches'>Remaining score web less than .006 inches</option>
								<option value='Remaining web tolerance less than +/- .003 inches'>Remaining web tolerance less than +/- .003 inches</option>
								<option value='Score edge to edge tolerance less than +/- .005 inches'>Score edge to edge tolerance less than +/- .005 inches</option>
								<option value='Datum to score edge tolerance less than +/- .005 inches'>Datum to score edge tolerance less than +/- .005 inches</option>
								<option value='Angle tolerance less than +/- .5 degrees'>Angle tolerance less than +/- .5 degrees</option>
								<option value='Remaining mill material thickness tolerance less than +/- .007 inches'>Remaining mill material thickness tolerance less than +/- .007 inches</option>
								<option value='Board edge to mill edge tolerance less than +/- .005 inches'>Board edge to mill edge tolerance less than +/- .005 inches</option>
								<option value='Z-axis cleanrance to Cu feature less than .007 inches'>Z-axis cleanrance to Cu feature less than .007 inches</option>
								<option value='Enter score overrun value from score calculator'>Enter score overrun value from score calculator</option>
								<option value='Are soldermask SMD annular ring check rules violated'>Are soldermask SMD annular ring check rules violated</option>
								<option value='Dictated drills'>Dictated drills</option>
								<option value='Are there overlapping holes requiring burr removal'>Are there overlapping holes requiring burr removal</option>
								<option value='Multi-layer/multicolor SM or nomen'>Multi-layer/multicolor SM or nomen</option>
							</select>
							<select id="feild_relation_1" type="text" listHeight="100px" style="width:155px;">
								<option value="is">is</option>
								<option value="contains">contains</option>
								<option value="start with">start with</option>
								<option value="end with">end with</option>
								<option value="great than">great than</option>
								<option value="less than">less than</option>
							</select>
							</td>
						</tr>
						<tr>
							<td width="30%"><input type="radio" name="criteria" id="colt" value="Colt"/><label for="colt">Colt Field</label></td>
							<td width="70%">
							<select id="colt_feild" type="text" listHeight="100px" style="width:200px;">
							<?php
								$general_feilds = file_get_contents("buildability/queries/general_feilds.txt");
								$general_feilds = split(',',$general_feilds);
								for ($i=0;$i<count($general_feilds);$i++){
									echo "<option value='$general_feilds[$i]'>$general_feilds[$i]</option>";
								}
								$general_feilds = file_get_contents("buildability/queries/registration_feilds.txt");
								$general_feilds = split(',',$general_feilds);
								for ($i=0;$i<count($general_feilds);$i++){
									echo "<option value='$general_feilds[$i]'>$general_feilds[$i]</option>";
								}
								$general_feilds = file_get_contents("buildability/queries/registration_feilds_2.txt");
								$general_feilds = split(',',$general_feilds);
								for ($i=0;$i<count($general_feilds);$i++){
									echo "<option value='$general_feilds[$i]'>$general_feilds[$i]</option>";
								}
								$general_feilds = file_get_contents("buildability/queries/etchback.txt");
								$general_feilds = split(',',$general_feilds);
								for ($i=0;$i<count($general_feilds);$i++){
									echo "<option value='$general_feilds[$i]'>$general_feilds[$i]</option>";
								}
								$general_feilds = file_get_contents("buildability/queries/soldermask.txt");
								$general_feilds = split(',',$general_feilds);
								for ($i=0;$i<count($general_feilds);$i++){
									echo "<option value='$general_feilds[$i]'>$general_feilds[$i]</option>";
								}

							?>
							</select>

							<select id="feild_relation" type="text" listHeight="100px" style="width:155px;">
								<option value="is">is</option>
								<option value="contains">contains</option>
								<option value="start with">start with</option>
								<option value="end with">end with</option>
								<option value="great than">great than</option>
								<option value="less than">less than</option>
							</select>
							
							</td>
						</tr>
						<tr><td colspan=2 style="text-align: center;"><label>Keyword:</label><input type="text" id="adv_search_input"></input>
					<input type="button" id="adv_search_btn" value="Go" style="width:80px;"></input></tr>
					
					</table>


					
					<div>
					</div>
					<div id="adv_search_grid"></div>
					<div id="search_result"></div>
			</div>
			<div region="south" border="false" style="text-align:right;height:30px;line-height:30px;">
				<a class="easyui-linkbutton" href="javascript:void(0)" onclick="close_search()">Close</a>
			</div>
			</div>
</div>