<<<<<<< HEAD
<?php
$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "portfolio_db";

// Create connection
$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

// Check connection
if ($conn){echo"you are connected";}
else{echo"error";}?>
=======
<?php
// connect_db.php — Secure DB connection using OOP mysqli

$db_server = "localhost";
$db_user   = "root";
$db_pass   = "";
$db_name   = "portfolio_db";

$conn = new mysqli($db_server, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    // Never expose DB errors to the browser in production
    error_log("DB Connection failed: " . $conn->connect_error);
    die(json_encode(["error" => "Database unavailable. Please try again later."]));
}

$conn->set_charset("utf8mb4");
?>
>>>>>>> 0de22fa (First git commit)
