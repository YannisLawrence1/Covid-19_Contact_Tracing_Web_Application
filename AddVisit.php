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
        <title>Add Visit</title>
        <link rel="stylesheet" href="css/Main.css">

        <script>
            function RemoveMarker() {
                document.getElementById('markers').innerHTML=""
            }
            function Click() {
                //collects the x and the y values with there offset
                var x = event.offsetX ? (event.offsetX) : event.pageX - document.getElementById("imgContainer").offsetLeft;
                var y = event.offsetY ? (event.offsetY) : event.pageY - document.getElementById("imgContainer").offsetTop;
                
                //Sets the two hidden form values
                document.getElementById('x').value = x;
                document.getElementById('y').value = y;

                //creates the marker and places it on the map an offset is used to make the marker point be at the cursor
                var tag = "<img id=mark src=res/marker_black.png style=\"left:"+(x-8.5)+"px; top:"+(y-19)+"px;\">"
                document.getElementById('markers').innerHTML=tag;
            }
            function validInputs() {
                //Gathers all values from the form
                var date = document.forms["AddVisit"]["date"].value;
                var time = document.forms["AddVisit"]["time"].value;
                var duration = document.forms["AddVisit"]["duration"].value;
                var x = document.forms["AddVisit"]["x"].value;
                
                //Checks all fields have data entered
                if (date === "") {
                    alert("No date entered, please enter the date of the visit.");
                    return false;

                } else if(time === "") {
                    alert("No time entered, please enter the time of the visit.");
                    return false;

                } else if(duration === 0) {
                    alert("Duration cannot be 0 please enter a length of time.");
                    return false;

                } else if  (x === "") {
                    alert("No location selected, click the map to select.");
                    return false;
                }
            }
            function status() {
                //Checks if the user should be alert of a successfull submission
                <?php if (isset($_SESSION["status"])) { ?>
                    if ("<?php echo $_SESSION["status"]; ?>" === "completed") {
                        alert("New visit successfully added.");
                        <?php unset($_SESSION['status']); ?>;
                    }
                <?php } ?>
            }
        </script>
        <style>
            div.main p {
                margin: 5px;
            }
            div.main p input{
                font-family: 'Times New Roman';
                font-size: 20pt;
                text-align: center;
                background: transparent;
                border-style: solid black;
                margin: 0px;
                width: 210px;
                height: 40px;
                border: 2px solid black;
            }
            div.main p input#datetime{
                width: 213px;
            }
            div.main p input#button1{
                background-color: white;
                margin-top: 144px;
                border-radius: 9px;
                width: 218px;
                height: 45px;
            }
            div.main p input#button{
                background-color: white;
                border-radius: 9px;
                width: 218px;
                height: 45px;
            }
            div.main p input#x{
                visibility: hidden;
                width: 1px;
            }
            div.main p input#y{
                visibility: hidden;
                width: 1px;
            }
            div.imgContainer {
                float:right;
                position: relative;
            }
            div.main div.imgContainer img#mark {
                position: absolute;
                width: 25px;
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
            <h1>Add A Visit</h1>
            <hr size="3" color=black>

            <div class ="imgContainer">
                <img src="res/exeter.jpg" id="map" style="float:right" height="385" onClick=Click()></img>
                <p id=markers></p>
            </div>

            <form name="AddVisit" action="phpQuerys/NewVisit.php" method="POST" onsubmit="return validInputs()" novalidate>
                <p><input id=datetime type="date" name="date" max="<?php echo date("Y-m-d"); ?>"></p>
                <p><input id=datetime type="time" name="time"><input id='x' name='x' type="number" step=0.01></p>
                <p><input type="number" min=0 max=720 value=60 name="duration"><input id='y' name='y' type="number" step=0.01></p>
                <p><input id=button1 type=submit value=Add></p>
                <p><input id=button type=reset value=Cancel onClick=RemoveMarker()></p>
            </form>

        </div>
    </body>
</html>