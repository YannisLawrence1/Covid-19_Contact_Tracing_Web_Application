<?php session_start();
    if(!is_string($_POST["date"]) or !is_string($_POST["time"]) or !is_numeric($_SESSION["id"])) {
        echo "Makes sure you are signed in and a post data and time where submitted.
        <p><a href=\"LogOut.php\">Return to Login</a></p>";
        die();
    }
    
    //Collects the inputs from the user
    $date = htmlspecialchars($_POST["date"]);
    $time = htmlspecialchars($_POST["time"]);

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
    $sql = "INSERT INTO Infections (date, time, user)
            VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $date, $time, $_SESSION["id"]);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();

        $_SESSION["status"] = "completed";
        header('Location: CurlReport.php');
        exit();

    } else {
        $stmt->close();
        $conn->close();

        echo "Error: " . $sql . "<br>" . $conn->error;
    }
?>