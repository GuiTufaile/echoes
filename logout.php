<?php 

	// Includes e requires
    include_once "config.php";
    include RESOURCE_PATH."/php/classes/class.mysql.php"; 
	
	session_start();
	session_destroy();
	header("Location: ".ROOT_WEB."/index.php");
		
?>