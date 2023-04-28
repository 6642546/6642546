<?php
$servername = 'localhost';
$dbusername = 'root';
$dbpassword = 'camv4gz';
$dbname = 'fee';
$conn = @mysql_connect($servername,$dbusername,$dbpassword) or die("connection failed");
mysql_select_db($dbname,$conn);
mysql_query("set names 'UTF8'");
 session_start();
 $user=utf8_decode($_POST['user']);
 $pwd= utf8_decode($_POST['pwd']);
 foreach( get_groups( $user,$pwd) as $e){
    $groups[]=  str_replace('CN=','',explode(',',$e)[0]);
 };
  $result=implode(",",$groups);
 // echo $result;

 if ($result){
    $sql="SELECT COUNT(USERNAME) as count FROM fee_permission WHERE FIND_IN_SET(username, '".$result."') or username='".$user."'";
	//echo $sql;    
	 $query = mysql_query($sql);
      if (!$query){
           echo "{success:false,message:\"".mysql_error()."\"}";
      }
   $count=0;
	while($row = mysql_fetch_array($query)) {
		 $count=$row['count'];	 
	}
  // echo "<br>test:". $count.'<br>';
	 if($count>0) {
			 $_SESSION['user']=$user;
			 if(strpos($result,'CN=APGU.PE-ALLSTAFF') !== false){ 
			   $_SESSION['ispe']=1;
			   $_SESSION['PEr']=1;
				echo '{"result":"1"}';

			 }else{
				   if (strtolower( $user)=='lb.oh'){
					   $_SESSION['ispe']=1;
					   $_SESSION['PEr']=1;
					   echo '{"result":"1"}';
					} else{
						$_SESSION['ispe']=0;
						$_SESSION['PEr']=0;
						echo '{"result":"0"}';
					}

					}
     }else{
            echo '{"result":"2"}';
   
     }
 }else{

      echo '{"result":"3"}';

 }
function get_groups($user,$password) {	
	$ldap_url = '';
	$ldap_domain = '';
    $ldap_dn = "DC=viasystems,DC=pri";
    $ldap_url = '10.65.8.51';
    $ldap_domain="vspri";
    $ldap = ldap_connect( $ldap_url );
	ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

	$login = @ldap_bind( $ldap, "$user@$ldap_domain", utf8_decode($password) ); 
	if(!$login){
		return '';
	}
	
	// Search AD
	$results = @ldap_search($ldap,$ldap_dn,"(&(objectCategory=person)(samaccountname=$user))",array("memberof","primarygroupid"));
	$entries = @ldap_get_entries($ldap, $results);
	// No information found, bad user
	if($entries['count'] == 0) return false;
	
	// Get groups and primary group token
	$output = $entries[0]['memberof'];
	$token = $entries[0]['primarygroupid'][0];
	
	// Remove extraneous first entry
	array_shift($output);
	
	// We need to look up the primary group, get list of all groups
	$results2 = @ldap_search($ldap,$ldap_dn,"(objectcategory=group)",array("distinguishedname","primarygrouptoken"));
	$entries2 = @ldap_get_entries($ldap, $results2);
	
	// Remove extraneous first entry
	array_shift($entries2);
	
	// Loop through and find group with a matching primary group token
	foreach($entries2 as $e) {
		if($e['primarygrouptoken'][0] == $token) {
			// Primary group found, add it to output array
			$output[] = $e['distinguishedname'][0];
			// Break loop
			break;
		}
	}
   return $output;
	
}

?>