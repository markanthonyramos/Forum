<?php
	require "../includes/connection.php";

	session_start();

	if (!empty($_POST["context"]) && !empty($_POST["id"])) {
		$reply = $_POST["context"];
		$commentId = $_POST["id"];

		$sql = "insert into replies(reply_context, user_id, comment_id) values (?, ?, ?);";
		// Setting up prepared statement
		$stmt = mysqli_stmt_init($conn);
		// Checking prepared statement
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			echo "SQL statement failed.";
		} else {
			// Binding variables to statement
			mysqli_stmt_bind_param($stmt, "sii", $reply, $_SESSION["uid"], $commentId);
			// Executing statement
			mysqli_stmt_execute($stmt);
		}
	}