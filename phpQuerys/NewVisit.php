<?php session_start();
    if(!is_string($_POST["date"]) or !is_string($_POST["time"])) {
        echo "Makes sure a valid date and time are submitted.
        <p><a href=\"LogOut.php\">Return to Login</a></p>";
        die();
    }
    if (!is_numeric($_POST["duration"]) or !is_numeric($_POST["x"]) or !is_numeric($_POST["y"])) {
        echo "Makes sure a valid duration and location are selected.
        <p><a href=\"LogOut.php\">Return to Login</a></p>";
        die();
    }
    if (!is_numeric($_SESSION["id"])) {
        echo "Makes sure a you are signed in.
        <p><a href=\"LogOut.php\">Return to Login</a></p>";
        die();
    }

    //Collects the inputs from the user
    $date = htmlspecialchars($_POST["date"]);
    $time = htmlspecialchars($_POST["time"]);
    $duration = htmlspecialchars($_POST["duration"]);
    $x = htmlspecialchars($_POST["x"]);
    $y = htmlspecialchars($_POST["y"]);

    //Fixes an issue where if a border is clicked the resulting co-ordinate can be less than 0
    if ($x < 0) {
        $x = 0;
    }
    if ($y < 0) {
        $y = 0;
    }

    //Sets the inpormation for the user
    $servername = "localhost";
    $serverUsername = "ecm1417";
    $serverPassword = "WebDev2021";
    $dbname = "COVID19";

    $conn = new mysqli($servername, $serverUsername, $serverPassword, $dbname);
    if ($conn->connect_error) {
        die("The Database Couldnt be reached: " . $conn->connect_error);
    }

    //Adds the visit to the database
    $sql = "INSERT INTO Visits (date, time, duration, x, y, user)
            VALUES (?, ?, ?, ?, ?, ?)";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiiii", $date, $time, $duration, $x, $y, $_SESSION["id"]);
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();

        $_SESSION["status"] = "completed";
        header('Location: ../AddVisit.php');
        exit();

    } else {
        $stmt->close();
        $conn->close();
        
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

?>