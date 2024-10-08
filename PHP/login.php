<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // retrieve data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // set loggIn
    $_SESSION["loggIn"] = false;

    // starting session + variables
    $_SESSION["username"] = $username;

    // database connect
    $host = "sql109.infinityfree.com";
    $dbusername = "if0_35864125";
    $dbpassword = "superThoth";
    $dbname = "if0_35864125_auth";

    $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the table exists
    $checkTableQuery = "SHOW TABLES LIKE '$username'";
    $checkTableResult = $conn->query($checkTableQuery);

    if ($checkTableResult->num_rows > 0) {
        // Table exists, check credentials
        $query = "SELECT * FROM login WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($query);

         if ($result->num_rows > 0) {
            // Credentials match, log in
            $user = $result->fetch_assoc();
            $_SESSION["loggIn"] = true;
            $_SESSION["username"] = $user['username'];
            $_SESSION["fullName"] = $user['fullName'];  
            $_SESSION["email"] = $user['email'];       
            header('Location: /library.html');
            exit();
            // Perform additional actions for successful login
        } else {
            // Credentials don't match
            echo '<script type="text/javascript">alert("Incorrect username or password"); window.location.href = "/login.html"</script>';

        }
    } else {
        // Table doesn't exist
        echo '<script type="text/javascript">alert("Username not found");</script>';
    }

    $conn->close();
}

if (isset($_POST['logoutButton'])) {
    logOut();
}

function logOut()
{
    unset($_SESSION['username']);
    header("Location: login.html");
    echo '<script>console.log("Logged out Successfully");</script>';
    die;
}
