<?php session_start(); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
        <title>Login</title>
        <link rel="stylesheet" href="css/LogIn.css">
        <style>
            p#user {
                margin-top: 80px;
            }
            p#pass {
                margin-bottom: 55px;
            }
        </style>
        <script>
            function InvalidLogIn() {
                <?php if (isset($_SESSION["error"])) { ?>
                    alert("Invalid Username or Password");
                    <?php session_destroy() ?>
                <?php } ?>
            }
        </script>
	</head>
    <body onload="InvalidLogIn()">
        <h1 class="title">COVID - 19 Contact Tracing</h1>
        <div class="background"></div>
        <div class="main">
            <form action="phpQuerys/LoginAttempt.php" method="POST">
                <p id=user><input name="username" type="text" placeholder="Username" maxlength=255></p>
                <p id=pass><input name="password" type="password" placeholder="Password" maxlength=72></p>
                <p><input id="butSubmit" type="submit" value="Login">
                <input id="butCancel" type="reset" value="Cancel"></p>
                <p><a href="RegisterPage.php">Register</a></p>
            </form>
        </div>
    </body>
</html>