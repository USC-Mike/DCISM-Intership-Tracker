<?php
session_start();
include(__DIR__ . '/.././config/db.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../public/login.php');
    exit();
}

// Check database connection
if (!isset($pdo)) {
    die('Database connection failed.');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Update personal information
        if (isset($_POST['userinfo'])) {
            $fullName = $_POST['full_name'];
            $mobile = $_POST['mobile'];
            $email = $_POST['email'];
            $course = $_POST['course'];
            $schoolYr = $_POST['schoolyear'];
            $coordinator = $_POST['coordinator'];
            $userId = $_SESSION['user_id'];

            $stmt = $pdo->prepare("UPDATE students SET full_name = ?, mobile = ?, email = ?, course = ?, schoolyear = ?, coordinator = ? WHERE id = ?");
            $stmt->execute([$fullName, $mobile, $email, $course, $schoolYr, $coordinator, $userId]);

            // Redirect with success
            header("Location: ../../public/student/profile.php?personal_success=1");
            exit();
        }

        // Update company information
        if (isset($_POST['companyinfo'])) {
            $companyName = $_POST['company_name'];
            $supervisorName = $_POST['supervisor_name'];
            $companyAddress = $_POST['company_address'];
            $companyEmail = $_POST['company_email'];
            $supervisorContact = $_POST['supervisor_contact'];
            $workschedule = $_POST['work_schedule'];
            $userId = $_SESSION['user_id'];

            $stmt = $pdo->prepare("UPDATE students SET company_name = ?, supervisor_name = ?, company_address = ?, company_email = ?, supervisor_contact = ?, work_schedule = ? WHERE id = ?");
            $stmt->execute([$companyName, $supervisorName, $companyAddress, $companyEmail, $supervisorContact , $userId]);

            // Redirect with success
            header("Location: ../../public/student/profile.php?company_success=1");
            exit();
        }

        // Handle report submission
        if (isset($_POST['report_type'])) {
            $reportType = $_POST['report_type']; // 'daily' or 'weekly'
            $date = $_POST['date'];
            $userId = $_SESSION['user_id'];
            $hoursWorked = $_POST['hours'];
            $workDescription = $_POST['work-description'];

            // Sanitize and validate inputs
            $date = htmlspecialchars($date);
            $workDescription = htmlspecialchars($workDescription);

            // Handle the week number based on report type
            $week_number = null;
            if ($reportType === 'weekly') {
                $week_number = $_POST['week-number']; // Only set if report type is 'weekly'
            }

            // Validate that the week_number is an integer if report type is weekly
            if ($reportType === 'weekly' && !empty($week_number) && is_numeric($week_number)) {
                $week_number = (int)$week_number; // Convert to integer if valid
            } elseif ($reportType === 'weekly' && empty($week_number)) {
                // Redirect back with error message if week number is required
                header("Location: ../../public/student/reports.php?error=week_required");
                exit();
            }

            // Prepare and execute the SQL statement
            try {
                $stmt = $pdo->prepare("INSERT INTO reports (user_id, report_type, date, week_number, hours_worked, work_description) 
                                       VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$userId, $reportType, $date, $week_number, $hoursWorked, $workDescription]);

                // Redirect with success notification
                header("Location: ../../public/student/reports.php?success=1");
                exit();
            } catch (PDOException $e) {
                // Error handling in case the insert fails
                echo "Error: " . $e->getMessage();
            }
        }

    } catch (PDOException $e) {
        // Database error handling
        echo "Database error: " . $e->getMessage();
        exit();
    }
}


