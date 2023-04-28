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
       $sql="SELECT pcn_no,SUBSTRING_INDEX(cust_name,';',1)as cust_name,initiator_dept,initiation_date,expected_reply_date , timestampdiff(day,expected_reply_date,now()) as delayday
       FROM pcn.pcn_main  where expected_reply_date <>'0000-00-00' and expected_reply_date is not null and curr_status='CCOMP' and timestampdiff(day,expected_reply_date,now())>=1 and splited=0";
       $title='PCN Cust Reply Delay List';
       $to='Sally.Chen;Bling.Feng;Suzy.Feng;christina.gao;Vivien.He;Mabel.Huang;lucky.jiang;Jane.Lam;carol.lin;lilian.liu;Wendy.Liu;PeiWen.Tang;Anne.Wang;Rainie.Wang;Carina.Xie;May.Zhang;gaby.zhong';
      $result=mysql_query($sql,$db);	
		 $temp='';
		 while($vo=mysql_fetch_array($result)){
						
						 $temp.=  "<tr>"
								."<td>".$vo['pcn_no']."</td>"
								."<td>".$vo['cust_name']."</td>"
								."<td>".$vo['initiator_dept']."</td>"
								."<td>".$vo['initiation_date']."</td>"
                                ."<td>".$vo['expected_reply_date']."</td>"
                                ."<td>".$vo['delayday']."</td>"
                                ."<td><a href='http://10.120.1.243/webtools/PCN/index.php/Index/view_detail/pcn_no/".$vo['pcn_no']."'>Click Me To View</a></td>"
								."</tr>";
							 }	
		    
			 $tableHead='Hi CS Team <br><br>
			            &nbsp;&nbsp;&nbsp; Please confirm following cust reply delay PCN list!<br>
			 
			   <div class="title">
						<h1>'.$title.'</h1>
					 </div>
					 <table width="100%" id="mytab"  border="1" class="t1">
						 <thead>
							  <th width="8%">Pcn Number</th>
							  <th width="5%">Customer Name</th>						 
							  <th width="10%">Initiator</th>
							  <th width="10%">Initiator Date </th>
							  <th width="15%">Expected Reply Date</th>
							  <th width="10%">Delay Day</th>
							  <th width="10%">Link</th>					  
						</thead>';		
			 $detail=($str.$tableHead.$temp."</table></body></html><br><br>".$link);//htmlspecialchars
			// echo $detail;
            
		     $subject='PCN Cust Reply Delay List';
			 $sender='pe.gud3@ttm.com';
			 $send_name='GUD3, PE';
			// $to="sheng.zhang;Mokin.Deng";
	         $cc='Qian.Hu;LY.Cheng;PE.GUD3;Sheng.Zhang;Mokin.Deng;lily.wang';
          //  require_once "D:/wamp64/www/ThinkPHP/Extend/Vendor/PHPMailer/class.phpmailer.php";	 
            require_once "/opt/apache/html/webtools/ThinkPHP/Extend/Vendor/PHPMailer/class.phpmailer.php";
            $mail = new PHPMailer();
			//$mail->SMTPDebug = 2;
			$mail->SMTPDebug  = 1;      // ����SMTP���Թ���
													   // 1 = errors and messages
													   // 2 = messages only
			$mail->CharSet ='UTF-8'; //���ò���gb2312���ı���
			$mail->IsSMTP(); //���ò���SMTP��ʽ�����ʼ�
			$mail->Host ='aphkmail.viasystems.pri'; //�����ʼ��������ĵ�ַ
			$mail->Port = 25; //�����ʼ��������Ķ˿ڣ�Ĭ��Ϊ25
			$mail->From = $sender; //���÷����˵������ַ
			$mail->FromName = $send_name; //���÷����˵�����
			//$mail->SMTPAuth = true; //����SMTP�Ƿ���Ҫ������֤��true��ʾ��Ҫ
			$mail->Username = $sender; //���÷����ʼ�������
			$mail->Password = ""; //�������������
			$mail->Subject = $title; //�����ʼ��ı���
			$mail->AltBody = "text/html"; // optional, comment out and test
			$mail->Body = $detail; //�����ʼ�����
			$mail->IsHTML(true); //���������Ƿ�Ϊhtml����
			$mail->WordWrap = 50; //����ÿ�е��ַ���
			$mail->AddReplyTo("pe.gud3@ttm.com",""); //���ûظ����ռ��˵ĵ�ַ

			foreach (split(';',$to) as $key => $value) {
					$mail_to = $value;
					if ($mail_to != '') {
						
						    $mail->AddAddress($mail_to . '@ttm.com',"");//�����ռ��ĵ�ַ
					
					}
				
				}
			 foreach (split(';',$cc) as $key => $value) {
						$mail_cc = $value;
						if ($mail_cc != '') {
							$mail->Addcc($mail_cc . '@ttm.com',"");//�����ռ��ĵ�ַ
						}
					}              
			  
						/*if ($attachment != '') //���ø���
							{
							$mail->AddAttachment($attachment,'test.xlsx');
							}*/

							if(!$mail->Send())
							{
								echo $mail->ErrorInfo;
							} else {
								echo 'Sending Mail successfully';
                                //echo $attach;
							}
           
	 
	  
  

?>