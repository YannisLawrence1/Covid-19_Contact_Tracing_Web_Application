<?php session_start();

//Checks if the user accessed the page without submitting the form
if (!is_string($_POST["username"]) or !is_string($_POST["password"])) {
    echo "Please make sure a string for username and a string for passwords is sent with a POST request.
    <p><a href=\"LogOut.php\">Return to Login</a></p>";
    die();
}

//The username and password entered by the user into the form
$username = htmlspecialchars($_POST["username"]);
$password = htmlspecialchars($_POST["password"]);

//The information to connect to the server
$servername = "localhost";
$serverUsername = "ecm1417";
$serverPassword = "WebDev2021";
$dbname = "COVID19";

//Makes the initial connection to the server
$conn = new mysqli($servername, $serverUsername, $serverPassword, $dbname);
if ($conn->connect_error) {
    session_destroy();
    die("The Database Couldnt be reached: " . $conn->connect_error);
}

//Changed to use Prepared statements to prevent an SQL injection
$sql = "SELECT * FROM User 
    WHERE username= ? ";

//Makes the connection and prepares the data
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row["password"])) {
        //Sets up the session variables to remember the users information
        $_SESSION["id"] =  $row["id"];
        $_SESSION["name"] = $row["name"];
        $_SESSION["surname"] = $row["surname"];
        $_SESSION["username"] = $row["username"];

        $stmt->close();
        $conn->close();

        header('Location: ../HomePage.php');
        exit();

    } else {
        //if no data is found the user is returened to the login page with an error message
        $_SESSION["error"] = true;

        $stmt->close();
        $conn->close();

        header('Location: ../LoginPage.php');
        exit();
    }
} else {
    //if no data is found the user is returened to the login page with an error message
    $_SESSION["error"] = true;

    $stmt->close();
    $conn->close();

    header('Location: ../LoginPage.php');
    exit();
}
?>