/*
// Fetch all documents for a student by their ID, including missing ones.

function fetchStudentDocumentsWithStatuses($studentId, $requiredDocumentTypes) {
    global $pdo;

    // Fetch existing documents for the student
    $stmt = $pdo->prepare("SELECT document_type, document_status FROM documents WHERE student_id = ?");
    $stmt->execute([$studentId]);
    $existingDocuments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Map existing documents by type
    $documentStatuses = [];
    foreach ($existingDocuments as $doc) {
        $documentStatuses[$doc['document_type']] = $doc['document_status'];
    }

    // Build the full list of required documents with their statuses
    $allDocuments = [];
    foreach ($requiredDocumentTypes as $type) {
        $status = $documentStatuses[$type] ?? 'Pending'; // Default to 'Pending' if not found
        $allDocuments[] = [
            'type' => $type,
            'status' => $status,
        ];
    }

    return $allDocuments;
}


// Handle document uploads.

function uploadDocument($studentId, $documentType, $file) {
    global $pdo;

    try {
        $uploadDir = __DIR__ . '/../../storage/documents/';
        $fileName = basename($file['name']);
        $targetFilePath = $uploadDir . $fileName;

        // Validate file type
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        if (strtolower($fileType) !== 'pdf') {
            throw new Exception("Only PDF files are allowed.");
        }

        // Move uploaded file to storage directory
        if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            // Insert or update the document in the database
            $stmt = $pdo->prepare("
                INSERT INTO documents (student_id, document_name, document_type, document_path, document_status)
                VALUES (?, ?, ?, ?, 'For Approval')
                ON DUPLICATE KEY UPDATE document_name = VALUES(document_name), document_path = VALUES(document_path), document_status = 'For Approval'
            ");
            
            $stmt->execute([$studentId, $fileName, $documentType, $targetFilePath]);
            return ['success' => true];
        } else {
            throw new Exception("Failed to upload file.");
        }
    } catch (Exception $e) {
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_document'])) {
    $studentId = $_SESSION['user_id']; // Current student ID
    $documentType = $_POST['document_type']; // Document type
    $file = $_FILES['document']; // Uploaded file

    // Call the uploadDocument function from the controller
    $uploadResult = uploadDocument($studentId, $documentType, $file);

    // Redirect back with success or error messages
    if ($uploadResult['success']) {
        header("Location: ../../public/student/checklist.php?upload_success=1");
    } else {
        header("Location: ../../public/student/checklist.php?upload_error=" . urlencode($uploadResult['message']));
    }
    exit();
}

*/


