<?php
require_once '../../src/controllers/coordinatorcontroller.php';
require_once '../../src/config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Display full name
$fullName = $_SESSION['full_name'] ?? 'Guest'; // Fallback to "Guest" if session is not set
// Fetch student details
$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT full_name, email, course_coordinator, department
        FROM coordinators WHERE id = ?");
$stmt->execute([$userId]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Coordinator details not found.");
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
    <title>Coordinator Profile</title>
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
                    <a href="report_management.php" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-task text-lg"></i> Report Management
                    </a>
                </li>
                <li>
                    <a href="profile.php" class="flex items-center gap-4 text-blue-500 text-lg p-2 hover:bg-gray-200 rounded-lg">
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

        <!-- Settings Content -->
        <section class="flex-1 md:ml-6 space-y-6">

        <!-- Notification Section -->
        <?php if (isset($_GET['personal_success'])): ?>
            <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-4">
                Personal information updated successfully.
            </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">
                    An error occurred. Please try again.
                </div>
            <?php endif; ?>
            <!-- Coordinator Profile Section -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-lg font-semibold text-gray-700">Coordinator Profile</h2>
                <form method="POST" class="space-y-4 mt-4" action="../../src/controllers/coordinatorcontroller.php">
                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label for="full_name" class="block text-sm text-gray-600">Name</label>
                            <input type="text" id="full_name" name="full_name" class="w-full px-4 py-2 border rounded-lg" value="<?php echo htmlspecialchars($student['full_name']); ?>" required>
                        </div>
                        <div class="w-1/2">
                            <label for="email" class="block text-sm text-gray-600">Email</label>
                            <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg" value="<?php echo htmlspecialchars($student['email']); ?>" required>
                        </div>
                    </div>
                    <div>
                        <label for="coursecoordinator" class="block text-sm text-gray-600">Course Coordinator</label>
                        <input type="text" id="coursecoordinator" name="coursecoordinator" class="w-full px-4 py-2 border rounded-lg" value="<?php echo htmlspecialchars($student['course_coordinator']); ?>" required>
                    </div>
     
                    <button type="submit" name="coordinatorinfo" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Save Changes</button>
                </form>
            </div>

            <!-- App Settings Section -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-lg font-semibold text-gray-700">App Settings</h2>
                <form action="#" method="post" class="space-y-4 mt-4">
                    <div class="flex gap-4">
                        <div class="w-1/2">
                            <label for="deadlines" class="block text-sm text-gray-600">Configure Deadlines</label>
                            <input type="text" id="deadlines" name="deadlines" class="w-full px-4 py-2 border rounded-lg" placeholder="e.g., 2024-12-01">
                        </div>
                        <div class="w-1/2">
                            <label for="reminders" class="block text-sm text-gray-600">Set Reminders</label>
                            <input type="text" id="reminders" name="reminders" class="w-full px-4 py-2 border rounded-lg" placeholder="Weekly">
                        </div>
                    </div>
                    <div>
                        <label for="doc-requirements" class="block text-sm text-gray-600">Document Requirements</label>
                        <textarea id="doc-requirements" name="doc-requirements" class="w-full px-4 py-2 border rounded-lg" rows="4" placeholder="Specify requirements..."></textarea>
                    </div>
                    <div class="flex gap-4">
                        <button type="submit" class="px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Export Data</button>
                        <button type="submit" class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">Import Data</button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-4 text-center mt-6">
        <p>&copy; 2024 OJT Tracker. All rights reserved. | Contact: support@ojttracker.com</p>
    </footer>
</body>
</html>
