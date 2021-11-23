<?php session_start();
    if (!is_numeric($_SESSION["id"])) {
        echo "Cannot view this page unless you are signed in!
        <p><a href=\"LoginPage.php\">Return to Login</a></p>";
        die();
    }

    //Sets up the initial curl data
    if (($handle = curl_init()) === false) {
        echo "Curl-Error: " .curl_error($handle);
    } else {
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_FAILONERROR, true);
    }

    //The information required by the server
    $servername = "localhost";
    $serverUsername = "ecm1417";
    $serverPassword = "WebDev2021";
    $dbname = "COVID19";

    //Initialises the connection the server
    $conn = new mysqli($servername, $serverUsername, $serverPassword, $dbname);
    if ($conn->connect_error) {
        http_response_code(500);
        die("Connection failed: " . $conn->connect_error);
    }

    //performs the sql query to get all of the locations visited by the user
    $sql = "SELECT * FROM Visits 
            WHERE user= ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION["id"]);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            if (isset($row["x"]) && isset($row["y"]) && isset($row["date"]) && isset($row["time"]) && isset($row["duration"])) {
                //Takes each row and sends it to the report server
                $url="http://ml-lab-7b3a1aae-e63e-46ec-90c4-4e430b434198.ukwest.cloudapp.azure.com:60999/ctracker/report.php";
                curl_setopt($handle, CURLOPT_URL, $url);
                curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-type: application/json"));

                //Creates the json file, the date and time are treated as strings as json has no date format
                $post = array("x"=>$row["x"], "y"=>$row["y"], "date"=>$row["date"], "time"=>$row["time"], "duration"=>$row["duration"]);
                
                //Sends the curl request and reports any errors
                curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($post));

                if (curl_exec($handle)) {
                    echo "Error: " .curl_error($handle);
                } else {
                    continue;
                }
            }

        }
    }
    $stmt->close();
    $conn->close();
    
    header('Location: ../ReportPage.php');
    exit();
?>
