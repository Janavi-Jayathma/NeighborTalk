<?php
	session_start();
	// this code section will remove the session varibales
	unset($_SESSION["username"]);
	unset($_SESSION["role"]);
	if(session_destroy()) {
      header("Location: ../app/home.php"); //redirects to the login page
   }
?>
