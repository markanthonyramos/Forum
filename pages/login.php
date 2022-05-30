<?php 
  session_start();

  if (!empty($_SESSION['uid']) && !empty($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
  }

	require "../includes/connection.php";

	if (!empty($_POST["username"]) && !empty($_POST["password"])) {
		$username = $_POST["username"];
		$password = $_POST["password"];
		
		$sql = "select user_id, username, password from users where username=?;";
		// Setting up prepared statement
		$stmt = mysqli_stmt_init($conn);
		// Checking prepared statement
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			echo "SQL statement failed.";
		} else {
			// Binding variables to statement
			mysqli_stmt_bind_param($stmt, "s", $username);
			// Executing statement
			mysqli_stmt_execute($stmt);
			// Getting result
			$result = mysqli_stmt_get_result($stmt);
			// Authenticating user
			if (mysqli_num_rows($result) == 1) {
				// Passing user's id and username to session variables
				while ($row = mysqli_fetch_assoc($result)) {
          if (password_verify($password, $row["password"])) {
            $_SESSION['uid'] = $row['user_id'];
            $_SESSION['user'] = $row['username'];
            
            // Inserting timestamp to last_login column in database
            mysqli_query($conn, "update users set last_login=now() where id={$_SESSION['uid']};");
            // Redirecting user
            header("Location: ../index.php");
          } else {
            header("Location: ?password_error=true");
            exit();
          }
				}
			} else {
        header("Location: ?username_error=true");
        exit();
      }
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
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
  .error-message {
    display: block;
    text-align: center;
    margin-bottom: 10px;
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
      <?php
        if (!empty($_GET["username_error"])) {
          echo '
          <form method="POST">
            <h1>Log In</h1>
            <p class="error-message">Wrong username.</p>
            <input type="text" name="username" placeholder="Username" maxlength="30" required><br>
            <input type="password" name="password" placeholder="Password" maxlength="30" required><br>
            <button type="submit">Log In</button>
            <p><a href="#">Forgot Password?</a><a href="./signUp.php">Sign Up</a></p>
          </form>';
        } elseif (!empty($_GET["password_error"])) {
          echo '
          <form method="POST">
            <h1>Log In</h1>
            <p class="error-message">Wrong password.</p>
            <input type="text" name="username" placeholder="Username" maxlength="30" required><br>
            <input type="password" name="password" placeholder="Password" maxlength="30" required><br>
            <button type="submit">Log In</button>
            <p><a href="#">Forgot Password?</a><a href="./signUp.php">Sign Up</a></p>
          </form>';
        } else {
          echo '
          <form method="POST">
            <h1>Log In</h1>
            <input class="error" type="text" name="username" placeholder="Username" maxlength="30" required><br>
            <input class="error" type="password" name="password" placeholder="Password" maxlength="30" required><br>
            <button type="submit">Log In</button>
            <p><a href="#">Forgot Password?</a><a href="./signUp.php">Sign Up</a></p>
          </form>';
        }
      ?>
    </div>
    <div id="footer">
      <p>Ewan &copy; 2021</p>
    </div>
  </main>
</body>

</html>