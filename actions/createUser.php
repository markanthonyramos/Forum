<?php
	require "../includes/connection.php";

	if (!empty($_POST["email"]) && !empty($_POST["username"]) && !empty($_POST["password"])) {
		$email = $_POST["email"];
		$username = $_POST["username"];
		$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
		
		$sql = "insert into users(username, password, email) values(?, ?, ?);";
		// Setting up prepared statement
		$stmt = mysqli_stmt_init($conn);
		// Checking prepared statement
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			echo "SQL statement failed.";
		} else {
			// Binding variables to statement
			mysqli_stmt_bind_param($stmt, "sss", $username, $password, $email);
			// Executing statement
			mysqli_stmt_execute($stmt);
			// Redirecting user
			header("Location: ../pages/login.php");
			exit();
		}
	}