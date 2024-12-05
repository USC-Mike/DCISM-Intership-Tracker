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
    <title>Coordinator Student Management</title>
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
                    <a href="student_management.php" class="flex items-center gap-4 text-blue-500 text-lg p-2 hover:bg-gray-200 rounded-lg">
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
            <!-- Search and Filter Section -->
        <form method="GET" action="student_management.php" class="bg-white p-4 rounded-lg shadow-lg">
            <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                <input type="text" name="search" placeholder="Search students by name, ID, or company..." class="flex-1 p-2 border rounded-lg" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <select name="status" class="p-2 border rounded-lg">
                    <option value="">Filter by Status</option>
                    <option value="active" <?= isset($_GET['status']) && $_GET['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="completed" <?= isset($_GET['status']) && $_GET['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="on-hold" <?= isset($_GET['status']) && $_GET['status'] === 'on-hold' ? 'selected' : '' ?>>On Hold</option>
                </select>
                <select name="year_level" class="p-2 border rounded-lg">
                    <option value="">Filter by Year Level</option>
                    <option value="3rd Year" <?= isset($_GET['year_level']) && $_GET['year_level'] == '3' ? 'selected' : '' ?>>3rd Year</option>
                    <option value="4th Year" <?= isset($_GET['year_level']) && $_GET['year_level'] == '4' ? 'selected' : '' ?>>4th Year</option>
                </select>
                <select name="company" class="p-2 border rounded-lg">
                    <option value="">Filter by Company</option>
                    <option value="ABC Corporation" <?= isset($_GET['company']) && $_GET['company'] === 'ABC Corp' ? 'selected' : '' ?>>ABC Corp</option>
                    <option value="XYZ Ltd" <?= isset($_GET['company']) && $_GET['company'] === 'XYZ Ltd' ? 'selected' : '' ?>>XYZ Ltd</option>
                    <option value="CBS Inc" <?= isset($_GET['company']) && $_GET['company'] === 'CBS Inc' ? 'selected' : '' ?>>CBS Inc</option>
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


            <!-- Student List -->
            <div class="bg-white p-4 rounded-lg shadow-lg">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border p-2">Student Name</th>
                            <th class="border p-2">ID</th>
                            <th class="border p-2">Course</th>

                            <th class="border p-2">Year Level</th>
                            <th class="border p-2">Company</th>
                            <th class="border p-2">Status</th>
                            
                            <th class="border p-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php if (!empty($students)): ?>
        <?php foreach ($students as $student): ?>
            <tr>
                <td class="border p-2"><?= htmlspecialchars($student['full_name']) ?></td>
                <td class="border p-2"><?= htmlspecialchars($student['id']) ?></td>
                <td class="border p-2"><?= htmlspecialchars($student['course']) ?></td>
                <td class="border p-2"><?= htmlspecialchars($student['year_level']) ?></td>

                <td class="border p-2"><?= htmlspecialchars($student['company_name']) ?></td>
                <td class="border p-2 text-<?= $student['status'] === 'active' ? 'green' : ($student['status'] === 'completed' ? 'blue' : 'red') ?>-600">
                    <?= ucfirst($student['status']) ?>
                </td>
                <td class="border p-2">

    <a href="student_management.php?id=<?= $student['id'] ?>&status=active" class="bg-blue-500 text-white px-4 py-2 rounded-md">Active</a>
    <a href="student_management.php?id=<?= $student['id'] ?>&status=on-hold" class="bg-red-500 text-white px-4 py-2 rounded-md">On Hold</a>
    <a href="student_management.php?id=<?= $student['id'] ?>&status=completed" class="bg-green-500 text-white px-4 py-2 rounded-md">Completed</a>
</td>

            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="6" class="text-center border p-4">No students found.</td>
        </tr>
    <?php endif; ?>
</tbody>

                </table>
            </div>
        </section>
    </main>
</body>
</html>
