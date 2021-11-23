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
        <title>Overview</title>
        <link rel="stylesheet" href="css/Main.css">

        <script>
            function DeleteRow(element) {
                var rowNum = element.parentNode.parentNode.rowIndex;
                var deleteRequest = new XMLHttpRequest();
                var location = "phpQuerys/DeleteRow.php";

                //Sends the id of the row to be deleted
                var id =  document.getElementById("overviewTable").rows[rowNum].cells[6].innerHTML;

                deleteRequest.onreadystatechange = function() {
                    if(deleteRequest.readyState == 4 && deleteRequest.status == 200) {
                        var return_data = deleteRequest.responseText;
                    }
                }

                //Sends the post request to the server to delete the vist from the sql database
                deleteRequest.open("POST", location, true);
                deleteRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                deleteRequest.send("id="+id);

                //Deletes the row from the table displayed to the user
                document.getElementById("overviewTable").deleteRow(rowNum);
            }

            //Add AJAX to remove
        </script>
        <style>
            div.main table{
               margin-top: 40px;
               margin-left: 0;
           }
           div.main table tr td{
               border: none;
               font-family: 'Times New Roman';
               font-size: 20pt;
               font-weight: normal;
               padding-bottom: 7px;
           }
           div.main table tr#first th{
               margin-top: 40px;
               border: none;
               font-family: 'Times New Roman';
               font-size: 20pt;
               font-weight: bold;
               padding-bottom: 7px;
           }
           div.main table tr th#date{
               text-align: right;
           }
           div.main table tr td#date{
               text-align: right;
           }
           div.main table tr td.hidden {
               visibility: hidden;
               width: 0px;
               font-size: 1pt;
           }
       </style>
	</head>
	<body>
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
        <div class="main" id="table">

            <table style="width:100%" id="overviewTable">
                <tr id="first">
                        <th id="date" style="width:20%">Date</th>
                        <th style="width:25%">Time</th>
                        <th style="width:25%">Duration</th>
                        <th style="width:10%">X</th>
                        <th style="width:10%">Y</th>
                        <th></th>
                        <th></th>
                </tr>

                <?php
                    $id = $_SESSION["id"];

                    //Information required to connect to the sql database
                    $servername = "localhost";
                    $serverUsername = "ecm1417";
                    $serverPassword = "WebDev2021";
                    $dbname = "COVID19";

                    //Connects to the database
                    $conn = new mysqli($servername, $serverUsername, $serverPassword, $dbname);
                    if ($conn->connect_error) {
                        die("The Database Couldnt be reached: " . $conn->connect_error);
                    }
                    
                    //SQL request for all locations submitted by the user
                    $sql = "SELECT * FROM Visits WHERE user=$id";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        //Creates rows for the table with the information from the sql request
                        while($row = $result->fetch_assoc()) {
                            echo "<tr> 
                                <td id=\"date\">".date_format(date_create($row["date"]), "d/m/Y")."</td>
                                <td>".date_format(date_create($row["time"]), 'G:i')."</td>
                                <td>".$row["duration"]."</td>
                                <td>".$row["x"]."</td>
                                <td>".$row["y"]."</td>
                                <td><img src=\"res/cross.png\" height=\"28px\" onclick=DeleteRow(this)></td>
                                <td class=hidden>".$row["id"]."</td>
                            </tr>";
                        }
                    }
                ?>
            </table>

        </div>
	</body>
</html>