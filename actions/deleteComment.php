<?php
	require "../includes/connection.php";
	
	if (!empty($_POST["id"])) {
		$commentId = $_POST["id"];

		$sql = "delete from comments where comment_id=?;";
		// Setting up prepared statement
		$stmt = mysqli_stmt_init($conn);
		// Checking prepared statement
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			echo "SQL statement failed.";
		} else {
			// Binding variables to statement
			mysqli_stmt_bind_param($stmt, "i", $commentId);
			// Executing statement
			mysqli_stmt_execute($stmt);
		}
	}
	