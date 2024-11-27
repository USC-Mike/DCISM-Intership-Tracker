<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Boxicons for icons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Tailwind CSS file -->
    <link rel="stylesheet" href="./assets/css/output.css">
    <title>OJT Tracker - Login</title>
</head>
<body class="bg-gray-100 font-lato">

    <!-- Main Content -->
    <main class="flex flex-col items-center justify-center min-h-screen p-6">
        <!-- Login Container -->
        <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-lg">
            <h1 class="text-2xl font-semibold text-center text-blue-500 mb-4">Reset your Password</h1>
            
            <!-- Login Form -->
            <form class="space-y-4">

                <!-- Email Field -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium">Email Address</label>
                    <input type="email" id="email" placeholder="Enter your usc email" class="mt-2 w-full p-2 border rounded-md">
                </div>

                <!-- Password Field -->
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-medium">Password</label>
                    <input type="password" id="password" placeholder="Create a password" class="mt-2 w-full p-2 border rounded-md">
                </div>

                <!-- Confirm Password Field -->
                <div class="mb-4">
                    <label for="confirm-password" class="block text-gray-700 font-medium">Confirm Password</label>
                    <input type="password" id="confirm-password" placeholder="Re-enter your password" class="mt-2 w-full p-2 border rounded-md">
                </div>

                <!-- Login Button -->
                <div>
                    <button type="submit" 
                        class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:ring focus:ring-blue-300">
                        Login
                    </button>
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
