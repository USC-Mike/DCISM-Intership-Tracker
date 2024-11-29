<?php
// Include the database connection
require_once '../../src/controllers/coordinatorcontroller.php'; // Adjust the path as needed


// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../public/login.php');
    exit();
}


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
    <title>Progress Tracker - Reports Management</title>
</head>
<body class="bg-gray-100 font-lato">

    <!-- Header -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="flex items-center justify-between px-6 py-4">
            <div class="flex items-center gap-4">
                <img src="./images/ces-logo.png" alt="Logo" class="h-10 w-auto">
                <h1 class="text-2xl font-semibold text-blue-500">OJT Tracker</h1>
            </div>
            <div class="flex items-center gap-6">
                <div class="relative">
                    <i class="bx bx-bell text-2xl text-gray-700 cursor-pointer"></i>
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
                    <a href="C-Dash.html" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-home text-lg"></i> Home
                    </a>
                </li>
                <li>
                    <a href="C-StudMgnt.html" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-user text-lg"></i> Student Management
                    </a>
                </li>
                <li>
                    <a href="C-DocsMgnt.html" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-file text-lg"></i> Document Management
                    </a>
                </li>
                <li>
                    <a href="C-ReportMgnt.html" class="flex items-center gap-4 text-blue-500 text-lg p-2 hover:bg-gray-200 rounded-lg">
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

        <!-- Search and Filter Section -->
        <form method="GET" action="report_management.php" class="bg-white p-4 rounded-lg shadow-lg">
            <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                <!-- Search --->
                <input type="text" name="search" placeholder="Search students by name, ID, or company..." class="flex-1 p-2 border rounded-lg" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                
                <!-- Date Selection --->
                <input type="date" name="date" class="p-2 border rounded-lg" value="<?= htmlspecialchars($_GET['date'] ?? '') ?>">

                <!-- Type Selection --->
                <select name="report_type" class="p-2 border rounded-lg">
                    <option value="">Filter by Type</option>
                    <option value="daily" <?= isset($_GET['report_type']) && $_GET['report_type'] == 'daily' ? 'selected' : '' ?>>Daily</option>
                    <option value="weekly" <?= isset($_GET['report_type']) && $_GET['report_type'] == 'weekly' ? 'selected' : '' ?>>Weekly</option>
                </select>

                <!-- Status Selection --->
                <select name="report_status" class="p-2 border rounded-lg">
                    <option value="">Filter by Status</option>
                    <option value="reviewed" <?= isset($_GET['report_status']) && $_GET['report_status'] == 'reviewed' ? 'selected' : '' ?>>Reviewed</option>
                    <option value="not-reviewed" <?= isset($_GET['report_status']) && $_GET['report_status'] == 'not-reviewed' ? 'selected' : '' ?>>Not Reviewed</option>
                </select>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Filter</button>
            </div>
        </form>

        <?php if (isset($_GET['status_update_success'])): ?>
    <div class="bg-green-500 text-white p-4 rounded-md mb-4 flex items-center justify-between">
        <span>Status updated successfully!</span>
        <!-- Close Button -->
        <button class="text-white text-xl font-bold hover:text-gray-300" onclick="this.parentElement.style.display='none'">&times;</button>

    </div>
<?php endif; ?>

            <!-- Reports Table -->
            <div class="bg-white p-4 rounded-lg shadow-lg">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border p-2">Student Name</th>
                            <th class="border p-2">Date</th>
                            <th class="border p-2">Report Type</th>
                            <th class="border p-2">Hours Worked</th>
                            <th class="border p-2">Task Summary</th>

                            <th class="border p-2">Progress</th>
                            <th class="border p-2">Status</th>
                            <th class="border p-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    <tbody>

    <?php if (!empty($reportsq)): ?>
    <?php foreach ($reportsq as $report): ?>
        <tr>
            <td class="border p-2"><?php echo htmlspecialchars($report['full_name']); ?></td>
            <td class="border p-2"><?php echo htmlspecialchars($report['date']); ?></td>

            <td class="border p-2"><?php echo htmlspecialchars($report['report_type']); ?></td>
            <td class="border p-2"><?php echo htmlspecialchars($report['hours_worked']); ?> hours</td>
            <td class="border p-2"><?php echo htmlspecialchars($report['work_description']); ?></td>
            <td class="border p-2">
        <div style="width: 100%; background-color: #f3f3f3; border: 1px solid #ccc;">
            <div style="width: <?= htmlspecialchars($report['progress_percentage']); ?>%; background-color: #4caf50; height: 20px;"></div>
        </div>
        <span><?= round($report['progress_percentage'], 2); ?>%</span>
    </td>
            <td class="border p-2 text-<?= $report['report_status'] === 'Approved' ? 'green' : ($report['report_status'] === 'Rejected' ? 'blue' : 'red') ?>-600">
                    <?= ucfirst($report['report_status']) ?>
                </td>
            <td class="border p-2">
            <a href="report_management.php?id=<?= $report['id'] ?>&report_status=Approved" class="bg-blue-500 text-white px-4 py-2 rounded-md">Approve</a>
            <a href="report_management.php?id=<?= $report['id'] ?>&report_status=Rejected" class="bg-red-500 text-white px-4 py-2 rounded-md">Rejected</a>
            
            </td>
        </tr>
    <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="6" class="text-center border p-4">No reports found.</td>
        </tr>
    <?php endif; ?>
</tbody>



                </table>
            </div>

        </section>
    </main>

</body>
</html>
