<?php
session_start();
$_SESSION['FEEWEBlogin']="";
$_SESSION['FEEWEB_userName']="";
$_SESSION['FEEWEB_uName']="";
$site = $_GET['site'];

// Unset all of the session variables.
$_SESSION = array();
$lifeTime = 24 * 3600 * 365 ; 
// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-$lifeTime);
}

if (isset($_COOKIE["FEEWEB_uName"])) {
    setcookie("FEEWEB_uName", '', time()-$lifeTime,'/');
}

session_destroy();
header("Location: login.php?site=$site");
?>