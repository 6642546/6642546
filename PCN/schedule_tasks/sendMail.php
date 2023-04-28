<?php
 $str='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml">
					<head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
					<title></title>
					<style type="text/css">
					<!--
					body,table{
						font-size:12px;
					}
					table{
						table-layout:fixed;
						empty-cells:show; 
						border-collapse: collapse;
						margin:0 auto;
					}
					td{
						height:20px;
					}
					h1,h2,h3{
						font-size:12px;
						margin:0;
						padding:0;
					}
					.title { background: #FFF; border: 1px solid #9DB3C5; padding: 1px; width:90%;margin:20px auto; }
						.title h1 { line-height: 31px; text-align:center;  background: #2F589C url(th_bg2.gif); background-repeat: repeat-x; background-position: 0 0; color: #FFF; }
							.title th, .title td { border: 1px solid #CAD9EA; padding: 5px; }


					table.t1{
						border:1px solid #cad9ea;
						color:#666;
					}
					table.t1 th {
						background-image: url(th_bg1.gif);
						background-repeat::repeat-x;
						height:30px;
					}
					table.t1 td,table.t1 th{
						border:1px solid #cad9ea;
						padding:0 1em 0;
					}
					table.t1 tr.a1{
						background-color:#f5fafe;
					}
					table.t2{
						border:1px solid #9db3c5;
						color:#666;
					}
					table.t2 th {
						background-image: url(th_bg2.gif);
						background-repeat::repeat-x;
						height:30px;
						color:#fff;
					}
					table.t2 td{
						border:1px dotted #cad9ea;
						padding:0 2px 0;
					}
					table.t2 th{
						border:1px solid #a7d1fd;
						padding:0 2px 0;
					}
					table.t2 tr.a1{
						background-color:#e8f3fd;
					}

					table.t3{
						border:1px solid #fc58a6;
						color:#720337;
					}
					table.t3 th {
						background-image: url(th_bg3.gif);
						background-repeat::repeat-x;
						height:30px;
						color:#35031b;
					}
					table.t3 td{
						border:1px dashed #feb8d9;
						padding:0 1.5em 0;
					}
					table.t3 th{
						border:1px solid #b9f9dc;
						padding:0 2px 0;
					}
					table.t3 tr.a1{
						background-color:#fbd8e8;
					}

					-->
					</style>
					<script type="text/javascript">
						function ApplyStyle(s){
							document.getElementById("mytab").className=s.innerText;
						}
					</script>
					</head>
					<body>';
  /*Connect DB*/
       error_reporting(0);
		//connect database to get data
		/********** Sever Name **********/
		$servername = '10.120.1.243';
		/********** User Name  **********/
		$dbusername = 'camvgz';
		/********** Password   **********/
		$dbpassword = 'camvgz';
        $dbname = 'pcn';
		$db=mysql_connect($servername,$dbusername,$dbpassword) or die("Can not connect mysql database." . mysql_error());
		MySQL_query("SET NAMES 'utf8'");
		mysql_select_db($dbname,$db) or die("Could not select database"); 
        
		
 /*PE Manager APProval*/
   $app_dep=['PE','QA','ENGD','MFGD','QAD','SPCN','CCOMP','PEE'];
  $prem=mysql_query("SELECT user_name  FROM pcn_userrules where access_rule like 'pre%'",$db);
   while($vo=mysql_fetch_array($prem)){
      
      array_push($app_dep, 'PRE-'.$vo['user_name']);
   }

 // var_dump($app_dep);
  $link = '<a href="http://10.120.1.243/webtools/PCN/index.php?l=zh-cn">Click Me To Do The Approval</a><br/>';
 foreach( $app_dep as $key=>$value){
	    if (strstr($value,'-')){
             $result=mysql_query("SELECT * FROM pcn.pcn_main where concat(curr_status,'-',pre_m)='".$value."'",$db);
			 $tvalue=$value;
			 $value='PRE';
		}else{
		    $result=mysql_query("SELECT * FROM pcn.pcn_main where curr_status='".$value."'",$db);	
		}
		 $temp='';
		 while($vo=mysql_fetch_array($result)){
						 $internal_pn = explode(";",$vo['internal_pn']);
						 $cust_pn =     explode(";",$vo['cust_pn']);
						 $cust_name=    explode(";", $vo['cust_name']);
						 $temp.=  "<tr>"
								."<td>".$vo['pcn_no']."</td>"
								."<td>".$cust_name[0]."</td>"
								."<td>".$cust_pn[0]."</td>"
								."<td>".$vo['initiator_dept']."</td>"
								."<td>".$internal_pn[0]."</td>"
								."<td>".$vo['initiation_date']."</td>"
								."</tr>";
							 }	
		  if ($temp!=''){
			  switch ($value) {				
				  case 'PE':
				     $title="PCN Pending PE Manger Approval List";
				     $cc=getSettingValue($db,'pe_cc');
					 $to=getSettingValue($db,'pe_to');
					break;
				  case 'PRE':
					 $title="PCN Pending PRE Manger Approval List";
				     $m_pre=str_replace('PRE-','',$tvalue);
				     $cc=getSettingValue($db,'pre_cc');
					 //$to=getSettingValue($db,'pre_to');
					 $to= $m_pre.'@ttm.com';
					 break;
				  case 'QA':
					 $title="PCN Pending QA Manger Approval List";
				     $cc=getSettingValue($db,'qa_cc');
					 $to=getSettingValue($db,'qa_to');
					break;
				  case 'ENGD':
					 $title="PCN Pending Engineering Driector Approval List";
				     $cc=getSettingValue($db,'engd_cc');
					 $to=getSettingValue($db,'engd_to');
					break;
				  case 'MFGD':
					 $title="PCN Pending MFG Driector Approval List";
				     $cc=getSettingValue($db,'mfgd_cc');
					 $to=getSettingValue($db,'mfgd_to');
					break;
				  case 'QAD':
					 $title="PCN Pending QA Driector Approval List";
				     $cc=getSettingValue($db,'qad_cc');
					 $to=getSettingValue($db,'qad_to');
					break;
				  case 'SPCN':
				    $title="PCN Pending Sending Customer List";
				     $cc=getSettingValue($db,'spcn_cc');
					 $to=getSettingValue($db,'spcn_to');
					break;
				  case "CCOMP":
				    $title="PCN Pending Customer Replying List";
				     $cc=getSettingValue($db,'ccomp_cc');
					 $to=getSettingValue($db,'ccomp_to');
					break;
				  case 'PEE':
                     $title="PCN Pending Executed PTL List";
				     $cc=getSettingValue($db,'pee_cc');
					 $to=getSettingValue($db,'pee_to');
					break;              
				}
			 $tot=str_replace('@ttm.com','',$to);
			 $tableHead='Hi '.$tot.'<br><br>
			            &nbsp;&nbsp;&nbsp; Please confirm following PCN List!<br>
			 
			   <div class="title">
						<h1>'.$title.'</h1>
					 </div>
					 <table width="100%" id="mytab"  border="1" class="t1">
						 <thead>
							  <th width="8%">Pcn Number</th>
							  <th width="5%">Customer Name</th>						 
							  <th width="10%">Customer P/N</th>
							  <th width="10%">Initiator/Dept</th>
							  <th width="15%">Internal P/N</th>
							  <th width="10%">Date</th>
												  
						</thead>';		
			 $detail=($str.$tableHead.$temp."</table></body></html><br><br>".$link);//htmlspecialchars
			// echo $detail;
			 $subject='PCN Pending PE Manger Approval  List';
			 $sender='pe.gud3';
			 $send_name='GUD3, PE';
			 $date=date('Y-m-d h:i:s', time());
			 $sql="INSERT INTO pcn_emails ( id,date,type, send_name,sender, to_,cc,subject,content,complete )
                       VALUES
                       ( NULL, '$date','','GUD3, PE','pe.gud3@ttm.com','$to','$cc','$title','$detail',0)";
			//echo $sql;
			mysql_query($sql,$db) or die('添加数据出错：'.mysql_error()); 


			// $to='sheng.zhang';
			// $cc='Qi.Qin;Qian.Hu';
			// s_mail($sender,$send_name,$to,$cc,$subject,$detail);
		  }				
 }
	  $result=mysql_query("SELECT * FROM pcn_emails where complete=0",$db);	
	   while($vo=mysql_fetch_array($result)){
		   $sender=$vo['sender'];
           $send_name=$vo['send_name'];
		   $to=$vo['to_'];
           $cc=$vo['cc'];
           $subject=$vo['subject'];
           $detail=$vo['content'];
		  // echo $sender.'<br>'.$send_name.'<br>'.$to.'<br>'.$cc.'<br>'.$subject.'<br>'.$detail;
	       s_mail($sender,$send_name,$to,$cc,$subject,$detail);
	       $sql="update pcn_emails set complete=1 where id=".$vo['id'];
           mysql_query($sql,$db);	
	   }

  	function getSettingValue($db,$val){
				$result=mysql_query("select setting_value from pcn_setting where lower(setting_name)='".$val."'",$db);
				$return=mysql_fetch_array($result);
				$complete = $return[0];
				return $complete;
		}
//echo $str.$detail.$foot;
		function s_mail($fromAddress,$from_nickname,$sendto,$ccAddress, $title, $response) {
						//include ("class.phpmailer.php");
					    //require_once "D:/wamp64/www/ThinkPHP/Extend/Vendor/PHPMailer/class.phpmailer.php";			
					   // require_once "/usr/local/apache/htdocs/home/webtools/ThinkPHP/Extend/Vendor/PHPMailer/class.phpmailer.php";
						require_once "/opt/apache/html/webtools/ThinkPHP/Extend/Vendor/PHPMailer/class.phpmailer.php";
						$sendto_mail = $sendto;
						$subject = $title;
						$body = $response;
						$mail = new PHPMailer(); 
						$mail->IsSMTP();
						$mail->SMTPDebug = 0;
						//$mail->Host = "amusmail.viasystems.pri";   
						$mail->Host ='aphkmail.viasystems.pri';
						$mail->FromName =  $from_nickname;   
						//$mail->SMTPAuth = true;          
						//$mail->From = $fromAddress.'@ttm.com';
						$mail->From = $fromAddress;
						$mail->CharSet = "utf8";           
						$mail->Encoding = "base64"; 
                       // $mail->AddAddress($sendto_mail);
						//$mail->AddCC($ccAddress);
						$array = explode(';',$sendto_mail);
						$count = count($array);
							for ($i = 0; $i < $count; $i++) {
								if(trim($array[$i])) {
									$mail->AddAddress(trim($array[$i]));  
									
								}
									
							}
						$array = explode(';',$ccAddress);	
						$count = count($array);
							for ($i = 0; $i < $count; $i++) {
								if(trim($array[$i])) {
									$mail->AddCC(trim($array[$i]));  	
								}
								
							}

						$mail->IsHTML(true); 
						$mail->Subject = $subject;
						$mail->Body = $body;
						$mail->AltBody ="text/html"; 
						$mail->Send();
				}

?>