function fetchStudentDocumentsWithStatuses($studentId, $requiredDocumentTypes) {
    global $pdo;

    // Fetch existing documents for the student
    $stmt = $pdo->prepare("SELECT document_type, document_status FROM documents WHERE student_id = ?");
    $stmt->execute([$studentId]);
    $existingDocuments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Map existing documents for easy lookup
    $documentsMap = [];
    foreach ($existingDocuments as $doc) {
        $documentsMap[$doc['document_type']] = $doc['document_status'];
    }

    // Build the complete document list with statuses
    $documents = [];
    foreach ($requiredDocumentTypes as $type) {
        $documents[] = [
            'type' => $type,
            'status' => $documentsMap[$type] ?? 'Pending', // Default to "Pending" if not submitted
        ];
    }

    return $documents;
}
/*
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_document'])) {
    echo "<pre>";
    print_r($_FILES['document']);
    echo "</pre>";
    exit();
}


// Upload document logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_document'])) {
    $studentId = $_SESSION['user_id'];
    $documentType = $_POST['document_type'];
    $file = $_FILES['document'];
    $uploadDir = __DIR__ . '/../../uploads/documents/';
    $fileName = time() . '_' . basename($file['name']); // Unique filename with timestamp
    $targetFilePath = $uploadDir . $fileName;

    try {
        // Ensure the upload directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Create directory if it doesn't exist
        }
        if ($_FILES['document']['size'] > 50 * 1024 * 1024) { // 50MB limit
            throw new Exception("File is too large. Maximum allowed size is 50MB.");
        }
        

        // Check for file upload errors
        // Debugging: Check upload directory
        if (!is_dir($uploadDir)) {
            throw new Exception("Upload directory does not exist: " . $uploadDir);
        }

          // Debugging: Check if file array is populated
        if (empty($file) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("File upload error: " . $file['error']);
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if ($mimeType !== 'application/pdf') {
    throw new Exception("Invalid file type. Only PDF files are allowed.");
}

        // Validate file type
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        if ($fileType !== 'pdf') {
            throw new Exception("Only PDF files are allowed.");
        }


        $uploadDir = __DIR__ . '/../../storage/documents/';
        if (!is_dir($uploadDir)) {
            throw new Exception("Upload directory does not exist: " . $uploadDir);
        }
        if (!is_writable($uploadDir)) {
            throw new Exception("Upload directory is not writable: " . $uploadDir);
        }


        // Move uploaded file to storage directory
        if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            // Insert or update document record in the database
            $stmt = $pdo->prepare("
                INSERT INTO documents (student_id, document_name, document_type, document_path, document_status, date_uploaded)
                VALUES (?, ?, ?, ?, 'For Approval', NOW())
                ON DUPLICATE KEY UPDATE 
                    document_name = VALUES(document_name), 
                    document_path = VALUES(document_path), 
                    document_status = 'For Approval', 
                    date_uploaded = NOW()
            ");
            $stmt->execute([$studentId, $fileName, $documentType, $targetFilePath]);

            // Redirect with success
            header("Location: ../../public/student/checklist.php?upload_success=1");
        } else {
            throw new Exception("Failed to move the uploaded file.");
        }

        if (!move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            throw new Exception(
                "Failed to move uploaded file. tmp_name: {$file['tmp_name']}, target: {$targetFilePath}"
            );
        }
        
    } catch (Exception $e) {
        error_log("Upload Error: " . $e->getMessage());
        header("Location: ../../public/student/checklist.php?upload_error=" . urlencode($e->getMessage()));
        exit();
    }
    
}
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_document'])) {
    $studentId = $_SESSION['user_id'];
    $documentType = trim($_POST['document_type']);
    $file = $_FILES['document'];

    // Define upload directory
    $uploadDir = realpath(__DIR__ . '/../../uploads') . '/';
    error_log("Resolved Upload Directory: {$uploadDir}");

    try {
        // Ensure directory exists
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                throw new Exception("Failed to create upload directory: {$uploadDir}");
            }
            error_log("Upload directory created: {$uploadDir}");
        }

        // Check if directory is writable
        if (!is_writable($uploadDir)) {
            throw new Exception("Upload directory is not writable: {$uploadDir}");
        }

        // Validate file upload
        if (empty($file) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("File upload error: " . $file['error']);
        }

        // Check file size (max 50MB)
        $maxSize = 50 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            throw new Exception("File is too large. Maximum allowed size is 50MB.");
        }

        // Validate file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if ($mimeType !== 'application/pdf') {
            throw new Exception("Invalid file type. Only PDF files are allowed.");
        }

        // Generate unique filename
        $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $file['name']);
        $targetFilePath = $uploadDir . $fileName;
        error_log("Target File Path: {$targetFilePath}");

        // Move file
        if (!move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            error_log("move_uploaded_file failed: tmp_name={$file['tmp_name']}, target={$targetFilePath}");
            throw new Exception("Failed to move uploaded file.");
        } else {
            error_log("File successfully moved to: {$targetFilePath}");
        }

        // Insert or update database record
        $stmt = $pdo->prepare("
            INSERT INTO documents (student_id, document_name, document_type, document_path, document_status, date_uploaded)
            VALUES (?, ?, ?, ?, 'For Approval', NOW())
            ON DUPLICATE KEY UPDATE 
                document_name = VALUES(document_name), 
                document_path = VALUES(document_path), 
                document_status = 'For Approval', 
                date_uploaded = NOW()
        ");
        $stmt->execute([$studentId, $fileName, $documentType, $targetFilePath]);
        error_log("Document record inserted/updated in database.");

        // Redirect with success
        header("Location: ../../public/student/checklist.php?upload_success=1");
    } catch (Exception $e) {
        error_log("Upload Error: " . $e->getMessage());
        header("Location: ../../public/student/checklist.php?upload_error=" . urlencode($e->getMessage()));
        exit();
    }
}
