<?php session_start(); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
        <title>Register</title>
        <link rel="stylesheet" href="css/LogIn.css">

        <script>
            function validInputs() {
                //Gathers all values from the form
                var name = document.forms["register"]["name"].value;
                var surname = document.forms["register"]["surname"].value;
                var username = document.forms["register"]["username"].value;
                var password = document.forms["register"]["password"].value;
                
                //Checks all fields have data entered
                if (name === "") {
                    alert("No name entered");
                    return false;

                } else if(surname === "") {
                    alert("No surname entered");
                    return false;

                } else if(username === "") {
                    alert("No username entered")
                    return false;

                } else if  (password === "") {
                    alert("No password entered")
                    return false;
                }
            }
            function InvalidLogIn() {
                <?php if (isset($_SESSION["error"])) { ?>
                    errorDesc = <?php echo $_SESSION["error"] ?>;

                    //Alerts the user of the error triggered and destroyes the session
                    if (errorDesc === 1) {
                        alert("That password is too short, it must be at least 8 characters.");
                    } else if (errorDesc === 2) {
                        alert("A User already has that username, please use a diffrent username.");
                    } else if (errorDesc === 3) {
                        alert("A password can only containe upper and lower case letters as well as numbers.")
                    }
                    <?php session_destroy() ?>
                <?php } ?>
            }
        </script>
        <style>
            div.main p {
                margin: 10px;
            }
            div.main p#name {
                margin-top: 80px;
            }
            div.main p#pass {
                margin-bottom: 55px;
            }
            div.main p input#butRegister {
                background-color: white;
                border-radius: 9px;
                height: 45px;
                width: 460px;
            }
        </style>
	</head>
    <body onload="InvalidLogIn()">
        <h1 class="title">COVID - 19 Contact Tracing</h1>
        <div class="background"></div>
        <div class="main">
            <form name="register" action="/phpQuerys/NewUser.php"  method="POST" onsubmit="return validInputs()">
                <p id=name><input name="name" type="text" placeholder="Name" maxlength=255></p>
                <p><input name="surname" type="text" placeholder="Surname" maxlength=255></p>
                <p><input name="username" type="text" placeholder="Username" maxlength=255></p>
                <p id=pass><input name="password" type="password" placeholder="Password" maxlength=72></p>
                <p><input id="butRegister" type="submit" value="submit"></p>
            </form>
        </div>
    </body>
</html>