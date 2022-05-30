<?php 
  session_start();

  if (!empty($_SESSION['uid']) && !empty($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
 	<style>
		* {
			box-sizing: border-box;
			padding: 0;
			margin: 0;
			font-family: Verdana;
			outline: none;
		}
		body {
			background-color: #eee;
		}
		#center {
			display: flex;
			justify-content: space-around;
			flex-direction: column;
			align-items: center;
			height: 95vh;
		}
		#center div:first-child {
			font-family: Helvetica;
			font-size: 50px;
		}
		#center div:first-child h1 {
			color: #ff5555;
		}
		a {
			color: #ff5555;
			text-decoration: none;
  	}
		a:hover {
			text-decoration: underline;
		}
		form {
			background-color: white;
			border: 1px solid #999;
			padding: 20px;
			border-radius: 5px;
			box-shadow: 0 0 12px #999;
		}
		form h1 {
			text-align: center;
			margin-bottom: 20px;
		}
		form input {
			margin-bottom: 10px;
			padding: 10px 15px;
			border-radius: 5px;
			width: 20vw;
			font-size: 16px;
			border: 1px solid #999;
		}
		form input:focus {
			border-color: #ff5555;
		}
		form button {
			width: 100%;
			padding: 10px 15px;
			border-radius: 5px;
			margin-bottom: 10px;
			background-color: #ff5555;
			border: none;
			color: white;
			font-weight: bold;
			font-size: 14px;
			font-family: Arial;
			cursor: pointer;
		}
		form button:hover {
			opacity: 0.8;
		}
		form p {
			display: flex;
			justify-content: space-between;
		}
		#footer {
			height: 5vh;
			background-color: white;
			display: flex;
			justify-content: center;
			align-items: center;
		}
		#footer p {
			font-family: Arial;
			font-weight: bold;
		}
	</style>
</head>
<body>
  <main>
    <div id="center">
    	<div>
    		<h1>Ewan</h1>
    	</div>
    	<form action="../actions/createUser.php" method="POST">
				<h1>Sign Up</h1>
    		<input type="email" name="email" placeholder="Email Address" maxlength="30" required><br>
    		<input type="text" name="username" placeholder="Username" maxlength="30" required><br>
    		<input type="password" name="password" placeholder="Password" maxlength="30" required><br>
    		<input type="password" name="password2" placeholder="Confirm Password" maxlength="30" required><br>
    		<button type="submit">Sign Up</button>
    		<p><a href="#">Forgot Password?</a><a href="./login.php">Log In</a></p>
    	</form>
    </div>
		<div id="footer">
			<p>Ewan &copy; 2021</p>
		</div>
  </main>
	<script>
		let password = document.querySelector("input[name='password']");
		let password2 = document.querySelector("input[name='password2']");
		// Checking passwords if it didn't match
		document.querySelector("form").addEventListener("submit", (e) => {
			if (password.value != password2.value) {
				e.preventDefault();
				alert("Passwords didn't match!")
				password.value = "";
				password2.value = "";
			}
		});
	</script>
</body>
</html>
