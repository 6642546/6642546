<?php
   session_start();
  if(isset($_SESSION['ispe'])) {
			unset($_SESSION['ispe']);
			unset($_SESSION['user']);
			session_unset();
			session_destroy();}
   header("Location: ./index.html"); 

?>