<?php
	require "../includes/connection.php";

	session_start();

	
	if (!empty($_POST["title"]) && !empty($_POST["context"])) {
		$title = $_POST["title"];
		$context = $_POST["context"];

		if ($_POST["is_update"] && !empty($_POST["post_id"])) {
			$postId = $_POST["post_id"];
			$title = $_POST["title"];
			$context = $_POST["context"];

			$sql = "update posts set post_title=?, post_context=? where post_id=?;";
			// Setting up prepared statement
			$stmt = mysqli_stmt_init($conn);
			// Checking prepared statement
			if (!mysqli_stmt_prepare($stmt, $sql)) {
				echo "SQL statement failed.";
			} else {
				// Binding variables to statement
				mysqli_stmt_bind_param($stmt, "ssi", $title, $context, $postId);
				// Executing statement
				mysqli_stmt_execute($stmt);
				// Redirecting user
				header("Location: ../index.php");
				exit();
			}
		} else {
			$sql = "insert into posts(post_title, post_context, user_id) values(?, ?, ?);";
			// Setting up prepared statement
			$stmt = mysqli_stmt_init($conn);
			// Checking prepared statement
			if (!mysqli_stmt_prepare($stmt, $sql)) {
				echo "SQL statement failed.";
			} else {
				// Binding variables to statement
				mysqli_stmt_bind_param($stmt, "ssi", $title, $context, $_SESSION["uid"]);
				// Executing statement
				mysqli_stmt_execute($stmt);
				// Redirecting user
				header("Location: ../index.php");
				exit();
			}
		}
	}
	