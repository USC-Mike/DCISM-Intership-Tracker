<?php
require_once '../../src/controllers/coordinatorcontroller.php';

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
            <?php if (isset($_GET['success'])): ?>
    <div class="bg-green-500 text-white p-4 rounded-lg mb-4">
        Document uploaded successfully!
    </div>
<?php endif; ?>

            <!-- Submission Guidelines -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold mb-4">Submission Guidelines</h2>
                <ul class="list-disc pl-6 text-gray-600 space-y-2">
                    <li>Ensure all documents are in PDF format.</li>
                    <li>Follow the naming convention: Lastname_Firstname_DocumentName.</li>
                    <li>Deadlines are strictly enforced; submit on or before the due date.</li>
                </ul>
            </div>

            <?php if (isset($_GET['upload_success'])): ?>
    <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-4">
        Document uploaded successfully.
    </div>
<?php elseif (isset($_GET['upload_error'])): ?>
    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">
        <?= htmlspecialchars($_GET['upload_error']) ?>
    </div>
<?php endif; ?>

            
<!-- Document Checklist -->
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-semibold mb-4">Document Checklist</h2>
    <p class="text-gray-600 mb-6">Below is the list of required documents and their submission statuses. Click "Upload" to submit your files.</p>
    <div class="space-y-4">
        <?php if (!empty($documentTypes) && is_array($documentTypes)): ?>
            <?php foreach ($documentTypes as $type): ?>
                <?php
                // Find the document in the fetched list
                $document = array_filter($documents, fn($doc) => $doc['document_type'] === $type);
                $status = $document ? $document[array_key_first($document)]['document_status'] : 'Pending';
                $statusColor = $status === 'Pending' ? 'text-yellow-500' : ($status === 'Approved' ? 'text-green-500' : 'text-red-500');
                ?>
                <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg shadow-sm">
                    <div>
                        <h3 class="text-lg font-semibold"><?= htmlspecialchars($type) ?></h3>
                        <p class="text-sm text-gray-500">Status: <span class="<?= $statusColor ?> font-medium"><?= htmlspecialchars($status) ?></span></p>
                    </div>
                    <form method="POST" action="../../src/controllers/studentcontroller.php" enctype="multipart/form-data">
                        <input type="hidden" name="document_type" value="<?= htmlspecialchars($type) ?>">
                        <input type="file" name="document" accept="application/pdf" class="hidden" id="upload-<?= htmlspecialchars($type) ?>">
                        <label for="upload-<?= htmlspecialchars($type) ?>" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 cursor-pointer">
                            Upload
                        </label>
                        <input type="hidden" name="upload_document" value="1">
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-gray-600">No document types available at this time.</p>
        <?php endif; ?>
    </div>
</div>


        </section>
    </main>
</body>
</html>
