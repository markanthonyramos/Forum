<?php
	require "../includes/connection.php";
	
	if (!empty($_POST["context"]) && !empty($_POST["id"])) {
		$reply = $_POST["context"];
		$replyId = $_POST["id"];

		$sql = "update replies set reply_context=? where reply_id=?;";
		// Setting up prepared statement
		$stmt = mysqli_stmt_init($conn);
		// Checking prepared statement
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			echo "SQL statement failed.";
		} else {
			// Binding variables to statement
			mysqli_stmt_bind_param($stmt, "si", $reply, $replyId);
			// Executing statement
			mysqli_stmt_execute($stmt);
		}
	}
