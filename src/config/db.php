<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$host = '127.0.0.1'; // your database host
$user = 'root';      // your database user
$password = '';      // your database password
$dbname = 'internship_tracker';     // your database name

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If the connection fails, show the error message
    die('Database connection failed: ' . $e->getMessage());
}
