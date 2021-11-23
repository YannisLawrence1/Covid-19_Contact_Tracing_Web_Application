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
        <title>Home Page</title>
        <link rel="stylesheet" href="css/Main.css">

        <style>
            div.imgContainer {
                    float:right;
                    position: relative;
                }
            div.main div.imgContainer img.mark {
                position: absolute;
                width: 25px;
            }
        </style>
	</head>
	<body>
		<h1 class="title">COVID - 19 Contact Tracing</h1>

        <ul class="sidemenu">
            <li><a href="HomePage.php">Home</a></li>
            <li><a href="/Overview.php">Overview</a></li>
            <li><a href="AddVisit.php">Add Visit</a></li>
            <li><a href="ReportPage.php">Report</a></li>
            <li><a href="SettingsPage.php">Settings</a></li>
            <li class="last"><a href='phpQuerys/LogOut.php'>Log Out</a></li>
        </ul>
        
        <div class="background">
        </div>

        <div class="main">
            <h1>Status</h1>
            <hr size="3" color=black>

            <div class ="imgContainer">
                <img src="res/exeter.jpg" id="map" style="float:right" height="385" usemap=#infection>

                <?php
                    if (($handle = curl_init()) === false) {
                        echo "Curl-Error: " .curl_error($handle);
                    } else {
                        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($handle, CURLOPT_FAILONERROR, true);
                    }

                    $locations = [];
                    //Gets the cookie values needed to see if a user was in range of a contaminated point
                    if (isset($_COOKIE[$_SESSION["username"]."Window"])) {
                        //Checks the cookies containing the users settings
                        $window = $_COOKIE[$_SESSION["username"]."Window"];
                        if ($window === "Two Weeks") {
                            $days = 14;
                        } elseif ($window === "Three Weeks") {
                            $days = 21;
                        } elseif ($window === "Four Weeks") {
                            $days = 28;
                        } else {
                            $days = 7;
                        }
                    } else {
                        $days = 7; 
                    }

                    if (isset($_COOKIE[$_SESSION["username"]."Distance"])) {
                        $distance = $_COOKIE[$_SESSION["username"]."Distance"];
                    } else {
                        $distance = 50;
                    }
                    
                    //Calculates the date of the furthest back to get visits from
                    $furthestDate = date("Y-m-d", strtotime("-".$days." days"));
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
                    $sql = "SELECT * FROM Visits WHERE user= ? AND date> ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("is", $id, $furthestDate);

                    //Currently on local host
                    $url = "http://ml-lab-7b3a1aae-e63e-46ec-90c4-4e430b434198.ukwest.cloudapp.azure.com:60999/ctracker/infections.php?ts=".$days;

                    //prepares and send the get request to the server
                    curl_setopt($handle, CURLOPT_URL, $url);
                    curl_setopt($handle, CURLOPT_HTTPGET, true);
                    curl_setopt($handle, CURLOPT_HEADER, false);

                    $funcCounter = 0;

                    if (($output = curl_exec($handle))!==false) {
                        $locations = json_decode($output, true);
                        
                        //For each location sees if the user has visited within there set distance of that location
                        foreach($locations as $location) {
                            //Sets the co-ordinate of the points
                            $xp = $location["x"];
                            $yp = $location["y"];
                            $funcCounter += 1;

                            //Close is used to detect if a red marker has already been placed for a point
                            $close = false;
                            
                            //executes the SQL request to get all locations visted by the user in there chosen timeframe
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while($row = $result->fetch_assoc()) {
                                //Sets the co-ordinates for the location visited by the user
                                $xc = $row["x"];
                                $yc = $row["y"];

                                //calculates the distance using using Pythagorean theorem
                                $pointDistance = sqrt(pow(($xp-$xc), 2) + pow(($yp-$yc), 2));

                                if ($pointDistance < $distance) {
                                    //Calculates if the point is in range of a location visited by the user
                                    echo "<img class=mark src=res/marker_red.png onclick='information".$funcCounter."()' style=\"left:".($xp-8.5)."px; top:".($yp-19)."px;\">";
                                    $close = true;
                                    break;
                                }
                                
                           
                            }
                            //If not found to be in range of a visited location places a black marker
                            //Prevents markers that are not inside the range of the map from being placed, fixing a bug where people with other map formatts could break mine
                            if ($close === false and ($location["x"]-8.5) < 383 and $location["y"]-19 < 388) {
                                echo "<img class=mark src=res/marker_black.png onclick='information".$funcCounter."()' style=\"left:".($location["x"]-8.5)."px; top:".($location["y"]-19)."px;\">";
                            }

                            //Creates a function that contains the information to display to the user when a marker is clicked
                            echo "<script>
                                function information".$funcCounter."() {
                                    var message = 'Information On Visit \\n\\n';
                                    message += 'X Co-ordinate: ".$location["x"]."\\n';
                                    message += 'Y Co-ordinate: ".$location["y"]."\\n';
                                    message += 'Date & Time:   ".date_format(date_create($location["date"]), "d/m/Y")." ".date_format(date_create($location["time"]), 'G:i')."\\n';
                                    message += 'Duartion:          ".$location["duration"]." minutes ';
                                    alert(message);
                                }
                            </script>";
                        }
                    } else {
                        echo "<p>Couldn't connect to the map so no markers can be displayed!<p>";
                    }

                    $stmt->close();
                    $conn->close();
                ?>
            </div>

            <p>Hi <?php echo $_SESSION["name"];?>, you might have had a connection to an infected 
                person at the location shown in red.</p>
            <p id="end">Click on the marker to see details about the infection.</p>

        </div>

	</body>
</html>