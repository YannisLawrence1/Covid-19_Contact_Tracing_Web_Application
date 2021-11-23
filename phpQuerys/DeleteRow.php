<?php session_start();
    //Only accepts post requests
    if($_SERVER['REQUEST_METHOD'] != "POST") {
        http_response_code(405);
        die();
    }

    //uses id from post request as variable
    $id = htmlspecialchars($_POST["id"]);

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

    $sql = "DELETE FROM Visits WHERE id= ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        //Notifes the device that the request was successfull
        $stmt->close();
        $conn->close();

        http_response_code(200);
        exit();

    } else {
        $stmt->close();
        $conn->close();
        
        http_response_code(500);
        die("Error deleting record: " . $conn->error);
    }
    $conn->close();
?>