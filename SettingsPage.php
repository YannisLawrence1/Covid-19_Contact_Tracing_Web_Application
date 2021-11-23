<?php session_start();
    if (!is_numeric($_SESSION["id"])) {
        echo "Cannot view this page unless you are signed in!
        <p><a href=\"phpQuerys/LogOut.php\">Return to Login</a></p>";
        die();
    }
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
        <title>Settings Page</title>
        <link rel="stylesheet" href="css/Main.css">
        <script> 
            function getCookies() {
                //gets the 2 cookies and sets there value in the table
                var cookieWindow = document.cookie
                    .split('; ')
                    .find(row => row.startsWith('<?php echo htmlspecialchars($_SESSION["username"])?>Window='))
                    .split('=')[1];
                cookieWindow = cookieWindow.replace('+', ' ');

                var cookieDistance = document.cookie
                    .split('; ')
                    .find(row => row.startsWith('<?php echo htmlspecialchars($_SESSION["username"])?>Distance='))
                    .split('=')[1];
                
                document.getElementById('window').value = cookieWindow;
                document.getElementById('distance').value = cookieDistance;
            }
        </script>

        <style>
            div.main p input{
                font-family: 'Times New Roman';
                font-size: 20pt;
                text-align: center;
                background: transparent;
                margin: 0px;
                width: 210px;
                height: 40px;
                border: 2px solid black;
            }
            div.main p input#distance{
                margin-left: 22px;
                width: 362px;
                height: 40px;
            }
            div.main p select{
                font-family: 'Times New Roman';
                font-size: 20pt;
                text-align: center;
                background: transparent;
                margin: 0px;
                margin-left: 20px;
                width: 370px;
                height: 45px;
                border: 2px solid black;
                text-align-last: center;
            }
            div.main form{
                width: 100%;
                margin-top: 90px;
            }
            div.main p.center{
                text-align: center;
            }
            div.main p input#butSubmit{
                background-color: white;
                border-radius: 9px;
                width: 218px;
                height: 45px;
                margin-left: 0%;
                margin-right: calc(100% - 443px);
            }
            div.main p input#butCancel{
                background-color: white;
                border-radius: 9px;
                width: 218px;
                height: 45px;
            }
        </style>
	</head>
	<body onload="getCookies()">
		<h1 class="title">COVID - 19 Contact Tracing</h1>

        <ul class="sidemenu">
            <li><a href="HomePage.php">Home</a></li>
            <li><a href="Overview.php">Overview</a></li>
            <li><a href="AddVisit.php">Add Visit</a></li>
            <li><a href="ReportPage.php">Report</a></li>
            <li><a href="SettingsPage.php">Settings</a></li>
            <li class="last"><a href='phpQuerys/LogOut.php'>Log Out</a></li>
        </ul>

        <div class="background">
        </div>
        <div class="main">
            <h1>Alert Settings</h1>
            <hr size="3", color=black>
            <p class=center>Here you may change the alert distance and the time span for which the contact tracing will be performed.</p>
            <form action="phpQuerys/SettingUpdate.php" method="POST">
                <p class=center><label>Window   
                    <select name="window" id="window">
                        <option>One Week</option>
                        <option>Two Weeks</option>
                        <option>Three Weeks</option>
                        <option>Four Weeks</option>
                    </select>
                </label></p>
                <p class=center><label>Distance<input type="number" name=distance id=distance min=0 max=500 value=50></label></p>
                <p><input id=butSubmit type=submit value=Report>
                <input id=butCancel type=reset value=Cancel></p>
            </form>
        </div>

	</body>
</html>