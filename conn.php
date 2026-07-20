<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sadesurucukursu_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error)
{
    die("Veritabanına bağlanılamadı.");
}

$conn->set_charset("utf8mb4");

?>