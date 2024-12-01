<?php
require_once '../../src/controllers/studentcontroller.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../public/login.php');
    exit();
}

// Display full name
$fullName = $_SESSION['full_name'] ?? 'Guest'; // Fallback to "Guest" if session is not set

// Define the required document types
$requiredDocumentTypes = [
    'Personal and Work Information Document',
    'Curriculum Vitae',
    'Parent’s Consent',
    'Endorsement Letter',
    'Company MOA',
    'Student MOA',
    'Final OJT Report',
    'OJT Performance Evaluation (1st)',
    'OJT Performance Evaluation (2nd)',
];

// Fetch all documents with their statuses for the current student
$documents = fetchStudentDocumentsWithStatuses($_SESSION['user_id'], $requiredDocumentTypes);


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
        <?php if (isset($_GET['upload_success'])): ?>
    <div class="bg-green-500 text-white p-4 rounded-lg mb-4">
        Document uploaded successfully!
    </div>
<?php elseif (isset($_GET['upload_error'])): ?>
    <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
        <?= htmlspecialchars($_GET['upload_error']) ?>
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
            <!-- Document Checklist -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold mb-4">Document Checklist</h2>
                <p class="text-gray-600 mb-6">Below is the list of required documents and their submission statuses. Click "Upload" to submit your files.</p>
                <div class="space-y-4">
    <?php foreach ($documents as $document): ?>
        <?php
        $statusColor = $document['status'] === 'Pending' ? 'text-yellow-500' :
                       ($document['status'] === 'For Approval' ? 'text-blue-500' :
                       ($document['status'] === 'Approved' ? 'text-green-500' : 'text-red-500'));
        ?>
        <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg shadow-sm">
            <div>
                <h3 class="text-lg font-semibold"><?= htmlspecialchars($document['type']) ?></h3>
                <p class="text-sm text-gray-500">Status: 
                    <span class="<?= $statusColor ?> font-medium"><?= htmlspecialchars($document['status']) ?></span>
                </p>
                            </div>
            <!-- Display selected file name -->
            <p class="text-sm text-gray-600 mt-2" id="file-name-<?= htmlspecialchars($document['type']) ?>">No file selected</p>
            
            <form method="POST" action="../../src/controllers/studentcontroller.php" enctype="multipart/form-data" class="flex items-center space-x-4">
                <input type="hidden" name="document_type" value="<?= htmlspecialchars($document['type']) ?>">
                
                <!-- File Input -->
                <label for="upload-<?= htmlspecialchars($document['type']) ?>" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 cursor-pointer">
                    Select File
                </label>
                <input type="file" name="document" accept="application/pdf" id="upload-<?= htmlspecialchars($document['type']) ?>" class="hidden" 
                       onchange="updateFileName('<?= htmlspecialchars($document['type']) ?>', this)">
                
                <!-- Submit Button -->
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                    Submit
                </button>

                <!-- Hidden Field to Identify Upload -->
                <input type="hidden" name="upload_document" value="1">
            </form>
        </div>
    <?php endforeach; ?>
</div>

            </div>

        </section>
    </main>
    <script>
    function updateFileName(documentType, input) {
        const fileName = input.files[0]?.name || "No file selected";
        document.getElementById(`file-name-${documentType}`).textContent = fileName;
    }
</script>
</body>
</html>
