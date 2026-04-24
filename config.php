<?php
// config.php
date_default_timezone_set('Asia/Kuala_Lumpur');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uitm_upk_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Set charset to UTF8 to handle special characters properly
$conn->set_charset("utf8");
?>
