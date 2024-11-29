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
    <title>Student - Reports</title>

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
                        <a href="S-Profile.html" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">View</a>
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
                    <a href="S-Reports.html" class="flex items-center gap-4 text-blue-500 text-lg p-2 hover:bg-gray-200 rounded-lg">
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

        <!-- Dashboard Content -->
        <section class="flex-1 md:ml-6 space-y-6">
            <h2 class="text-2xl font-bold text-gray-700">Submit Reports</h2>

            <!-- Report Notification -->
            <?php if (isset($_GET['success'])): ?>
            <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-4">
                Report submitted successfully.
            </div>
            <?php endif; ?>

            <!-- Report Type Selector -->
            <div class="flex items-center space-x-4">
                <button id="daily-btn" class="py-2 px-4 bg-blue-500 text-white rounded-md hover:bg-blue-600 hover:text-white">Daily Report</button>
                <button id="weekly-btn" class="py-2 px-4 bg-gray-200 text-gray-700 rounded-md hover:bg-blue-600 hover:text-white">Weekly Report</button>
            </div>

            <!-- Report Form -->
            <form class="bg-white p-6 rounded-lg shadow-md space-y-4" id="report-form" method="POST" action="../../src/controllers/studentcontroller.php">
            <input type="hidden" id="report-type" name="report_type" value="daily"> <!-- Default to daily -->    
            
            <!-- Date picker -->
            <div>
                    <label for="date" class="block text-gray-600 font-medium">Select Date</label>
                    <input type="date" id="date" name="date" class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <!-- Week selector (only for weekly reports) -->
                <div id="week-container" class="hidden">
                    <label for="week-number" class="block text-gray-600 font-medium">Select Week</label>
                    <select id="week-number" name="week-number" class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Week</option>
                        <option value="1">Week 1</option>
                        <option value="2">Week 2</option>
                        <!-- Add other weeks as needed -->
                    </select>
                </div>

                <!-- Hours worked -->
                <div>
                    <label for="hours" class="block text-gray-600 font-medium">Hours Worked</label>
                    <input type="number" id="hours" name="hours" class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter hours worked" required>
                </div>

                <!-- Work description -->
                <div>
                    <label for="work-description" class="block text-gray-600 font-medium">Task Details</label>
                    <textarea id="work-description" name="work-description" rows="4" class="w-full p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Describe tasks completed" required></textarea>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">Submit Report</button>
            </form>

            <!-- View Previous Reports -->
            <div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Previously Submitted Reports</h3>
                <ul class="bg-white p-4 rounded-lg shadow-md space-y-2">
                    <?php if (!empty($reports)): ?>
                    <?php foreach ($reports as $report): ?>
                        <li class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">
                                <?= ucfirst($report['report_type']) ?> Report - 
                                <?= htmlspecialchars($report['report_type'] === 'weekly' ? 'Week ' . $report['week_number'] : $report['date']) ?>
                            </span>
                            <span><?= htmlspecialchars($report['submitted_at']) ?></span>
                        </li>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <li class="text-gray-600">No reports submitted yet.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </section>
    </main>

    <script>
        const dailyBtn = document.getElementById('daily-btn');
        const weeklyBtn = document.getElementById('weekly-btn');
        const weekContainer = document.getElementById('week-container');
        const reportTypeInput = document.getElementById('report-type'); // Get the hidden report_type input

        // When Daily Report is selected
        dailyBtn.addEventListener('click', () => {
            weekContainer.classList.add('hidden');
            dailyBtn.classList.replace('bg-gray-200', 'bg-blue-500');
            dailyBtn.classList.replace('text-gray-700', 'text-white');
            weeklyBtn.classList.replace('bg-blue-500', 'bg-gray-200');
            weeklyBtn.classList.replace('text-white', 'text-gray-700');

            reportTypeInput.value = 'daily'; // Set report type to 'daily'
        });

        // When Weekly Report is selected
        weeklyBtn.addEventListener('click', () => {
            weekContainer.classList.remove('hidden');
            weeklyBtn.classList.replace('bg-gray-200', 'bg-blue-500');
            weeklyBtn.classList.replace('text-gray-700', 'text-white');
            dailyBtn.classList.replace('bg-blue-500', 'bg-gray-200');
            dailyBtn.classList.replace('text-white', 'text-gray-700');

            reportTypeInput.value = 'weekly'; // Set report type to 'weekly'
        });
    </script>
</body>
</html>
