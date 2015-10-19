<?php

/*
 *
 * Done by Pablo Jimenez - pablo0910@outlook.es
 *
 */

$username = "";
$password = "";
$servername = ""; 
$dbname = "";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>