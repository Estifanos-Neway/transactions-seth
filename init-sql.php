<?php
require "./config.php";
require "./src/php/functions.php";

// Create connection
$conn = new mysqli($serverName, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>