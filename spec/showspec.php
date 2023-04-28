<script type="text/javascript" src="scripts/spec_view_list.js"></script>
<?php
require("getspec.php");
?>
<div class="body-wrap">
	<div class="inheritance-btns btns-block">
		<span id="criteria_pic"><img src="images/icon-grid.gif"/>Criteria</span>
		<span id="operation_pic"><img src="images/method.gif"/>Operation Notes</span>
		<span id="cam_pic"><img src="images/cmp.gif"/>Cam Notes</span>
		<span id="graph_pic"><img src="images/camera.gif"/>Graph</span>
	</div>
	<div class="inheritance res-block">
		<pre>Revision Info&#13;&nbsp;&nbsp;<img src="images/elbow-end.gif"/>Revision: <?php echo $revision;?>&#13;&nbsp;&nbsp;<img src="images/elbow-end.gif"/>Last update:<?php echo $last_update; ?>&#13;&nbsp;&nbsp;<img src="images/elbow-end.gif"/>Updated by:<?php echo $updated_by; ?></pre>
	</div>
	<h1><font face="Times New Roman">Customer SPEC Name:</font><font face="Times New Roman" color="blue"><?php echo $spec_name?></font></h1>
		<table cellspacing="0">
			<tr>
				<td class="label">Type:</td>
				<td class="hd-info"><?php echo $spec_type;?></td>
			</tr>
			<tr>
				<td class="label">Sub Type:</td>
				<td class="hd-info"><?php echo $sub_type;?></td>
			</tr>
			<tr>
				<td class="label">Priority:</td>
				<td class="hd-info"><?php echo $priority;?></td>
			</tr>
			<tr>
				<td class="label">Description:</td>
				<td class="hd-info"><?php echo $description;?></td>
			</tr>
			<tr>
				<td class="label">ITAR:</td>
				<td class="hd-info"><?php echo $itar;?></td>
			</tr>
			<?php
				if($_GET["site"]=="HY") {
					echo "<tr><td class='label'>Customer Reference:</td><td><a href='http://10.65.8.14/index.php?type=file&main_folder=PE/MI/03.Customer%20Specs/01.Customer%20Reference/" . substr($spec_name,0,1)."&kw=" .substr($spec_name,0,3)."&action=' target='_blank'>View Customer Reference on FTP</a></td></tr>";
					
				}
			?>
			</table>
	<div class="hr"></div>
	<a id="spec_criteria"></a>
		<h2><font face="Times New Roman">Spec Criteria</font></h2>
		<table cellspacing="0" class="member-table">
			<tbody>
				<tr>
					<th colspan="2" class="sig-header"><b>Category</b></th>
					<th class="sig-header"><b>Description</b></th>
					<th class="msource-header"><b>Site</b></th>		
					<th class="msource-header"><b>Rule Type</b></th>
					<th class="msource-header"><b>Status</b></th>	
				</tr>
					<?php 
						$index=0;
						$row_index=0;
						$pre_category="";
						$has_site_spcifiy=0;
						$is_first_loop=0;
						$is_last_loop=0;
						// var col arrays:
						$col_head=array();
						$col_desc=array();
						$col_desc_true=array();
						$col_desc_end=array();
						$col_site=array();
						$col_site_name=array();
						$col_rule=array();
						$col_rule_name=array();
						$col_status=array();
						$col_status_name=array();
						while($row = oci_fetch_array($stid, OCI_RETURN_NULLS)) {

							$description=$row['DESCRIPTION'];

							//TODO: handle graph here:
							if (strpos($description,'<@')){
								preg_match_all('/(?<=<@)([^\@>]*?)(?=@>)/',$description, $image_names);
								//$image_name = $image_names[0][0];
								//echo $image_name.count($image_names[0]);

								for ($i=0; $i< count($image_names[0]); $i++) {
									
								$description = preg_replace('/<@/i','<br/><span id="tip-info" class="hide-link"><li><a href="./graphs/'.$spec_name.'/'.$image_names[0][$i].'" class="tip">./graphs/'.$spec_name.'/'.$image_names[0][$i].'
														<div style="position:absolute; text-align:left;">
														<span id="tip_info"><img src="./graphs/'.$spec_name.'/'.$image_names[0][$i].'"/></span>
														</div>
														</a></li></span><img class="hideimage" src="graphs/'.$spec_name.'/',$description,1);
								$description = preg_replace('/@>/i','"/>',$description,1);

								}							
							
							}

							
							$description = preg_replace('/<\/=/i','<=',$description);

							$category=$row['CATEGORY_T'];
							$type=$row['TYPE_T'];
							$index=$index+1;
							$premise=$row['PREMISE'];
							$expression=$row['EXPRESSION'];
							$expression2=$row['EXPRESSION2'];
							$comparison=$row['COMPARISON_OPERATOR_T'];
							$feildname=$row['FLDNAME'];
							$uititle=$row['UITITLE'];
							$rule="";
							if ($feildname){
								$feildname=$feildname."(".$uititle.")";
							}
							if ($premise){
								$rule="<em>IF</em> ".$premise." <br/><em>THEN</em> "."<em>".$type."</em> ".$feildname." <em>". $comparison ."</em> ".$expression." ".$expression2;
							}else{
								$rule="<em>".$type."</em> ".$feildname." <em>". $comparison ."</em> ".$expression." ".$expression2;
							}
							$rule = wordwrap($rule,80,"<br/>",1);
							//TODO: handle site here:
							$site_name=$_GET["site"];
							$site_name_='';
							if (strpos($rule,'SITE_NAME_')){
								if (strpos($rule,'HY')){
									$site_name_=$site_name_.'HY,';
								}
								if (strpos($rule,'HZ')){
									$site_name_=$site_name_.'HZ,';
								}
								if (strpos($rule,'SJ')){
									$site_name_=$site_name_.'SJ,';
								}
								if (strpos($rule,'FG')){
									$site_name_=$site_name_.'FG,';
								}
								if (strpos($rule,'GZ')){
									$site_name_=$site_name_.'GZ,';
								}
								if (strpos($rule,'ZS')){
									$site_name_=$site_name_.'ZS,';
								}
								$site_name_=rtrim($site_name_,',');
							}
							if ($site_name_){$site_name=$site_name_;}
							if($site_name=='Global'){$icon= 'msource';}else{$icon= 'site';}
							
							//TODO: handle data source here:
							if (strpos($description,'[Source')){
								preg_match_all('/(?<=\[Source:)([^\]]*?)(?=\])/',$description, $data_sources);
								$data_source = $data_sources[0][0];
								$description = preg_replace("/\[Source:$data_source\]/i","<br/><pre><i>$data_source</i></pre>",$description);
							}
							
							//TODO: handle status here:
							$status='OK';
							if (strpos($description,'[Status:')){
								preg_match_all('/(?<=\[Status:)([^\]]*?)(?=\])/',$description, $data_sources);
								$status = $data_sources[0][0];
								$description = preg_replace("/\[Status:$status\]/i","",$description);
							}
							$col_desc_true[$row_index]=$description;
							//TODO: handle site specify:
							//preg_match("/^\[+\d+\]/",$description)
							//echo strpos($description,"["). $description;
						    $description=nl2br($description);
							if (preg_match("/^\[+\d+\]:/",$description) and preg_match("/^\[+\d+\]:/",$col_desc_true[$row_index-1])){
								$has_site_spcifiy=1;
								if ($pre_category<>$category){
									$is_first_loop=0;
									
									if ($is_last_loop!=0){
										
										//$col_desc[$row_index]=$col_desc[$row_index]."</table>";
										$is_last_loop=0;
										$has_site_spcifiy=0;
									}
									//<b><a href=\"#expand\" class=\"exi\">$row_index</a></b>
							
									$row_index=$row_index+1;
									$col_head[$row_index]="<tr class=\"config-row expandable inherited\">"
												 ."<td class=\"micon\"><a href=\"#expand\" class=\"exi\"></a></td>"
												 ."<td class=\"sig\" rowspan=\"1\" style='border-right:1px solid #d0d0d0;'><b><a href=\"#expand\" class=\"exi\">$category</a></b></td>"
												 ."<td class=\"sig\"><div class=\"mdesc\">";
									$col_desc[$row_index]=$description;
									$col_desc_end[$row_index]="<div class=\"long\"><b>Rule:</b><pre><code>$rule</code></pre></div>";
									$col_site[$row_index]="<td class=\"$icon\">$site_name";
									$col_rule[$row_index]="<td class=\"rule\">$type";
									$col_status[$row_index]="<td class=\"status\">$status";
									$col_site_name[$row_index]=$site_name;
									$col_rule_name[$row_index]=$type;
									$col_status_name[$row_index]=$status;
							
								}else{
									//echo $row_index;
									$row_index=$row_index-1;
									$is_first_loop=$is_first_loop+1;
									$is_last_loop=$is_last_loop+1;
									$last_index=1;
									for ($i=1; $i<=count($col_desc)+1; $i++) {
										if ((!isset($col_desc[$i])) or $col_desc[$i]==''){
											$last_index=$i;
											break;
										}
									}
									if ($is_first_loop==1){
										$table_head="<table width=\"100%\" border='1px' style=\"border:blue 1px solid;\"><th>Description</th><th>Site</th><th>Rule Type</th><th>Status</th>";
										$col_desc[$last_index-1]="$table_head<tr><td>".$col_desc[$last_index-1].$col_desc_end[$last_index-1]."</td>
										<td width='40px'>".$col_site_name[$last_index-1]."</td><td width='50px'>".$col_rule_name[$last_index-1]."</td><td width='40px'>".$col_status_name[$last_index-1]."</td></tr>"
										."<tr><td>$description<div class=\"long\"><b>Rule:</b><pre><code>$rule</code></pre></div></td>
										<td width='40px'>$site_name</td><td width='50px'>$type</td><td width='40px'>$status</td></tr></table>";
										
									}else{
										$table_head='';
										$col_desc[$last_index-1]=rtrim($col_desc[$last_index-1],"</table>");
										$col_desc[$last_index-1]=$col_desc[$last_index-1]."><tr><td>$description<div class=\"long\"><b>Rule:</b><pre><code>$rule</code></pre></div></td>
										<td width='40px'>$site_name</td><td width='50px'>$type</td><td width='40px'>$status</td></tr></table>";
									}
									//style=\"border:1px   solid   blue;\"
								
									
									$col_desc_end[$last_index-1]=$col_desc_end[$last_index-1];
									$col_head[$last_index-1]=$col_head[$last_index-1];
									$col_desc_end[$last_index-1]='';
									$col_site[$last_index-1]='<td class="rule"><br/>';
									$col_rule[$last_index-1]='<td class="rule"><br/>';
									$col_status[$last_index-1]='<td class="rule"><br/>';
									$row_index=$row_index+1;
								} 
							
								$pre_category=$category;
							}else{
									if ($is_last_loop!=0){
										//$col_desc[$row_index]=$col_desc[$row_index]."</table>";
										$is_last_loop=0;
										$has_site_spcifiy=0;
									}
									//TODO: handle rowspan here:
									$catetory_col_text="";
									//echo $pre_category;
									if ($pre_category==$category){
										$how_many_rows=1;
										
										for ($i=$row_index;$i>=1;$i--){
											//echo $row_index;
											if (strpos($col_head[$i],"rowspan")){
											preg_match_all('/(?<=rowspan=")([^"]*?)(?=")/',$col_head[$i], $row_sources);
											$this_row = $row_sources[0][0];
											
											$that_row=$this_row+1;
											$catetory_col_text="";
											$col_head[$i] = preg_replace("/rowspan=\"$this_row\"/i","rowspan=\"$that_row\"",$col_head[$i]);
											break;
											}
										}
									}else{
									
										$catetory_col_text="<td class=\"sig\" rowspan=\"1\" style='border-right:1px solid #d0d0d0;'><b><a href=\"#expand\" class=\"exi\">$category</a></b></td>";
									}



								   $row_index=$row_index+1;
							       $col_head[$row_index]="<tr class=\"config-row expandable inherited\">"
												 ."<td class=\"micon\"><a href=\"#expand\" class=\"exi\"></a></td>"
												 .$catetory_col_text."<td class=\"sig\"><div class=\"mdesc\">";
									$col_desc[$row_index]=$description;
									$col_desc_end[$row_index]="<div class=\"long\"><b>Rule:</b><pre><code>$rule</code></pre></div>";
									$col_site[$row_index]="<td class=\"$icon\">$site_name";
									$col_rule[$row_index]="<td class=\"rule\">$type";
									$col_status[$row_index]="<td class=\"status\">$status";
									$col_site_name[$row_index]=$site_name;
									$col_rule_name[$row_index]=$type;
									$col_status_name[$row_index]=$status;
									$pre_category=$category;
								}
							//$pre_category=$category;
							
						} 

						//TODO: echo out.
						$echo_text_out="";
						for ($i=1; $i<=count($col_desc)+1; $i++) {
								if (isset($col_desc[$i]) and $col_desc[$i]){
									$echo_text_out=$echo_text_out. $col_head[$i];
									$echo_text_out=$echo_text_out. $col_desc[$i];
									$echo_text_out=$echo_text_out. $col_desc_end[$i]."</div></td>";
									$echo_text_out=$echo_text_out. $col_site[$i]."</td>";
									$echo_text_out=$echo_text_out. $col_rule[$i]."</td>";
									$echo_text_out=$echo_text_out. $col_status[$i]."</td></tr>";
								}
								
							}
						//$echo_text_out=nl2br($echo_text_out);
						//$echo_text_out=str_replace(chr(13),'<br>',$echo_text_out);
						echo $echo_text_out;
					?>
			</tbody>
		</table>
		<a id="spec_operation_notes"></a>
		<h2><font face="Times New Roman">Spec Operation Notes</font></h2>
		<table cellspacing="0" class="member-table">
			<tbody>
				<tr>
					<th colspan="2" class="sig-header"><b>Description</b></th>
					<th class="msource-header"><b>Site</b></th>		
				</tr>
				
				<?php
					$index_ope=0;
					$index_cam=0;
					$cam_notes="";
					while($row = oci_fetch_array($stid1, OCI_RETURN_NULLS)) {
							$description=$row['TEXT'];
							$description=ltrim($description,'"');
							$description=rtrim($description,'"');
							$note_type=$row['NOTE_TYPE_T'];
							$operation_class=$row['OPERATION_CLASS_T'];
							$cam_class=$row['CAM_CLASS_T'];
							
							$premise=$row['PREMISE'];
							
							$rule="<em>".$premise."</em> ";
							//TODO: handle site here:
							$site_name='Global';
							$site_name_='';
							if (strpos($rule,'SITE_NAME_')){
								if (strpos($rule,'HY')){
									$site_name_=$site_name_.'HY,';
								}
								if (strpos($rule,'HZ')){
									$site_name_=$site_name_.'HZ,';
								}
								if (strpos($rule,'SJ')){
									$site_name_=$site_name_.'SJ,';
								}
								if (strpos($rule,'FG')){
									$site_name_=$site_name_.'FG,';
								}
								if (strpos($rule,'GZ')){
									$site_name_=$site_name_.'GZ,';
								}
								if (strpos($rule,'ZS')){
									$site_name_=$site_name_.'ZS,';
								}
								$site_name_=rtrim($site_name_,',');
							}
							if ($site_name_){$site_name=$site_name_;}
							if($site_name=='Global'){$icon= 'msource';}else{$icon= 'site';}




							if ($note_type=='Operation'){
							$index_ope=$index_ope+1;
							echo "<tr class=\"config-row expandable inherited\">";
							echo "<td class=\"micon\"><a href=\"#expand\" class=\"exi\"></a></td>";
							echo "<td class=\"sig\"><b><a href=\"#expand\" class=\"exi\">$index_ope.$operation_class</a></b><div class=\"mdesc\">$description";
							echo "<div class=\"long\"><br/><b>Rule:</b><pre><code>$rule</code></pre></div></div></td>";
							echo "<td class=\"$icon\">$site_name</td>";
							echo "</tr>";
							}else{
							$index_cam=$index_cam+1;
							$cam_notes=$cam_notes."<tr class=\"config-row expandable inherited\">"
									   ."<td class=\"micon\"><a href=\"#expand\" class=\"exi\"></a></td>"
									   ."<td class=\"sig\"><b><a href=\"#expand\" class=\"exi\">$index_cam.$cam_class</a></b><div class=\"mdesc\">$description"
									   ."<div class=\"long\"><br/><b>Rule:</b><pre><code>$rule</code></pre></div></div></td>"
									   ."<td class=\"$icon\">$site_name</td>"
									   ."</tr>";
							
							}
						} 

				?>
			</tbody>
		</table>
		<a id="spec_cam_notes"></a>
		<h2><font face="Times New Roman">Spec CAM Notes</font></h2>
		<table cellspacing="0" class="member-table">
			<tbody>
				<tr>
					<th colspan="2" class="sig-header"><b>Description</b></th>
					<th class="msource-header"><b>Site</b></th>		
				</tr>

				<?php
					echo $cam_notes;
				?>
			</tbody>
		</table>
</div>