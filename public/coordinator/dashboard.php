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
    <style>
        /* Ensure smooth transition for sidebar */
        #sidebar.collapsed {
            transform: translateX(-100%);
        }
        #content.collapsed {
            margin-left: 0;
        }
    </style>
</head>
<body class="bg-gray-100 font-lato">

    <!-- Header -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="flex items-center justify-between px-6 py-4">
            <div class="flex items-center gap-4">
                <img src="./images/ces-logo.png" alt="Logo" class="h-10 w-auto">
                <h1 class="text-2xl font-semibold text-blue-500">OJT Tracker - Coordinator</h1>
            </div>
            <div class="flex items-center gap-6">
                <div class="relative">
                    <i class="bx bx-bell text-2xl text-gray-700 cursor-pointer" onclick="window.location.href='notification-page.html'"></i>
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">3</span>
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
                    <a href="C-Dash.html" class="flex items-center gap-4 text-blue-500 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-home text-lg"></i> Home
                    </a>
                </li>
                <li>
                    <a href="C-StudMgnt.html" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-group text-lg"></i> Student Management
                    </a>
                </li>
                <li>
                    <a href="C-DocsMgnt.html" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-file text-lg"></i> Document Management
                    </a>
                </li>
                <li>
                    <a href="C-ReportMgnt.html" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-task text-lg"></i> Report Management
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-edit text-lg"></i> Evaluation Management
                    </a>
                </li>
                <li>
                    <a href="C-Profile.html" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-user text-lg"></i> Profile
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-4 text-red-500 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-log-out text-lg"></i> Logout
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Dashboard Content -->
        <section class="flex-1 md:ml-6 space-y-6">
            <!-- Summary Cards -->

            <h1 class="text-2xl font-semibold text-blue-700">Welcome back coordinator, John Doe</h1>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-lg flex items-center gap-4">
                    <i class="bx bx-group text-blue-500 text-4xl"></i>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Active Students</h3>
                        <p class="text-2xl font-bold text-gray-900">120</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg flex items-center gap-4">
                    <i class="bx bx-file text-yellow-500 text-4xl"></i>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Pending Documents</h3>
                        <p class="text-2xl font-bold text-gray-900">15</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg flex items-center gap-4">
                    <i class="bx bx-calendar text-red-500 text-4xl"></i>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Upcoming Deadlines</h3>
                        <p class="text-2xl font-bold text-gray-900">3</p>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-lg font-semibold text-gray-700">Quick Links</h2>
                <div class="flex gap-4 mt-4">
                    <a href="#" class="flex items-center justify-center bg-blue-500 text-white rounded-lg px-4 py-2 w-1/3">
                        Approve Documents
                    </a>
                    <a href="#" class="flex items-center justify-center bg-green-500 text-white rounded-lg px-4 py-2 w-1/3">
                        Review Reports
                    </a>
                    <a href="#" class="flex items-center justify-center bg-yellow-500 text-white rounded-lg px-4 py-2 w-1/3">
                        Send Notifications
                    </a>
                </div>
            </div>

            <!-- Notifications Section -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Recent Notifications</h2>
                <ul class="space-y-4">
                    <li class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">Student X submitted MOA.</p>
                    </li>
                    <li class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">RWeekly Report from Student Y.</p>
                    </li>
                    <li class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600">Evaluation for Student Z due.</p>
                    </li>
                    <!-- Add more notifications here -->
                </ul>
            </div>
        </section>
    </main>

</body>
</html>
