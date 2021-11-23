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
        <title>Report Page</title>
        <link rel="stylesheet" href="css/Main.css">
        <script>
            function validInputs() {
                //Gathers all values from the form
                var date = document.forms["ReportInfection"]["date"].value;
                var time = document.forms["ReportInfection"]["time"].value;
                
                //Checks all fields have data entered
                if (date === "") {
                    alert("No date entered, please enter the date of the infection.");
                    return false;

                } else if(time === "") {
                    alert("No time entered, please enter the time of the infection.");
                    return false;
                }
            }
            function status() {
                //Checks if the user should be alert of a successfull submission
                <?php if (isset($_SESSION["status"])) { ?>
                    if ("<?php echo $_SESSION["status"]; ?>" === "completed") {
                        alert("Infection reported successfully.");
                        <?php unset($_SESSION['status']); ?>;
                    }
                <?php } ?>
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
            div.main form{
                width: 100%;
                margin-top: 90px;
            }
            div.main p.center{
                text-align: center;
            }
            div.main p input#datetime{
                width: 400px;
                height: 45px;
    
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
            div.main p input#button1{
                background-color: white;
                border-radius: 9px;
                width: 218px;
                height: 45px;
                margin-left: 0%;
                margin-right: calc(100% - 443px);
            }
            div.main p input#button{
                background-color: white;
                border-radius: 9px;
                width: 218px;
                height: 45px;
            }
        </style>
	</head>
	<body onload="status()">
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
            <h1>Report an Infection</h1>
            <hr size="3", color=black>
            <p class=center>Please report the date and time when you where tested positive for COVID-19</p>
            <form name="ReportInfection" action="phpQuerys/NewInfection.php" method="POST" onsubmit="return validInputs()">
                <p class=center><input id=datetime type="date" name="date" max="<?php echo date("Y-m-d"); ?>"></p>
                <p class=center><input id=datetime type="time" name="time"></p>
                <p><input id=button1 type=submit value=Report>
                <input id=button type=reset value=Cancel></p>
            </form>
        </div>

	</body>
</html>