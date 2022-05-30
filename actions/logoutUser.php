<?php	
	session_start();
	// Destroying cookie's values
	session_unset();
	session_destroy();

	header("Location: ../pages/login.php");
	exit();
