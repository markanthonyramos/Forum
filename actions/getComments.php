<?php
	require "../includes/connection.php";

	session_start();

	if (!empty($_POST["post_id"])) {
		$postId = $_POST["post_id"];

		function displayData($result) {
			if (mysqli_num_rows($result) > 0) {
				while ($row = mysqli_fetch_assoc($result)) {
					echo "
					<hr class='comments-hr'>
					<div class='comments-replies'>
						<div class='comments' id='comment-{$row["comment_id"]}'>
							<div class='comments-title-bar'>
								<h6>Commented by: {$row["commented_by"]}</h6>
								" . (($row["posted_by"] == $_SESSION["user"]) ? "
									<div class='comments-replies-three-dot-menu'>
										<div class='comments-replies-three-dot-wrapper'>
											<div></div>
											<div></div>
											<div></div>
										</div>
										<ul class='comments-replies-drop-down'>
											<li class='edit-comments-replies' data-comment-id='{$row['comment_id']}'>Edit comment</li>
											<li class='delete-comments' data-comment-id='{$row['comment_id']}'>Delete comment</li>
										</ul>
									</div>" : "") . "
							</div>
							<p class='comment-context'>" . nl2br($row["comment_context"]) . "</p>
							<form class='update-comment'>
								<textarea rows='1' maxlength='255'></textarea>
								<input type='hidden' value='{$row["comment_id"]}'>
								<h6>Press esc to cancel</h6>
							</form>
						</div>
						<h6 class='reply-button'>Reply</h6>
						<div class='reply-input-wrapper'>
							<form class='reply-input'>
								<textarea data-comment-id='{$row["comment_id"]}' maxlength='255' rows='1' placeholder='Type your reply here...' required></textarea>
								<button type='button' class='send-reply'>Send</button>
							</form>
						</div>"; 
						if ($row['reply_context'] != "") {
							$replyId = explode(",", $row["reply_id"]);
							$repliedBy = explode(",", $row["replied_by"]);
							$replyContext = explode(",", $row["reply_context"]);

							foreach ($replyContext as $index => $val) {
								echo "
								<div class='replies' id='reply-{$replyId[$index]}'>
									<div class='reply-title-bar'>
									<h6>Replied by: {$repliedBy[$index]}</h6>
									" . (($row["posted_by"] == $_SESSION["user"]) ? "
									<div class='comments-replies-three-dot-menu'>
										<div class='comments-replies-three-dot-wrapper'>
											<div></div>
											<div></div>
											<div></div>
										</div>
										<ul class='comments-replies-drop-down'>
											<li class='edit-comments-replies' data-reply-id='{$replyId[$index]}'>Edit reply</li>
											<li class='delete-replies' data-reply-id='{$replyId[$index]}'>Delete reply</li>
										</ul>
									</div>" : "") . "
									</div>
									<p>" . nl2br($val) . "</p>
									<form class='update-reply'>
										<textarea maxlength='255'></textarea>
										<input type='hidden' value='{$replyId[$index]}'>
										<h6>Press esc to cancel</h6>
									</form>
								</div>";
							}
						} else {
							echo "";
						} 
					echo "</div>";
				}
			}
		}
		
		$sql = "
		SELECT
			(select users.username from users where users.user_id=posts.user_id) as posted_by,
			comments.comment_id,
			comments.comment_context,
			comments.date_created as comment_date_created,
			(select users.username from users where users.user_id=comments.user_id) as commented_by,
			GROUP_CONCAT(replies.reply_id) as reply_id,
			GROUP_CONCAT(replies.reply_context) as reply_context,
			GROUP_CONCAT(replies.date_created) as reply_date_created,
			GROUP_CONCAT((select users.username from users where users.user_id=replies.user_id)) as replied_by 
		from comments
		left JOIN posts
			on posts.post_id=comments.post_id
		left JOIN replies
			on replies.comment_id=comments.comment_id
		where comments.post_id=?
		group by comments.comment_id desc;";
			
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

			displayData($result);
		}
	}