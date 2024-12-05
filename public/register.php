<?php
require_once '../src/controllers/authcontroller.php';

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

            <!-- Notification Section -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline"><?php echo $_SESSION['error']; ?></span>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php elseif (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline"><?php echo $_SESSION['success']; ?></span>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <!-- Registration Form -->
            <form method="POST"  action="../src/controllers/authcontroller.php">
                
            <!-- Role Selector -->
            <div class="mb-4">
                <label for="role" class="block text-gray-700 font-medium">Role</label>
                <select id="role" name="role" class="mt-2 w-full p-2 border rounded-md">
                    <option value="student" selected>Student</option>
                    <option value="coordinator">Coordinator</option>
                </select>
            </div>

                <!-- Common Fields -->
                <div id="common-fields">
                    <div class="mb-4">
                        <label for="full-name" class="block text-gray-700 font-medium">Full Name</label>
                        <input type="text" id="full-name" name="full-name" placeholder="Enter your full name" class="mt-2 w-full p-2 border rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-medium">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" class="mt-2 w-full p-2 border rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 font-medium">Password</label>
                        <input type="password" id="password" name="password" placeholder="Create a password" class="mt-2 w-full p-2 border rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label for="confirm-password" class="block text-gray-700 font-medium">Confirm Password</label>
                        <input type="password" id="confirm-password" name="confirm-password" placeholder="Re-enter your password" class="mt-2 w-full p-2 border rounded-md" required>
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
                <button type="submit" name="register" class="w-full bg-blue-500 text-white p-3 rounded-md font-medium hover:bg-blue-600">Register</button>
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
