<?php session_start();
    if(!is_string($_POST["name"]) or !is_string($_POST["surname"]) or !is_string($_POST["username"]) or !is_string($_POST["password"])) {
        echo "Make sure that a name, surname, username and password are entered into the form.
        <p><a href=\"../RegisterPage.php\">Return to Register</a></p>";
        die();
    }

    //Collects the inputs from the user
    $name = htmlspecialchars($_POST["name"]);
    $surname = htmlspecialchars($_POST["surname"]);
    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);

    //Sets the inpormation for the user
    $servername = "localhost";
    $serverUsername = "ecm1417";
    $serverPassword = "WebDev2021";
    $dbname = "COVID19";
    
    //Checks the password is longer than 8 characters and only contains valid characters
    if (strlen($password) < 8) {
        //Sends an error telling the user the password was too short
        $_SESSION["error"] = 1;
        header('Location: ../RegisterPage.php');
        exit();
    }
    if (preg_match('/[^A-Za-z0-9]/', $password)) {
        $_SESSION["error"] = 3;
        header('Location: ../RegisterPage.php');
        exit();
    }

    //Attempts to connect to database
    $conn = new mysqli($servername, $serverUsername, $serverPassword, $dbname);
    if ($conn->connect_error) {
        session_destroy();
        die("The Database Couldnt be reached: " . $conn->connect_error);
    }

    //Checks if there is a user with that username already
    $sql = "SELECT * FROM User WHERE username= ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        //Sends an error telling the user that someone already claimed that username
        $_SESSION["error"] = 2;
        header('Location: ../RegisterPage.php');
        exit();
    }

    //Applies hash to the password
    $options = [
        'cost' => 13,
    ];
    $password=password_hash($password, PASSWORD_BCRYPT, $options);

    //Adds the user to the database
    $sql = "INSERT INTO User (name, surname, username, password) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $surname, $username, $password);
    if ($stmt->execute()) {

        $sql = "SELECT * FROM User 
                WHERE username= ?
                AND password= ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $_SESSION["id"] =  $row["id"];
        }

        $_SESSION["name"] = $name;
        $_SESSION["surname"] = $surname;
        $_SESSION["username"] = $username;

        $stmt->close();
        $conn->close();

        header('Location: ../HomePage.php');
        exit();

    } else {
        $stmt->close();
        $conn->close();
        
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
?>