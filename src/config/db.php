<?php
$host = '127.0.0.1'; // your database host
$user = 'root';      // your database user
$password = '';      // your database password
$dbname = 'internship_tracker';     // your database name

$conn = new mysqli($host, $user, $password, $dbname, 3307);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

