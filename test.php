<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = '127.0.0.1'; 
$user = 'root'; 
$password = ''; // Default password in XAMPP
$database = 'internship_tracker';

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected to MySQL successfully!";

