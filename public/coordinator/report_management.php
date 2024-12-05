<?php
require_once '../../src/controllers/coordinatorcontroller.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
                    <a href="dashboard.php" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
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
                    <a href="report_management.php" class="flex items-center gap-4 text-blue-500 text-lg p-2 hover:bg-gray-200 rounded-lg">
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
                    <option value="Pending" <?= isset($_GET['report_status']) && $_GET['report_status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Approved" <?= isset($_GET['report_status']) && $_GET['report_status'] == 'Approved' ? 'selected' : '' ?>>Approved</option>
                    <option value="Rejected" <?= isset($_GET['report_status']) && $_GET['report_status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
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
            <td class="border p-2 text-<?= $report['report_status'] === 'Approved' ? 'green' : ($report['report_status'] === 'Rejected' ? 'blue' : 'red') ?>-600">
                    <?= ucfirst($report['report_status']) ?>
                </td>
            <td class="border p-2">
            <a href="report_management.php?id=<?= $report['id'] ?>&report_status=Pending" class="bg-blue-500 text-white px-4 py-2 rounded-md">Pending</a>
            <a href="report_management.php?id=<?= $report['id'] ?>&report_status=Approved" class="bg-green-500 text-white px-4 py-2 rounded-md">Approve</a>
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
