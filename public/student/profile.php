<?php
require_once '../../src/controllers/studentcontroller.php';
require_once '../../src/config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Check if PDO connection is set
if (!isset($pdo)) {
    die('Database connection failed.');
}

$userId = $_SESSION['user_id'];
$fullName = $_SESSION['full_name'] ?? 'Guest'; // Fallback to "Guest" if session is not set

$stmt = $pdo->prepare("SELECT full_name, mobile, email, course, schoolyear, coordinator, work_schedule, company_name, supervisor_name, company_address, company_email, supervisor_contact
        FROM students WHERE id = ?");
$stmt->execute([$userId]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Student details not found.");
}
$userId = $_SESSION['user_id'];
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
    <title>Student - Profile</title>

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
                    <a href="report_templates.php" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-file"></i> Report Templates 
                    </a>
                </li>
                <li>
                    <a href="profile.php" class="flex items-center gap-4 text-blue-500 text-lg p-2 hover:bg-gray-200 rounded-lg">
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

        <!-- Profile Content -->
        <section class="flex-1 md:ml-6 space-y-6">
            <!-- Notification Section -->
            <?php if (isset($_GET['personal_success'])): ?>
            <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-4">
                Personal information updated successfully.
            </div>
            <?php endif; ?>

            <?php if (isset($_GET['company_success'])): ?>
                <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-4">
                    Company information updated successfully.
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">
                    An error occurred. Please try again.
                </div>
            <?php endif; ?>


            <!-- Personal Info Section -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-blue-500 mb-4">Personal Information</h2>

                <form method="POST" action="../../src/controllers/studentcontroller.php">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="full_name" class="block text-gray-700 font-medium mb-2">Full Name</label>
                            <input type="text" id="full_name" name="full_name" class="w-full p-3 border rounded-lg" value="<?php echo htmlspecialchars($student['full_name']); ?>" required>
                        </div>
                        <div>
                            <label for="mobile" class="block text-gray-700 font-medium mb-2">Mobile Number</label>
                            <input type="text" id="mobile" name="mobile" class="w-full p-3 border rounded-lg" value="<?php echo htmlspecialchars($student['mobile']); ?>" required>
                        </div>
                        <div>
                            <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                            <input type="email" id="email" name="email" class="w-full p-3 border rounded-lg" value="<?php echo htmlspecialchars($student['email']); ?>" required>
                        </div>
                        <div>
                            <label for="course" class="block text-gray-700 font-medium mb-2">Course</label>
                            <input type="text" id="course" name="course" class="w-full p-3 border rounded-lg" value="<?php echo htmlspecialchars($student['course']); ?>" required>
                        </div>
                        <div>
                            <label for="schoolyear" class="block text-gray-700 font-medium mb-2">School Year</label>
                            <input type="text" id="schoolyear" name="schoolyear" class="w-full p-3 border rounded-lg" value="<?php echo htmlspecialchars($student['schoolyear']); ?>" required>
                        </div>
                        <div>
                            <label for="coordinator" class="block text-gray-700 font-medium mb-2">Coordinator</label>
                            <input type="text" id="coordinator" name="coordinator" class="w-full p-3 border rounded-lg" value="<?php echo htmlspecialchars($student['coordinator']); ?>" required>
                        </div>
                    </div>
                    <button name="userinfo" class="mt-6 bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">
                        Save Changes
                    </button>
                </form>
            </div>
            
            <!-- Company Details Section -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold text-blue-500 mb-4">Company Information</h2>
                
                <form method="POST" action="../../src/controllers/studentcontroller.php">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="company_name" class="block text-gray-700 font-medium mb-2">Company Name</label>
                            <input type="text" id="company_name" name="company_name" class="w-full p-3 border rounded-lg" value="<?php echo htmlspecialchars($student['company_name']); ?>" required>
                        </div>
                        
                        <div>
                            <label for="company_address" class="block text-gray-700 font-medium mb-2">Company Address</label>
                            <input type="text" id="company_address" name="company_address" class="w-full p-3 border rounded-lg" value="<?php echo htmlspecialchars($student['company_address']); ?>" required>
                        </div>
                        <div>
                            <label for="company_email" class="block text-gray-700 font-medium mb-2">Company Email</label>
                            <input type="email" id="company_email" name="company_email" class="w-full p-3 border rounded-lg" value="<?php echo htmlspecialchars($student['company_email']); ?>" required>
                        </div>

                        <div>
                            <label for="supervisor_name" class="block text-gray-700 font-medium mb-2">Supervisor Name</label>
                            <input type="text" id="supervisor_name" name="supervisor_name" class="w-full p-3 border rounded-lg" value="<?php echo htmlspecialchars($student['supervisor_name']); ?>" required>
                        </div>

                        <div>
                            <label for="supervisor_contact" class="block text-gray-700 font-medium mb-2">Supervisor Contact</label>
                            <input type="text" id="supervisor_contact" name="supervisor_contact" class="w-full p-3 border rounded-lg" value="<?php echo htmlspecialchars($student['supervisor_contact']); ?>" required>
                        </div>

                        <div>
                            <label for="work_schedule" class="block text-gray-700 font-medium mb-2">Work Schedule</label>
                            <input type="text" id="work_schedule" name="work_schedule" class="w-full p-3 border rounded-lg" value="<?php echo htmlspecialchars($student['work_schedule']); ?>" required>
                        </div>
                    </div>
                    <button name="companyinfo" class="mt-6 bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600">
                        Save Changes
                    </button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
