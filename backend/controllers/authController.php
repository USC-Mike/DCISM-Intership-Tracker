<?php
session_start();
require './config/db.php'; // Adjust path based on your folder structure

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
            header('Location: login.php');
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
                $_SESSION['user_role'] = $role;

                // Redirect based on role
                if ($role === 'coordinator') {
                    header('Location: coordinator_dashboard.php');
                } else {
                    header('Location: student_dashboard.php');
                }
                exit();
            } else {
                $_SESSION['error'] = 'Incorrect password.';
            }
        } else {
            $_SESSION['error'] = 'No account found with this email.';
        }

        // Redirect back to login on failure
        header('Location: login.php');
        exit();
    } elseif (isset($_POST['register'])) {
        // Register logic
        $fullname = $_POST['fullname'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? '';
        $yrlevel = $_POST['yrlevel'] ?? '';
        $course = $_POST['course'] ?? '';

        // Validate inputs
        if (empty($fullname) || empty($email) || empty($password) || empty($role) || empty($yrlevel) || empty($course)) {
            $_SESSION['error'] = 'All fields are required.';
            header('Location: register.php');
            exit();
        }

        // Check if the email already exists
        $table = $role === 'student' ? 'students' : 'coordinators';
        $sql = "SELECT * FROM $table WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = 'An account with this email already exists.';
            header('Location: register.php');
            exit();
        }

        // Register the user
        $isRegistered = registerUser($fullname, $email, $password, $role, $yrlevel, $course);

        if ($isRegistered) {
            $_SESSION['success'] = 'Registration successful! Please log in.';
            header('Location: login.php');
            exit();
        } else {
            $_SESSION['error'] = 'Registration failed. Please try again.';
            header('Location: register.php');
            exit();
        }
    }
}
?>
