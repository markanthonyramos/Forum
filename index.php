<?php
	require "./includes/connection.php";

	session_start();

	if (empty($_SESSION['uid']) && empty($_SESSION['user'])) {
		header('Location: ./pages/login.php');
		exit();
	}
?>
<!DOCTYPE html>
<html lang='en'>
<head>
	<meta charset='UTF-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1.0'>
	<meta name='postId' content='<?php echo $_GET["post_id"] ?>'>
	<title>Ewan</title>
	<link rel='stylesheet' href='./style.css?<?php echo time(); ?>'>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css"
		integrity="sha384-vSIIfh2YWi9wW0r9iZe7RJPrKwp6bG+s9QZMoITbCckVJqGCCRhc+ccxNcdpHuYu" 
		crossorigin="anonymous">
	<script src='https://code.jquery.com/jquery-3.5.1.min.js' 
		integrity='sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=' 
		crossorigin='anonymous'></script>
</head>
<body>
	<div>
		<button type='button' class='create-post-btn'>Create a post</button>
		<form action="./actions/createPost.php" method="POST" class='create-post'>
			<div>
				<button type='button' class='cancel-btn'>Cancel</button>
				<button type='submit' class='post-btn'>Post</button>
			</div>
			<input type="hidden" name='post_id'>
			<input type="hidden" name='is_update' value='false'>
			<input type='text' name='title' placeholder='Title' maxlength='50' required><br>
			<textarea name='context' placeholder='Say someting about your post...' maxlength='255' required></textarea><br>
		</form>
		<div class="create-post-background"></div>
	</div>
	<main>
		<div id='nav'>
			<div>
				<h2><a href='./index.php'>Ewan</a></h2>
			</div>
			<?php
			if ($_SERVER["PHP_SELF"] == $_SERVER["REQUEST_URI"]) {
				echo '
				<form class="search-bar">
					<input type="text" placeholder="Search a post here" required>
					<button class="search-button"><i class="fas fa-search"></i></button>
				</form>';
			}
			?>
			<div>
				<ul>
					<li>Welcome, <?php echo $_SESSION['user'] ?></li>
					<li><a href='./actions/logoutUser.php'>Logout</a></li>
				</ul>
			</div>
		</div>
		<div class='post-card-main-wrapper'>
			<?php
				include "./actions/getPosts.php";
			?>
		</div>
	</main>
	<script src='./index.js?<?php echo time() ?>'></script>
	<?php
		if ($_GET["post_id"]){
			echo "<script>
				$(function () {
					let postId = $(\"meta[name='postId']\").attr('content');

					setInterval(() => {
						getLikeCommentCount();
					}, 1000);

					function getLikeCommentCount() {
						$('.like-count').load('./actions/getLikeCount.php', {
							post_id: postId,
						});
						$('.comment-count').load('./actions/getCommentCount.php', {
							post_id: postId,
						});
					}
				});
			</script>";
		}
	?>
</body>
</html>