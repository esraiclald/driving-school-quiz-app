<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sadesurucukursu_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error)
{
    error_log(mysqli_connect_error());
    header("Location: error.php");
    exit;
}

$conn->set_charset("utf8mb4");

?>