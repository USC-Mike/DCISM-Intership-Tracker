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


// Fetch all documents for a student by their ID.

function fetchStudentDocuments($studentId) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT document_type, document_status FROM documents WHERE student_id = ?");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching documents: " . $e->getMessage();
        return [];
    }
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
            // Insert document into the database
            $stmt = $pdo->prepare("INSERT INTO documents (student_id, document_name, document_type, document_path, document_status)
                                   VALUES (?, ?, ?, ?, 'Pending')");
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


// Define the list of required document types
$documentTypes = [
    'Personal & Work Plan',
    'Curriculum Vitae',
    'Parent’s Consent',
    'Endorsement Letter',
    'Company MOA',
    'Student MOA',
    'Final OJT Report',
    'OJT Performance Evaluation (1st)',
    'OJT Performance Evaluation (2nd)',
];

// Fetch the uploaded documents for the current student
$documents = fetchStudentDocuments($_SESSION['user_id']);
