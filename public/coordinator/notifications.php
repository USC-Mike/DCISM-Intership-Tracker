<?php
require_once '../../src/controllers/coordinatorcontroller.php'; // Adjust path as needed
// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../public/login.php');
    exit();
}

// Display full name, defaulting to "Guest" if not set
$fullName = $_SESSION['full_name'] ?? 'Guest';

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
    <title>Coordinator Notifications</title>
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
                    <a href="dashboard.php" class="flex items-center gap-4 text-blue-500 text-lg p-2 hover:bg-gray-200 rounded-lg">
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

            <!-- Send Notification Form -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-semibold text-gray-700">Send Notification</h2>
                <form method="POST" action="../../src/controllers/coordinatorcontroller.php" class="mt-6 space-y-4">
                <input type="hidden" name="send_notification" value="1">
                <div>
                        <label for="recipient" class="block text-gray-600">Recipient</label>
                        <select name="recipient" id="recipient" class="w-full p-3 border rounded-md" onchange="toggleStudentSelector()">
                            <option value="all">All Students</option>
                            <option value="specific">Specific Student(s)</option>
                        </select>
                    </div>

                    <!-- Specific Student Selector (hidden by default) -->
                    <div id="specific-student" class="hidden">
                        <label for="student_id" class="block text-gray-600">Select Student</label>
                        <select name="student_id" id="student_id" class="w-full p-3 border rounded-md">
                            <option value="">Select a student</option>
                            <!-- Dynamically populate students -->
            <?php
            $students = $pdo->query("SELECT id, full_name FROM students")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($students as $student) {
                echo "<option value='{$student['id']}'>{$student['full_name']}</option>";
            }
            ?>
                        </select>
                    </div>

                    <div>
                        <label for="message" class="block text-gray-600">Message</label>
                        <textarea name="message" id="message" rows="4" class="w-full p-3 border rounded-md" placeholder="Enter your notification content here..."></textarea>
                    </div>


                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-md">Send</button>
                    </div>
                </form>
            </div>

            <!-- Notification History -->
            <div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-gray-700">Notification History</h2>

    <div class="mt-6 space-y-4">
        <?php if (!empty($notifications)): ?>
            <?php foreach ($notifications as $notification): ?>
                <div class="flex justify-between items-center border-b pb-4">
                    <div>
                        <p class="font-semibold text-gray-800">
                            <?= htmlspecialchars($notification['recipient_name'] ?? 'All Students') ?>
                        </p>
                        <p class="text-gray-600">
                            Sent on: <?= htmlspecialchars(date('F j, Y', strtotime($notification['date_sent']))) ?>
                        </p>
                        <p class="text-gray-500 mt-2">
                            Message: <?= htmlspecialchars($notification['message']) ?>
                        </p>
                    </div>
                    <div class="flex gap-4">
                        <button class="px-4 py-2 bg-yellow-500 text-white rounded-md">Edit</button>
                        <button class="px-4 py-2 bg-green-500 text-white rounded-md">Resend</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-gray-600">No notifications found.</p>
        <?php endif; ?>
    </div>
</div>


        </section>
    </main>
    
    <!-- Script to toggle student selection visibility -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
    const recipient = document.getElementById('recipient');
    recipient.addEventListener('change', toggleStudentSelector);
});

function toggleStudentSelector() {
    // Get the value of the recipient dropdown
    const recipient = document.getElementById('recipient').value;

    // Get the specific-student div
    const specificStudent = document.getElementById('specific-student');

    // Show or hide the specific-student selector based on the recipient value
    if (recipient === 'specific') {
        specificStudent.classList.remove('hidden'); // Show specific-student section
    } else {
        specificStudent.classList.add('hidden'); // Hide specific-student section
    }
}

    </script>

    <!-- Footer (Optional) -->
    <footer class="bg-white shadow-md mt-12 py-4 text-center">
        <p class="text-gray-500">&copy; 2024 OJT Tracker | All Rights Reserved</p>
    </footer>

</body>
</html>
