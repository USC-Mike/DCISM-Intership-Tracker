<?php
session_start();
include(__DIR__ . '/.././config/db.php');; // Adjust path based on your folder structure
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if PDO connection is set
if (!isset($pdo)) {
    die('Database connection failed.');
}

// Handle login and registration requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Action based on the type of request
    if (isset($_POST['login'])) {
        // Login logic
        $role = $_POST['role'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Validate inputs
        if (empty($role) || empty($email) || empty($password)) {
            $_SESSION['error'] = 'All fields are required.';
            header('Location: ../../public/login.php');
            exit();
        }

        // Role-based table selection
        $table = $role === 'coordinator' ? 'coordinators' : 'students';

        // Query the database for the user
        $query = "SELECT * FROM $table WHERE email = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['full_name'] = $user['full_name'];

                // Redirect based on role
                if ($role === 'coordinator') {
                    header('Location: ../../public/coordinator/dashboard.php');
                } else {
                    header('Location: ../../public/student/dashboard.php');
                }
                exit();
            } else {
                $_SESSION['error'] = 'Incorrect password.';
            }
        } else {
            $_SESSION['error'] = 'No account found with this email.';
        }

        // Redirect back to login on failure
        header('Location: ../../public/login.php');
        exit();


    } elseif (isset($_POST['register'])) {
        // Register logic
        // Extract data from POST request
        $role = $_POST['role'] ?? '';
        $fullName = $_POST['full-name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm-password'] ?? '';

        // Validate inputs
        if (empty($role) || empty($fullName) || empty($email) || empty($password) || empty($confirmPassword)) {
            $_SESSION['error'] = 'All fields are required.';
            header('Location: ../../public/register.php');
            exit();
        }

        if ($password !== $confirmPassword) {
            $_SESSION['error'] = 'Passwords do not match.';
            header('Location: ../../public/register.php');
            exit();
        }

        // Check if the email already exists
        $table = $role === 'student' ? 'students' : 'coordinators';
        
        $stmt = $pdo->prepare("SELECT * FROM $table WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = 'An account with this email already exists.';
            header('Location: ../../public/register.php');
            exit();
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Select table based on role
        $table = ($role === 'coordinator') ? 'coordinators' : 'students';

        $stmt = $pdo->prepare("SELECT * FROM $table WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = 'An account with this email already exists.';
            header('Location: ../../public/register.php');
            exit();
        }

        // Insert new user into the database
        if ($role === 'student') {
            $yearLevel = $_POST['year-level'] ?? '';
            $course = $_POST['course'] ?? '';
            $insertQuery = "INSERT INTO students (full_name, email, password, year_level, course) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($insertQuery);

            if ($stmt->execute([$fullName, $email, $hashedPassword, $yearLevel, $course])) {
                $_SESSION['success'] = 'Registration successful. You can now login.';
                header('Location: ../../public/login.php');
                exit();
            } else {
                $_SESSION['error'] = 'Registration failed. Please try again.';
                header('Location: ../../public/register.php');
                exit();
            }

        } else {
            $department = $_POST['department'] ?? '';
            $insertQuery = "INSERT INTO coordinators (full_name, email, password, department) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($insertQuery);
            $stmt->execute([$fullName, $email, $hashedPassword, $department]);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = 'Registration successful. You can now login.';
                header('Location: ../../public/login.php');
                exit();
            } else {
                $_SESSION['error'] = 'Registration failed. Please try again.';
                header('Location: ../../public/register.php');
                exit();
            }

        }

    }
}

