<?php
	$host = "localhost";
	$user = "root";
	$password = "";
	$dbName = "socmed";

	$conn = mysqli_connect($host, $user, $password, $dbName);

	if (!$conn) {
		echo "Error at database connection.";
	}