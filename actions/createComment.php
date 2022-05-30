<?php
	require "../includes/connection.php";

	session_start();

	if (!empty($_POST["context"]) && !empty($_POST["id"])) {
		$comment = $_POST["context"];
		$postId = $_POST["id"];

		$sql = "insert into comments(comment_context, user_id, post_id) values (?, ?, ?);";
		// Setting up prepared statement
		$stmt = mysqli_stmt_init($conn);
		// Checking prepared statement
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			echo "SQL statement failed.";
		} else {
			// Binding variables to statement
			mysqli_stmt_bind_param($stmt, "sii", $comment, $_SESSION["uid"], $postId);
			// Executing statement
			mysqli_stmt_execute($stmt);
		}
	}