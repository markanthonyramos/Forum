<?php
	require "../includes/connection.php";
	
	if (!empty($_POST["post_id"])) {
		$postId = $_POST["post_id"];

		$sql = "delete from posts where post_id=?;";
		// Setting up prepared statement
		$stmt = mysqli_stmt_init($conn);
		// Checking prepared statement
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			echo "SQL statement failed.";
		} else {
			// Binding variables to statement
			mysqli_stmt_bind_param($stmt, "i", $postId);
			// Executing statement
			mysqli_stmt_execute($stmt);
		}
	}
	