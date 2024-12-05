<?php
require_once '../../src/controllers/coordinatorcontroller.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../public/login.php');
    exit();
}

// Display full name, defaulting to "Guest" if not set
$fullName = $_SESSION['full_name'] ?? 'Guest';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Boxicons for icons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Tailwind CSS file -->
    <link rel="stylesheet" href="../assets/css/output.css">
    <title>Coordinator Dashboard</title>

</head>
<body class="bg-gray-100 font-lato">

    <!-- Header -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="flex items-center justify-between px-6 py-4">
            <div class="flex items-center gap-4">
                <img src="../assets/images/dcism-logo.png" alt="Logo" class="h-10 w-auto">
                <h1 class="text-xl font-semibold text-blue-500">Internship Tracker</h1>
            </div>
            <div class="flex items-center gap-6">
                <!-- Bell icon with link to notifications page -->
                <div class="relative">
                    <a href="notifications.php">
                        <i class="bx bx-bell text-2xl text-gray-700 cursor-pointer"></i>
                    </a>
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">5</span>
                </div>
                <!-- Profile Icon with Dropdown -->
                <div class="relative group">
                    <!-- Profile Icon -->
                    <a href="profile.php">
                    <i class="bx bx-user-circle text-3xl text-gray-700 cursor-pointer"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex flex-col md:flex-row p-6">
        <!-- Sidebar -->
        <aside class="bg-white w-full md:w-1/4 lg:w-1/5 h-auto md:h-full p-4 rounded-lg shadow-lg">
            <ul class="space-y-4">
                <li>
                    <a href="dashboard.php" class="flex items-center gap-4 text-blue-500 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-home text-lg"></i> Home
                    </a>
                </li>
                <li>
                    <a href="student_management.php" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-group text-lg"></i> Student Management
                    </a>
                </li>
                <li>
                    <a href="document_management.php" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-file text-lg"></i> Document Management
                    </a>
                </li>
                <li>
                    <a href="report_management.php" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-task text-lg"></i> Report Management
                    </a>
                </li>
                <li>
                    <a href="profile.php" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-user text-lg"></i> Profile
                    </a>
                </li>
                <li>
                    <a href="logout.php" class="flex items-center gap-4 text-red-500 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-log-out text-lg"></i> Logout
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Dashboard Content -->
        <section class="flex-1 md:ml-6 space-y-6">
            <!-- Summary Cards -->

            <h1 class="text-2xl font-semibold text-blue-700">Welcome back, <?php echo htmlspecialchars($fullName); ?>!</h1>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-lg shadow-lg flex items-center gap-4">
        <i class="bx bx-group text-blue-500 text-6xl"></i>
        <div>
            <h3 class="text-xl font-semibold text-gray-700">Active Students</h3>
            <p class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($activeStudents); ?></p>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-lg flex items-center gap-4">
        <i class="bx bx-file text-yellow-500 text-6xl"></i>
        <div>
            <h3 class="text-xl font-semibold text-gray-700">Pending Documents</h3>
            <p class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($pendingDocuments); ?></p>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-lg flex items-center gap-4">
        <i class="bx bx-calendar text-red-500 text-6xl"></i>
        <div>
            <h3 class="text-xl font-semibold text-gray-700">Pending Reports</h3>
            <p class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($pendingReports); ?></p>
        </div>
    </div>
</div>


            <!-- Quick Links -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Quick Links</h2>
                <div class="flex gap-4 mt-4">
                    <a href="document_management.php" class="flex items-center justify-center bg-blue-500 text-white rounded-lg px-4 py-2 w-1/3">
                        Approve Pending Documents
                    </a>
                    <a href="report_management.php" class="flex items-center justify-center bg-green-500 text-white rounded-lg px-4 py-2 w-1/3">
                        Review Reports
                    </a>
                    <a href="student_management.php" class="flex items-center justify-center bg-yellow-500 text-white rounded-lg px-4 py-2 w-1/3">
                        Review Active Students
                    </a>
                </div>
            </div>

        </section>
    </main>

</body>
</html>
