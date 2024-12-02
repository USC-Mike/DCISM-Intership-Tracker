<?php
require_once '../../src/controllers/studentcontroller.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../public/login.php');
    exit();
}
$userId = $_SESSION['user_id'];

// Display full name
$fullName = $_SESSION['full_name'] ?? 'Guest'; // Fallback to "Guest" if session is not set

// Handle search query
$searchQuery = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';

// Fetch report templates
$templates = fetchReportTemplates($searchQuery);
$unreadNotificationsCount = countUnreadNotifications($userId);
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
    <title>Student - Report Templates</title>
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
                <?php if ($unreadNotificationsCount > 0): ?>
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">
                        <?= htmlspecialchars($unreadNotificationsCount) ?>
                    </span>
                <?php endif; ?>
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
                    <a href="dashboard.php" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
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
                    <a href="report_templates.php" class="flex items-center gap-4 text-blue-500 text-lg p-2 hover:bg-gray-200 rounded-lg">
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
            <!-- Report Templates Section -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-3xl font-semibold text-blue-600">Report Templates</h2>
                <p class="mt-2 text-gray-700">You can download any available report template needed for your progress tracking.</p>
                
<!-- Search Bar -->
<div class="mt-4 mb-6 flex justify-between items-center">
    <form method="GET" action="report_templates.php" class="w-full flex items-center">
        <input type="text" name="search" placeholder="Search Templates..." 
               class="flex-1 p-2 border rounded-md text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
               value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 ml-2 rounded-md hover:bg-blue-600">Search</button>
    </form>
</div>
                
<!-- Templates Table -->
<div class="overflow-x-auto">
    <table class="min-w-full table-auto text-gray-700">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-3 text-left">Template Name</th>
                <th class="p-3 text-left">Template Type</th>
                <th class="p-3 text-left">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($templates)): ?>
                                <tr>
                                    <td colspan="4" class="p-4 text-center text-red-500">No templates found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($templates as $template): ?>
                                    <tr class="border-t">
                                        <td class="p-3"><?= htmlspecialchars($template['name']) ?></td>
                                        <td class="p-3"><?= htmlspecialchars($template['type']) ?></td>
                                        <td class="p-3">
                                             <a href="../../src/controllers/studentcontroller.php?file=<?= urlencode($template['name']) ?>" 
       class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Download</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
        </tbody>
    </table>
</div>
            </div>
        </section>
    </main>
</body>
</html>
