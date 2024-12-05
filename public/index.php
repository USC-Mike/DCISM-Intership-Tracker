<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Boxicons for icons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Tailwind CSS file -->
    <link rel="stylesheet" href="./assets/css/output.css">
    <title>Internship Tracker</title>
</head>
<body class="bg-gray-100 font-sans">
    <!-- Navbar -->
    <nav class="bg-white shadow">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="#" class="text-2xl font-bold text-blue-500">Internship Tracker</a>
            <div class="space-x-4">
                <a href="#features" class="text-gray-600 hover:text-blue-500">Features</a>
                <a href="#about" class="text-gray-600 hover:text-blue-500">About</a>
                <a href="#contact" class="text-gray-600 hover:text-blue-500">Contact</a>
                <a href="./login.php" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Login</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-blue-500 text-white py-20">
        <div class="container mx-auto text-center">
            <h1 class="text-4xl font-bold mb-4">Simplify Internship Tracking</h1>
            <p class="text-lg mb-6">Manage your internships with ease. Track, organize, and succeed with the Internship Tracker app.</p>
            <a href="./register.php" class="px-6 py-3 bg-white text-blue-500 font-semibold rounded-md hover:bg-gray-100">Get Started</a>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-100">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-semibold text-gray-800 mb-6">Features</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Feature 1 -->
                <div class="p-6 bg-white rounded-lg shadow">
                    <i class="bx bx-task text-blue-500 text-4xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700">Internship Management</h3>
                    <p class="text-gray-600 mt-2">Track your tasks, progress, and deadlines effortlessly.</p>
                </div>
                <!-- Feature 2 -->
                <div class="p-6 bg-white rounded-lg shadow">
                    <i class="bx bx-message-dots text-blue-500 text-4xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700">Real-Time Updates</h3>
                    <p class="text-gray-600 mt-2">Stay informed with live updates and notifications.</p>
                </div>
                <!-- Feature 3 -->
                <div class="p-6 bg-white rounded-lg shadow">
                    <i class="bx bx-chart text-blue-500 text-4xl mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700">Performance Insights</h3>
                    <p class="text-gray-600 mt-2">Analyze your progress and stay on track for success.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-semibold text-gray-800 mb-6">About Internship Tracker</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">The Internship Tracker is designed to help students, coordinators, and mentors manage internship processes efficiently. From task assignments to progress tracking, the app ensures seamless communication and organization for everyone involved.</p>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-gray-100">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-semibold text-gray-800 mb-6">Contact Us</h2>
            <p class="text-gray-600 mb-4">Have questions? Reach out to us!</p>
            <a href="mailto:support@internshiptracker.com" class="px-6 py-3 bg-blue-500 text-white rounded-md hover:bg-blue-600">Email Us</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 Internship Tracker. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
