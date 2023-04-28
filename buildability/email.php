<?php
require("../plugins/phpmailer/class.phpmailer.php"); 
error_reporting(E_ERROR);

function smtp_mail ( $sendto_email, $subject, $body ,$fromAddress) {
	$mail = new PHPMailer(); 
	$mail->IsSMTP();
	$mail->SMTPDebug = 1;

	$mail->Host = "amusmail.viasystems.pri";   

	$mail->FromName =  "Buildability";   
	//$mail->SMTPAuth = true;          
	$mail->From = $fromAddress;
	$mail->CharSet = "utf8";           
	$mail->Encoding = "base64"; 
	$array = split(';',$sendto_email);	
	$count = count($array);
		for ($i = 0; $i < $count; $i++) {
			$mail->AddAddress(trim($array[$i]));  	
		}
	$mail->IsHTML(true); 
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->AltBody ="text/html"; 
	if(!$mail->Send()) { 
		echo  $mail->ErrorInfo; 
	}
}

function getEmailAdress($config_name,$site,$config_type,$db){
	$emails = "";
	$query = "select config_text from buildability_config where config_name='$config_name' and site='$site' and config_type='$config_type'";
	$req=@mysql_query($query,$db);	
	$result=@mysql_fetch_array($req);
	$config_text = $result[0];
	if ($config_text){
		$array = split(',',$config_text);	
		$count = count($array);
		for ($i = 0; $i < $count; $i++) {
			if (trim($array[$i])){
				$query = "select email from users where user_name='$array[$i]'";
				$req=@mysql_query($query,$db);	
				$result=@mysql_fetch_row($req);
				if ($result[0]){
					$emails .=$result[0].";";
				}
			}
		}
	}
	return rtrim($emails,';');
	//return rtrim(htmlspecialchars($emails, ENT_QUOTES),';');
}

function getFromAdress($user_name,$db){
	$emails = "";
	$query = "select email from users where display_name='$user_name'";
	$req=@mysql_query($query,$db);	
	$result=@mysql_fetch_array($req);
	$config_text = $result[0];
	if (!$config_text){
		$config_text = 'kyle.jiang@viasystems.com';
	}
	return $config_text;
}

function sendMail($site,$config_type,$part_number,$db,$user_name,$body,$Subject){
		if ($config_type =='email_new_list'){
			$toAddress = getEmailAdress('email_new_list',$site,'email',$db);
			$fromAddress = getFromAdress($user_name,$db);
			if ($toAddress){
				//$Subject='New Buildability has been created for '.$part_number.'.';
				$Body=$body;
				smtp_mail($toAddress, $Subject,$Body ,$fromAddress );
			}
		}
}
?>
