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