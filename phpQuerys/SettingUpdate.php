<?php session_start();
    if (!is_string($_SESSION["username"])) {
        echo "Makes sure you are signed in before attempting to change settings.
        <p><a href=\"LogOut.php\">Return to Login</a></p>";
        die();
    }
    if (!is_string($_POST["window"]) or !is_string($_POST["distance"])) {
        echo "Make sure a valid selections for the settings is made.
        <p><a href=\"LogOut.php\">Return to Login</a></p>";
        die();
    }
    /*Uses the users name in the cookie identifer to prevent multipe 
    users on the same pc effecting each others settings*/
    $cookie_name = htmlspecialchars($_SESSION["username"])."Window";
    $cookie_value = htmlspecialchars($_POST["window"]);
    setcookie($cookie_name, $cookie_value, time() + (30*24*60*60), "/");

    $cookie_name = htmlspecialchars($_SESSION["username"])."Distance";
    $cookie_value = htmlspecialchars($_POST["distance"]);
    setcookie($cookie_name, $cookie_value, time() + (30*24*60*60), "/");

    header('Location: ../SettingsPage.php');
    exit();
?>