<?php
	error_reporting(1);

	include "../includes/connection.php";

	session_start();

	function displayData($result, $conn) {
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_assoc($result)) {
				$dateCreated = date("M-d-Y | g:i A", strtotime($row["date_created"]));
				echo "
				<div class='post-card-wrapper' id='{$row["post_id"]}'>
					<div class='post-card'>
						<div class='post-title-bar'>
							<div class='post-title'>
								<h3>{$row['post_title']}</h3>
								" . (($row["posted_by"] == $_SESSION["user"]) ? "
								<div class='post-three-dot-menu'>
									<div class='post-three-dot-wrapper'>
										<div></div>
										<div></div>
										<div></div>
									</div>
									<ul class='post-drop-down'>
										<li class='edit-post' data-post-id='{$row['post_id']}'>Edit post</li>
										<li class='delete-post' data-post-id='{$row['post_id']}'>Delete post</li>
									</ul>
								</div>" : "") . "
							</div>
							<div class='post-owner'>
								<h6>Posted by: " . (($row["posted_by"] == $_SESSION["user"]) ? "You" : $row["posted_by"]) . " </h6><h6>{$dateCreated}</h6>
							</div>
						</div>
						<hr>
						<div class='post-context'>
							<p>{$row["post_context"]}</p>
						</div>
						<hr>
						<div class='post-bottom-bar'>
							<div>
								<h6><span class='like-count'>{$row["like_count"]}</span> Likes</h6>";
						$result2 = mysqli_query($conn, "select * from likes where user_id={$_SESSION['uid']} and post_id={$row['post_id']}");
						if (mysqli_num_rows($result2) == 1) {
							echo "<button data-post-id='{$row['post_id']}' class='like-button liked'>Like</button>";
						} else {
							echo "<button data-post-id='{$row['post_id']}' class='like-button'>Like</button>";
						}
						echo "
							</div>
							<div>
								<h6><span class='comment-count'>{$row["comment_count"]}</span> Comments</h6>
								<button class='comment-button showed-comment' data-post-id='{$row['post_id']}'>Comment</button>
							</div>
						</div>
						<div style='" . (($_GET["post_id"] == "") ? "display:none;" : "") . "'>
							<hr class='comment-input-hr'>
							<form class='comment-input'>
								<textarea maxlength='255' rows='1' placeholder='Type your comment here...' required></textarea>
								<button type='button' class='send-comment'>Send</button>
							</form>
							<div class='comments-wrapper'></div>
							<div class='comments-tmp'></div>
							<div class='hide-view-main-wrapper'>
								<hr>
								<div class='hide-view-wrapper'>
									<h6 class='hide-comments'>Hide all comments</h6>
									<h6 class='view-more-comments'>View more comments</h6>
								</div>
							</div>
						</div>
					</div>
				</div>";
			}
		}
	}

	if (!empty($_GET["post_id"])) {
		$postId = $_GET["post_id"];
		
		$sql = '
			select
				DISTINCT posts.post_id,
				posts.post_title,
				posts.post_context,
				posts.date_created,
				(select users.username from users where users.user_id=posts.user_id) as posted_by,
				(select count(likes.like_id) from likes where likes.post_id=posts.post_id) as like_count,
				(select count(comments.comment_id) from comments where comments.post_id=posts.post_id) as comment_count
			from posts
			where post_id=?;';
			
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

			displayData($result, $conn);
		}
	} else {
		$sql = '
		select
			DISTINCT posts.post_id,
			posts.post_title,
			posts.post_context,
			posts.date_created,
			(select users.username from users where users.user_id=posts.user_id) as posted_by,
			(select count(likes.like_id) from likes where likes.post_id=posts.post_id) as like_count,
			(select count(comments.comment_id) from comments where comments.post_id=posts.post_id) as comment_count
		from posts
		ORDER by posts.post_id desc;';
		$result = mysqli_query($conn, $sql);

		displayData($result, $conn);
	}
	?>