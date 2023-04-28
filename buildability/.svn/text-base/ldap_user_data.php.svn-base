<?php
$ldap_server = '10.65.8.51';
$domain="vspri";
$username ="kyle.jiang";
$chknumber = "jlong05%";

$conn_ = ldap_connect($ldap_server);

//$bind = ldap_bind($conn_, "$domain\\".$username, $chknumber);


$base_dn = "o=FGDOM01,c=US";
$filter_col = "mail";
$filter_val = "kyle.jiang";

$filter="(|(sn=$filter_val*)(givenname=$filter_val*))";
$justthese = array("ou", "sn", "mail");

$result = ldap_search($conn_,"o=FGDOM01","cn=Recipients*",$justthese);
$entry = ldap_first_entry($conn_ , $result);
$firstname = ldap_get_values($conn_, $entry, "mail");
echo $firstname;

?>