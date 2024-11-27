<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Boxicons for icons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Tailwind CSS file -->
    <link rel="stylesheet" href="./assets/css/output.css">
    <title>Internship Tracker - Login</title>
</head>
<body class="bg-gray-100 font-lato">

    <!-- Main Content -->
    <main class="flex flex-col items-center justify-center min-h-screen p-6">
        <!-- Login Container -->
        <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-lg">
            <h1 class="text-2xl font-semibold text-center text-blue-500 mb-4">Internship Tracker Login</h1>
            
            <!-- Login Form -->
            <form class="space-y-4" method="POST" action=".../backend/controllers/authController.php">
                <!-- Display Errors -->
                <?php
                session_start();
                if (isset($_SESSION['error'])) {
                    echo '<div class="text-red-500 text-center">' . $_SESSION['error'] . '</div>';
                    unset($_SESSION['error']);
                }
                ?>

                <!-- Role Selector -->
                <div>
                    <label for="role" class="block text-gray-700">Role</label>
                    <select id="role" name="role" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-200">
                        <option value="student" selected>Student</option>
                        <option value="coordinator">Coordinator</option>
                    </select>
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-gray-700">Email Address</label>
                    <input id="email" name="email" type="email" placeholder="Enter your email" 
                        class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-200" required>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-gray-700">Password</label>
                    <input id="password" name="password" type="password" placeholder="Enter your password" 
                        class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-200" required>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember" type="checkbox" class="h-4 w-4 text-blue-500 focus:ring focus:ring-blue-200">
                    <label for="remember" class="ml-2 text-gray-700">Remember Me</label>
                </div>

                <!-- Login Button -->
                <div>
                    <button type="submit" 
                            class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:ring focus:ring-blue-300">
                        Login
                    </button>
                </div>

                <!-- Forgot Password -->
                <div class="text-center">
                    <a href="forgot_password.php" class="text-sm text-blue-500 hover:underline">Forgot Password?</a>
                </div>
            </form>

            
            <!-- Footer Section -->
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-700">
                    Don’t have an account? 
                    <a href="register.php" class="text-blue-500 hover:underline">Register here.</a>
                </p>
            </div>
        </div>

    </main>
</body>
</html>
