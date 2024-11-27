<?php
session_start();
include(__DIR__ . '/.././src/config/db.php'); // Adjust path based on your folder structure

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract data from POST request
    $role = $_POST['role'] ?? '';
    $fullName = $_POST['full-name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm-password'] ?? '';

    // Validate inputs
    if (empty($role) || empty($fullName) || empty($email) || empty($password) || empty($confirmPassword)) {
        $_SESSION['error'] = 'All fields are required.';
        header('Location: register.php');
        exit();
    }

    if ($password !== $confirmPassword) {
        $_SESSION['error'] = 'Passwords do not match.';
        header('Location: register.php');
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Select table based on role
    $table = ($role === 'coordinator') ? 'coordinators' : 'students';

    // Check if user already exists
    $checkQuery = "SELECT * FROM $table WHERE email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $_SESSION['error'] = 'Account already exists with this email.';
        header('Location: register.php');
        exit();
    }

    // Insert new user into the database
    if ($role === 'student') {
        $yearLevel = $_POST['year-level'] ?? '';
        $course = $_POST['course'] ?? '';
        $insertQuery = "INSERT INTO students (full_name, email, password, year_level, course) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param('sssss', $fullName, $email, $hashedPassword, $yearLevel, $course);
    } else {
        $department = $_POST['department'] ?? '';
        $insertQuery = "INSERT INTO coordinators (full_name, email, password, department) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param('ssss', $fullName, $email, $hashedPassword, $department);
    }

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Registration successful. You can now login.';
        header('Location: login.php');
    } else {
        $_SESSION['error'] = 'Registration failed. Please try again.';
        header('Location: register.php');
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Boxicons for icons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Tailwind CSS file -->
    <link rel="stylesheet" href="./assets/css/output.css">
    <title>OJT Tracker Registration</title>
</head>
<body class="bg-gray-100 font-lato">

    <!-- Main Content -->
    <main class="flex justify-center items-center min-h-screen">
        <!-- Registration Container -->
        <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-lg">
            <h1 class="text-2xl font-semibold text-center text-blue-500 mb-4">Create an Account</h1>

            <!-- Role Selector -->
            <div class="mb-4">
                <label for="role" class="block text-gray-700 font-medium">Role</label>
                <select id="role" name="role" class="mt-2 w-full p-2 border rounded-md">
                    <option value="student" selected>Student</option>
                    <option value="coordinator">Coordinator</option>
                </select>
            </div>

            <!-- Registration Form -->
            <form method="POST">
                <!-- Common Fields -->
                <div id="common-fields">
                    <div class="mb-4">
                        <label for="full-name" class="block text-gray-700 font-medium">Full Name</label>
                        <input type="text" id="full-name" name="full-name" placeholder="Enter your full name" class="mt-2 w-full p-2 border rounded-md">
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-medium">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" class="mt-2 w-full p-2 border rounded-md">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 font-medium">Password</label>
                        <input type="password" id="password" name="password" placeholder="Create a password" class="mt-2 w-full p-2 border rounded-md">
                    </div>
                    <div class="mb-4">
                        <label for="confirm-password" class="block text-gray-700 font-medium">Confirm Password</label>
                        <input type="password" id="confirm-password" name="confirm-password" placeholder="Re-enter your password" class="mt-2 w-full p-2 border rounded-md">
                    </div>
                </div>

                <!-- Student Fields -->
                <div id="student-fields" class="hidden">
                    <div class="mb-4">
                        <label for="year-level" class="block text-gray-700 font-medium">Year Level</label>
                        <select id="year-level" name="year-level" class="mt-2 w-full p-2 border rounded-md">
                            <option>3rd Year</option>
                            <option>4th Year</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="course" class="block text-gray-700 font-medium">Course</label>
                        <select id="course" name="course" class="mt-2 w-full p-2 border rounded-md">
                            <option>Computer Science</option>
                            <option>Information Technology</option>
                            <option>Information Systems</option>
                        </select>
                    </div>
                </div>

                <!-- Coordinator Fields -->
                <div id="coordinator-fields" class="hidden">
                    <div class="mb-4">
                        <label for="department" class="block text-gray-700 font-medium">Department</label>
                        <select id="department" name="department" class="mt-2 w-full p-2 border rounded-md">
                            <option>Department of Computer, Information Sciences, and Mathematics</option>
                        </select>
                    </div>
                </div>

                <!-- Register Button -->
                <button type="submit" class="w-full bg-blue-500 text-white p-3 rounded-md font-medium hover:bg-blue-600">Register</button>
            </form>

            <!-- Footer Section -->
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-700">
                    Already have an account?
                    <a href="login.php" class="text-blue-500 hover:underline">Login here.</a>
                </p>
            </div>
        </div>
    </main>

    <script>
        const roleSelector = document.getElementById('role');
        const studentFields = document.getElementById('student-fields');
        const coordinatorFields = document.getElementById('coordinator-fields');

        roleSelector.addEventListener('change', () => {
            const role = roleSelector.value;
            if (role === 'student') {
                studentFields.classList.remove('hidden');
                coordinatorFields.classList.add('hidden');
            } else {
                studentFields.classList.add('hidden');
                coordinatorFields.classList.remove('hidden');
            }
        });

        // Trigger change event to show the appropriate fields on page load
        roleSelector.dispatchEvent(new Event('change'));
    </script>
</body>
</html>
