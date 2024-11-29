<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../public/login.php');
    exit();
}

// Display full name
$fullName = $_SESSION['full_name'] ?? 'Guest'; // Fallback to "Guest" if session is not set
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
    <title>Student - Notifications</title>
    <script>
        // Function to remove a notification when "Mark as Read" is clicked
        function markAsRead(notification) {
            notification.remove(); // Remove the notification from the DOM
        }
    </script>
</head>
<body class="bg-gray-100 font-lato">

    <!-- Header -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="flex items-center justify-between px-6 py-4">
            <div class="flex items-center gap-4">
                <img src="./images/dcism-logo.png" alt="Logo" class="h-10 w-auto">
                <h1 class="text-2xl font-semibold text-blue-500">Internship Tracker</h1>
            </div>
            <div class="flex items-center gap-6">
                <div class="relative">
                    <!-- Bell icon with link to notifications page -->
                    <a href="S-Notif.html">
                        <i class="bx bx-bell text-2xl text-gray-700 cursor-pointer"></i>
                    </a>
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">5</span>
                </div>
                <div class="relative group">
                    <img src="./profile.jpg" alt="Profile" class="w-10 h-10 rounded-full cursor-pointer">
                    <div class="absolute right-0 mt-2 bg-white border rounded-md shadow-md hidden group-hover:block">
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">View Profile</a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</a>
                    </div>
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
                    <a href="S-Dash.html" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-home"></i> Home
                    </a>
                </li>
                <li>
                    <a href="S-Checklist.html" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-line-chart"></i> Checklist
                    </a>
                </li>
                <li>
                    <a href="S-Reports.html" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-paper-plane"></i> Reports
                    </a>
                </li>
                <li>
                    <a href="S-Eval.html" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-notification"></i> Evaluation
                    </a>
                </li>
                <li>
                    <a href="S-Profile.html" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-user"></i> Profile
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-4 text-red-500 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-log-out"></i> Logout
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Notifications Content -->
        <section class="flex-1 md:ml-6 space-y-6">
            <h2 class="text-xl font-semibold text-gray-800">Notifications</h2>

            <!-- Notification List -->
            <div class="space-y-4">
                <!-- Notification 1 -->
                <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-md hover:bg-gray-50">
                    <div>
                        <h3 class="text-lg font-medium text-gray-800">OJT Report Deadline Approaching</h3>
                        <p class="text-sm text-gray-600">Your OJT report submission deadline is approaching. Submit it by the end of the week.</p>
                        <p class="text-xs text-gray-500 mt-1">Due: 12/15/2024</p>
                    </div>
                    <button class="text-blue-500 text-xs font-medium" onclick="markAsRead(this.parentElement)">Mark as Read</button>
                </div>

                <!-- Notification 2 -->
                <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-md hover:bg-gray-50">
                    <div>
                        <h3 class="text-lg font-medium text-gray-800">Evaluation Submitted</h3>
                        <p class="text-sm text-gray-600">Your evaluation for OJT has been successfully submitted. Thank you for completing it.</p>
                        <p class="text-xs text-gray-500 mt-1">Date: 11/20/2024</p>
                    </div>
                    <button class="text-blue-500 text-xs font-medium" onclick="markAsRead(this.parentElement)">Mark as Read</button>
                </div>

                <!-- Notification 3 -->
                <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-md hover:bg-gray-50">
                    <div>
                        <h3 class="text-lg font-medium text-gray-800">Final Report Submission Reminder</h3>
                        <p class="text-sm text-gray-600">Reminder: Your final report is due by 12/30/2024. Please upload it before the deadline.</p>
                        <p class="text-xs text-gray-500 mt-1">Due: 12/30/2024</p>
                    </div>
                    <button class="text-blue-500 text-xs font-medium" onclick="markAsRead(this.parentElement)">Mark as Read</button>
                </div>
            </div>
        </section>
    </main>

</body>
</html>
