$(function () {
	let postId = $("meta[name='postId']").attr("content");

	getComments();

	function getComments() {
		$(".comments-wrapper").load("./actions/getComments.php", {
			post_id: postId,
		});
	}
	// Setting up a boolean to track post form
	let isPostFormShow = false;
	// Show post form at the middle of the webpage
	function showPostForm() {
		if (!isPostFormShow) {
			$("body").css({
				overflow: "hidden",
			});
			// Making the background blackish
			$(".create-post-background").show();
			// Moving the post form to middle
			$(".create-post").css({
				transform: "translateX(-50%)",
			});

			$("main").css({
				filter: "blur(1px)"
			});
			// Changing the boolean variable to opposite of it
			isPostFormShow = !isPostFormShow;
		} else {
			// Moving the post form back
			$("main").css({
				filter: "blur(0)"
			});

			$(".create-post").css({
				transform: "translateX(-500%)",
			});
			// Removing the blackish background
			$(".create-post-background").hide();

			$("body").css({
				overflow: "auto",
			});

			$("input[name='title']").val("");
			$("textarea[name='context']").val("");
			$("input[name='post_id']").val("");
			$("input[name='is_update']").attr("checked", false);
			// Changing the boolean variable to opposite of it
			isPostFormShow = !isPostFormShow;
		}
	}

	$(".create-post-btn").on("click", showPostForm);

	$(".cancel-btn").on("click", showPostForm);

	$(".search-bar").on("submit", function (e) {
		e.preventDefault();
		let postTitle = $(this).children(":nth-child(1)");

		$(".post-card-main-wrapper").load("./actions/searchPost.php", {
			post_title: postTitle.val(),
		});

		postTitle.val("");
	});

	$(".post-three-dot-wrapper").on("click", function () {
		$(this).next().toggle();
	});

	$(".edit-post").on("click", function () {
		let postId = $(this).attr("data-post-id");
		let postTitle = $(this).parent().parent().prev().html();
		let postContext = $(this).parent().parent().parent().parent().next().next().children().html();

		$(this).parent().hide();
		$("input[name='post_id']").val(postId);
		$("input[name='is_update']").attr("checked", true);
		$("input[name='title']").val(postTitle);
		$("textarea[name='context']").val(postContext);
		showPostForm();
	});

	$(".delete-post").on("click", function () {
		let postId = $(this).attr("data-post-id");
		let del = confirm("Do you really want to delete this post?");
		
		if (del) {
			$.post("./actions/deletePost.php", {
				post_id: postId,
			});

			setTimeout(() => {
				location.reload();
			}, 100);
		}
	});

	$(document).on("click", ".like-button", function () {
		let likebtn = $(this);
		let postId = likebtn.attr("data-post-id");
		let likeCount = likebtn.prev().children();
		
		if (likebtn.hasClass("like-button liked")) {
			likebtn.removeClass("liked");

			$.post("./actions/unlikeAPost.php", {
				post_id: postId,
			});
			
			setTimeout(() => {
				likeCount.load("./actions/getLikeCount.php", {
					post_id: postId,
				});
			}, 100);
		} else {
			likebtn.addClass("liked");

			$.post("./actions/likeAPost.php", {
				post_id: postId,
			});

			setTimeout(() => {
				likeCount.load("./actions/getLikeCount.php", {
					post_id: postId,
				});
			}, 100);
		}
	});

	$(".comment-button").on("click", function () {
		let postId = $(this).attr("data-post-id");

		open(`?post_id=${postId}`);
	});
	
	$(document).on("click", "textarea", function () {
		let textAreaRows = parseInt($(this).attr("rows"));

		$(document).on("keydown", "textarea", function (e) {
			let key = e.key;
			
			if (e.shiftKey && key == "Enter") { 
				$(this).attr("rows", textAreaRows += 1);
			}
		});
	});

	function sendCommentReply(e, isEnter, link, value, pk, cb) {
		function postData() {
			if (value.trim().length > 0) {
				$.post(`./actions/${link}.php`, {
					context: value,
					id: pk,
				});

				setTimeout(() => {
					getComments();
				}, 100);
			}
		}
		
		if (isEnter) {
			if (!e.shiftKey && e.key == "Enter") {
				postData();
				return cb();
			}
		} else {
			postData();
			return cb();
		}
	}

	$(".comment-input textarea").on("keyup", function (e) {
		sendCommentReply(e, true, "createComment", $(this).val(), postId, () => {
			setTimeout(() => {
				$('.comment-count').load('./actions/getCommentCount.php', {
					post_id: postId,
				});
			}, 100);

			$(this).val("");
			$(this).attr("rows", 1);
		});
	});
	
	$(".send-comment").on("click", function (e) {
		let commentTextArea = $(this).prev();

		sendCommentReply(e, false, "createComment", commentTextArea.val(), postId, () => {
			setTimeout(() => {
				$('.comment-count').load('./actions/getCommentCount.php', {
					post_id: postId,
				});
			}, 100);

			commentTextArea.val("");
			$(this).prev().attr("rows", "1");
		});
	});

	$(document).on("click", ".comments-replies-three-dot-wrapper", function () {
		$(this).next().toggle();
		$(".comments-replies-three-dot-wrapper").toggle();
		$(this).toggle();
	});

	$(document).on("click", ".edit-comments-replies", function () {
		let commentTag = $(this).parent().parent().parent().next()
		let commentInput = commentTag.next().children(":nth-child(1)");
		let commentContext = commentTag.html()
		let arr = commentContext.split("<br>");
		commentContext = commentTag.html().replace(/<br>/g, "");
		let editComment = commentTag.next().show();

		commentInput.trigger("focus");
		commentTag.hide();
		editComment.children().attr("rows", arr.length);
		editComment.children(":nth-child(1)").html(commentContext);
		$(this).parent().hide();
		$(".comments-replies-three-dot-wrapper").show();
	});

	function deleteCommentReply(comrep, id, link, cb) {
		let del = confirm(`Do you really want to delete this ${comrep}?`);
		
		if (del) {
			$.post(`./actions/${link}.php`, {
				id: id,
			});

			setTimeout(() => {
				getComments();
			}, 100);

			return cb();
		}
	}

	$(document).on("click", ".delete-comments", function () {
		let commentId = $(this).attr("data-comment-id");
		
		deleteCommentReply("comment", commentId, "deleteComment", () => {
			$(this).parent().hide();
			$(".comments-replies-three-dot-wrapper").show();
		});
	});

	$(document).on("click", ".delete-replies", function () {
		let replyId = $(this).attr("data-reply-id");
		
		deleteCommentReply("reply", replyId, "deleteReply", () => {
			$(this).parent().hide();
			$(".comments-replies-three-dot-wrapper").show();
		});
	});

	$(document).on("keyup", ".update-comment textarea", function (e) {
		let commentId = $(this).next().attr("value");

		sendCommentReply(e, true, "updateComment", $(this).val(), commentId);
	});

	$(document).on("click", ".reply-button", function () {
		$(this).next().show();
		$(this).next().children().children(":nth-child(1)").trigger("focus");
	});

	$(document).on("keyup", ".reply-input textarea", function (e) {
		let commentId = $(this).attr("data-comment-id");

		sendCommentReply(e, true, "createReply", $(this).val(), commentId);
	});

	$(document).on("click", ".send-reply", function (e) {
		let replyInput = $(this).prev();
		let commentId = replyInput.attr("data-comment-id");

		sendCommentReply(e, false, "createReply", replyInput.val(), commentId);
	});

	$(document).on("keyup", ".update-reply textarea", function (e) {
		let replyId = $(this).next().attr("value");
		
		sendCommentReply(e, true, "updateReply", $(this).val(), replyId);
	});

	$(document).on("keyup", function (e) {
		if (e.key == "Escape") {
			$(".update-comment").hide();
			$(".comment-context").show();
		}
	});

	$(".hide-comments").on("click", function () {
		$(".comment-button").removeClass("showed-comment");
		$(".comments-wrapper").hide();
	});

	$(".view-more-comments").on("click", function () {
		$(".comments-wrapper").show();
		$(".comment-button").addClass("showed-comment");
	});
});