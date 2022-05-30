<?php
	require "../includes/connection.php";
	
	if (!empty($_POST["context"]) && !empty($_POST["id"])) {
		$comment = $_POST["context"];
		$commentId = $_POST["id"];

		$sql = "update comments set comment_context=? where comment_id=?;";
		// Setting up prepared statement
		$stmt = mysqli_stmt_init($conn);
		// Checking prepared statement
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			echo "SQL statement failed.";
		} else {
			// Binding variables to statement
			mysqli_stmt_bind_param($stmt, "si", $comment, $commentId);
			// Executing statement
			mysqli_stmt_execute($stmt);
		}
	}
