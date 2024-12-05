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
    <title>Documents Management - Coordinator</title>
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
                    <a href="document_management.php" class="flex items-center gap-4 text-blue-500 text-lg p-2 hover:bg-gray-200 rounded-lg">
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
         <form method="GET" action="document_management.php" class="bg-white p-4 rounded-lg shadow-lg mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Search by student name..." 
                    class="flex-1 p-2 border rounded-lg" 
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                
                <select name="document_type" class="p-2 border rounded-lg">
                    <option value="">Filter by Document Type</option>
                    <option value="Personal and Work Information Document" <?= isset($_GET['document_type']) && $_GET['document_type'] === 'Personal and Work Information Document' ? 'selected' : '' ?>>Personal and Work Information Document</option>
                    <option value="Curriculum Vitae" <?= isset($_GET['document_type']) && $_GET['document_type'] === 'Curriculum Vitae' ? 'selected' : '' ?>>Curriculum Vitae</option>
                    <option value="Endorsement Letter" <?= isset($_GET['document_type']) && $_GET['document_type'] === 'Endorsement Letter' ? 'selected' : '' ?>>Endorsement Letter</option>
                    <option value="Company MOA" <?= isset($_GET['document_type']) && $_GET['document_type'] === 'Company MOA' ? 'selected' : '' ?>>Company MOA</option>
                    <option value="Student MOA" <?= isset($_GET['document_type']) && $_GET['document_type'] === 'Student MOA' ? 'selected' : '' ?>>Student MOA</option>
                </select>
                
                <input 
                    type="date" 
                    name="submission_date" 
                    class="p-2 border rounded-lg" 
                    value="<?= htmlspecialchars($_GET['submission_date'] ?? '') ?>">
                
                <select name="document_status" class="p-2 border rounded-lg">
                    <option value="">Filter by Status</option>
                    <option value="For Approval" <?= isset($_GET['document_status']) && $_GET['document_status'] === 'For Approval' ? 'selected' : '' ?>>For Approval</option>
                    <option value="Approved" <?= isset($_GET['document_status']) && $_GET['document_status'] === 'Approved' ? 'selected' : '' ?>>Approved</option>
                    <option value="Rejected" <?= isset($_GET['document_status']) && $_GET['document_status'] === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Filter</button>
            </div>
        </form>
            <!-- Pending Documents Section -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold text-gray-700">Pending Documents</h2>
                <table class="w-full mt-4 table-auto">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 text-left text-sm text-gray-600">Student Name</th>
                            <th class="px-4 py-2 text-left text-sm text-gray-600">Document Type</th>
                            <th class="px-4 py-2 text-left text-sm text-gray-600">Submission Date</th>
                            <th class="px-4 py-2 text-left text-sm text-gray-600">Document Status</th>
                            <th class="px-4 py-2 text-left text-sm text-gray-600">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($filteredDocuments as $doc): ?>
                            <tr class="border-b">
                                <td class="px-4 py-2"><?= htmlspecialchars($doc['student_name']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($doc['document_type']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($doc['date_uploaded']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($doc['document_status']) ?></td>
                                <td class="px-4 py-2">
                                    <form method="POST" action="../../src/controllers/coordinatorcontroller.php" class="inline">
                                        <input type="hidden" name="document_id" value="<?= $doc['id'] ?>">
                                        <button type="submit" name="action" value="Approved" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">Approve</button>
                                        <button type="submit" name="action" value="Rejected" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 ml-2">Reject</button>
                                    </form>
                                    <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 ml-2">
                                    <a href="view_document.php?document_id=<?= $doc['id'] ?>" target="_blank" >
                                        View
                                    </a>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Approved Documents Section -->
            <div class="bg-white p-6 rounded-lg shadow-lg mt-6">
                <h2 class="text-xl font-semibold text-gray-700">Approved Documents</h2>
                <table class="w-full mt-4 table-auto">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 text-left text-sm text-gray-600">Student Name</th>
                            <th class="px-4 py-2 text-left text-sm text-gray-600">Document Type</th>
                            <th class="px-4 py-2 text-left text-sm text-gray-600">Approval Date</th>
                            <th class="px-4 py-2 text-left text-sm text-gray-600">Document Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($approvedDocuments as $doc): ?>
                            <tr class="border-b">
                                <td class="px-4 py-2"><?= htmlspecialchars($doc['student_name']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($doc['document_type']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($doc['date_uploaded']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($doc['document_status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

</body>
</html>
