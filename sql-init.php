<?php
error_reporting(E_ERROR | E_PARSE);
// importing modules
require "./config.php";
require "./src/php/functions.php";

// Create connection
try {
    $conn = new mysqli($sqlInfo["serverName"], $sqlInfo["username"], $sqlInfo["password"], $sqlInfo["database"]);
    if ($conn->connect_error) {
        echo ">[error] Could't connect to the server. [1]";
        echo "<br>";
        echo $conn->error;
        echo "<br>";
    }
} catch (\Throwable $th) {
    echo ">[error] Could't connect to the server. [2]";
    echo "<br>";
    echo $th;
    echo "<br>";
    exit;
}

// Create database
$sql = "CREATE DATABASE ".$sqlInfo["database"];
try {
    if ($conn->query($sql) === TRUE) {
        echo ">Database '".$sqlInfo["database"]."' created successfully";
        echo "<br>";
    } else {
        echo ">[error] Could't create the database '".$sqlInfo["database"]."'. [1]";
        echo "<br>";
        echo $conn->error;
        echo "<br>";
    }
} catch (\Throwable $th) {
        echo ">[error] Could't create the database '".$sqlInfo["database"]."'. [2]";
        echo "<br>";
        echo $th;
        echo "<br>";
}

// sql to create table
$sql = "CREATE TABLE ".$sqlInfo["table"]." (
    id INT NOT NULL AUTO_INCREMENT,
    time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    transaction_id VARCHAR(300) DEFAULT NULL,
    full_name VARCHAR(100) DEFAULT NULL,
    mt4_account VARCHAR(100) DEFAULT NULL,
    amount INT NOT NULL DEFAULT 0,
    email VARCHAR(100) DEFAULT NULL,
    country VARCHAR(100) DEFAULT NULL,
    currency VARCHAR(10) DEFAULT NULL,
    success INT NOT NULL DEFAULT 0,
    PRIMARY KEY (id));";
    try { 
        if ($conn->query($sql) === TRUE) {
            echo ">Table '".$sqlInfo["table"]."' created successfully";
        } else {
            echo ">[error] Could't create the table '".$sqlInfo["table"]."'. [1]";
            echo "<br>";
            echo $conn->error;
            echo "<br>";
        }
    } catch (\Throwable $th) {
        echo ">[error] Could't create the table '".$sqlInfo["table"]."'. [2]";
        echo "<br>";
        echo $th;
        echo "<br>";
    }
try {
    $conn->close();
} catch (\Throwable $th) {}

?>