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
    <title>Student - Checklist</title>

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
                    <a href="checklist.php" class="flex items-center gap-4 text-blue-500 text-lg p-2 hover:bg-gray-200 rounded-lg">
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
            <!-- Submission Guidelines -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold mb-4">Submission Guidelines</h2>
                <ul class="list-disc pl-6 text-gray-600 space-y-2">
                    <li>Ensure all documents are in PDF format.</li>
                    <li>Follow the naming convention: Lastname_Firstname_DocumentName.</li>
                    <li>Deadlines are strictly enforced; submit on or before the due date.</li>
                </ul>
            </div>
            <!-- Document Checklist -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold mb-4">Document Checklist</h2>
                <p class="text-gray-600 mb-6">Below is the list of required documents and their submission statuses. Click "Upload" to submit your files.</p>
                <div class="space-y-4">
                    <!-- Checklist Item -->
                    <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg shadow-sm">
                        <div>
                            <h3 class="text-lg font-semibold">Personal and Work Information Document</h3>
                            <p class="text-sm text-gray-500">Status: <span class="text-yellow-500 font-medium">Pending</span></p>
                        </div>
                        <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                            Upload
                        </button>
                    </div>
                    <!-- Checklist Item -->
                    <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg shadow-sm">
                        <div>
                            <h3 class="text-lg font-semibold">Curriculum Vitae</h3>
                            <p class="text-sm text-gray-500">Status: <span class="text-yellow-500 font-medium">Pending</span></p>
                        </div>
                        <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                            Upload
                        </button>
                    </div>
                    <!-- Checklist Item -->
                    <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg shadow-sm">
                        <div>
                            <h3 class="text-lg font-semibold">Parent's Consent (Signed by parent or guardian)</h3>
                            <p class="text-sm text-gray-500">Status: <span class="text-yellow-500 font-medium">Pending</span></p>
                        </div>
                        <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                            Upload
                        </button>
                    </div>
                    <!-- Checklist Item -->
                    <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg shadow-sm">
                        <div>
                            <h3 class="text-lg font-semibold">Endorsement Letter</h3>
                            <p class="text-sm text-gray-500">Status: <span class="text-yellow-500 font-medium">Pending</span></p>
                        </div>
                        <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                            Upload
                        </button>
                    </div>
                    <!-- Checklist Item -->
                    <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg shadow-sm">
                        <div>
                            <h3 class="text-lg font-semibold">Company MOA (Memorandum of Agreement for Company)</h3>
                            <p class="text-sm text-gray-500">Status: <span class="text-yellow-500 font-medium">Pending</span></p>
                        </div>
                        <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                            Upload
                        </button>
                    </div>
                    <!-- Checklist Item -->
                    <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg shadow-sm">
                        <div>
                            <h3 class="text-lg font-semibold">Student MOA (Memorandum of Agreement for Student)</h3>
                            <p class="text-sm text-gray-500">Status: <span class="text-yellow-500 font-medium">Pending</span></p>
                        </div>
                        <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                            Upload
                        </button>
                    </div>
                    <!-- Checklist Item -->
                    <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg shadow-sm">
                        <div>
                            <h3 class="text-lg font-semibold">Final OJT Report</h3>
                            <p class="text-sm text-gray-500">Status: <span class="text-red-500 font-medium">Rejected</span></p>
                        </div>
                        <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                            Upload
                        </button>
                    </div>
                    <!-- Checklist Item -->
                    <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg shadow-sm">
                        <div>
                            <h3 class="text-lg font-semibold">OJT Performance Evaluation (1st)</h3>
                            <p class="text-sm text-gray-500">Status: <span class="text-red-500 font-medium">Rejected</span></p>
                        </div>
                        <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                            Upload
                        </button>
                    </div>
                    <!-- Checklist Item -->
                    <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg shadow-sm">
                        <div>
                            <h3 class="text-lg font-semibold">OJT Performance Evaluation (2nd)</h3>
                            <p class="text-sm text-gray-500">Status: <span class="text-red-500 font-medium">Rejected</span></p>
                        </div>
                        <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                            Upload
                        </button>
                    </div>
                </div>
            </div>

        </section>
    </main>
</body>
</html>
