<?php
require_once '../../src/controllers/studentcontroller.php';

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../public/login.php');
    exit();
}

// Display full name, defaulting to "Guest" if not set
$fullName = $_SESSION['full_name'] ?? 'Guest';

// Get the user ID
// $userId = $_SESSION['user_id'];
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
    <title>Student - Dashboard</title>
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
                    <i class="bx bx-user-circle text-3xl text-gray-700 cursor-pointer"></i>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex flex-col md:flex-row p-6">
        <!-- Sidebar -->
        <aside class="bg-white w-full md:w-1/4 lg:w-2/12 h-auto md:h-full p-4 rounded-lg shadow-lg">
            <ul class="space-y-4">
                <li>
                    <a href="dashboard.php" class="flex items-center gap-4 text-blue-500 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-home"></i> Home
                    </a>
                </li>
                <li>
                    <a href="checklist.php" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-line-chart"></i> Checklist
                    </a>
                </li>
                <li>
                    <a href="reports.php" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-paper-plane"></i> Reports
                    </a>
                </li>
                <li>
                    <a href="report_templates.php" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-file"></i> Report Templates
                    </a>
                </li>
                <li>
                    <a href="profile.php" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-user"></i> Profile
                    </a>
                </li>
                <li>
                    <a href="logout.php" class="flex items-center gap-4 text-red-500 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-log-out"></i> Logout
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Dashboard Content -->
        <section class="flex-1 md:ml-6 space-y-6">

            <h1 class="text-2xl font-semibold text-blue-700">Welcome back, <?php echo htmlspecialchars($fullName); ?>!</h1>

<!-- Progress Bar -->
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-semibold mb-4">Progress Overview</h2>
    <div class="mt-4">
        <div class="w-full bg-gray-200 rounded-full h-6">
            <div class="bg-blue-500 h-6 rounded-full" style="width: <?= round($progressPercentage) ?>%;"></div>
        </div>
        <p class="mt-2 text-sm text-gray-600">
            <?= round($progressPercentage, 2) ?>% completed 
            (Total Hours: <?= $totalHoursWorked ?> / Required: <?= $requiredHours ?>)
        </p>
    </div>
</div>


            <!-- Checklist Overview -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Checklist Overview</h2>
                <ul class="space-y-4">
                    <li class="bg-gray-50 p-4 rounded-lg flex justify-between">
                        <span>Resume</span>
                        <span class="text-green-500">Submitted</span>
                    </li>
                    <li class="bg-gray-50 p-4 rounded-lg flex justify-between">
                        <span>Endorsement Letter</span>
                        <span class="text-yellow-500">Pending</span>
                    </li>
                    <li class="bg-gray-50 p-4 rounded-lg flex justify-between">
                        <span>Company MOA</span>
                        <span class="text-red-500">Not Submitted</span>
                    </li>
                </ul>
            </div>

            <!-- Quick Links -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Quick Links</h2>
                <div class="flex gap-4 mt-4">
                    <a href="reports.php" class="flex items-center justify-center bg-blue-500 text-white rounded-lg px-4 py-2 w-1/2">Submit Report</a>
                    <a href="checklist.php" class="flex items-center justify-center bg-green-500 text-white rounded-lg px-4 py-2 w-1/2">Review Checklist</a>
                </div>
            </div>

            <!-- Notifications Section -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Upcoming Deadlines</h2>
                <ul class="space-y-4">
                    <li class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">Your report for Week 5 has been approved.</p>
                    </li>
                    <li class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">Reminder: Task "Complete Weekly Report" is due tomorrow.</p>
                    </li>
                    <li class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">Submit MOA by Nov 20, 2024.</p>
                    </li>
                    <li class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">Weekly Report for Week 12 due Nov 22, 2024.</p>
                    </li>
                </ul>
            </div>

        </section>
    </main>

</body>

</html>
