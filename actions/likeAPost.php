<?php
	require "../includes/connection.php";

	session_start();

	if (!empty($_POST["post_id"])) {
		$postId = $_POST["post_id"];

		$sql = "insert into likes(user_id, post_id) values (?, ?);";
		// Setting up prepared statement
		$stmt = mysqli_stmt_init($conn);
		// Checking prepared statement
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			echo "SQL statement failed.";
		} else {
			// Binding variables to statement
			mysqli_stmt_bind_param($stmt, "ii", $_SESSION["uid"], $postId);
			// Executing statement
			mysqli_stmt_execute($stmt);
		}
	}