<?php
session_start();
include(__DIR__ . '/../../src/config/db.php');; // Adjust path based on your folder structure
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../public/login.php');
    exit();
}

// Check if PDO connection is set
if (!isset($pdo)) {
    die('Database connection failed.');
}

// Display full name
$fullName = $_SESSION['full_name'] ?? 'Guest'; // Fallback to "Guest" if session is not set
// Fetch student details
$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT full_name, mobile, email, company_name, supervisor_name, company_address, company_email 
        FROM students WHERE id = ?");
$stmt->execute([$userId]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Student details not found.");
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
    <title>Student - Profile</title>

</head>
<body class="bg-gray-100 font-lato">

    <!-- Header -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="flex items-center justify-between px-6 py-4">
            <div class="flex items-center gap-4">
                <img src="./assets/images/dcism-logo.png" alt="Logo" class="h-10 w-auto">
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
                    <a href="S-Reports.html" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-paper-plane"></i> Reports
                    </a>
                </li>
                <li>
                    <a href="S-Eval.html" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-notification"></i> Evaluation
                    </a>
                </li>
                <li>
                    <a href="S-Profile.html" class="flex items-center gap-4 text-blue-500 text-lg p-2 hover:bg-gray-200 rounded-lg">
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
                            <label for="supervisor_name" class="block text-gray-700 font-medium mb-2">Supervisor Name</label>
                            <input type="text" id="supervisor_name" name="supervisor_name" class="w-full p-3 border rounded-lg" value="<?php echo htmlspecialchars($student['supervisor_name']); ?>" required>
                        </div>
                        <div>
                            <label for="company_address" class="block text-gray-700 font-medium mb-2">Company Address</label>
                            <input type="text" id="company_address" name="company_address" class="w-full p-3 border rounded-lg" value="<?php echo htmlspecialchars($student['company_address']); ?>" required>
                        </div>
                        <div>
                            <label for="company_email" class="block text-gray-700 font-medium mb-2">Company Email</label>
                            <input type="email" id="company_email" name="company_email" class="w-full p-3 border rounded-lg" value="<?php echo htmlspecialchars($student['company_email']); ?>" required>
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
