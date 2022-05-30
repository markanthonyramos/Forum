<?php
	require "../includes/connection.php";

	if (!empty($_POST["post_id"])) {
		$postId = $_POST["post_id"];
		
		$sql = "select count(comment_id) as comment_count from comments where post_id=?;";
		
		$stmt = mysqli_stmt_init($conn);
		// Checking prepared statement
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			echo "SQL statement failed.";
		} else {
			// Binding variables to statement
			mysqli_stmt_bind_param($stmt, "i", $postId);
			// Executing statement
			mysqli_stmt_execute($stmt);
			// Getting result
			$result = mysqli_stmt_get_result($stmt);

			while ($row = mysqli_fetch_assoc($result)) {
				echo $row["comment_count"];
			}
		}
	}
