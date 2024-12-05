<?php
session_start();
include(__DIR__ . '/.././config/db.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirect if user is not logged in
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
} else {
    // Handle case where user is not logged in or session expired
    echo "User not logged in.";
    exit();
}

$userId = $_SESSION['user_id'];

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
            $status = 'Pending';

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
                $stmt = $pdo->prepare("INSERT INTO reports (user_id, report_type, report_status,  date, week_number, hours_worked, work_description) 
                                       VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$userId, $reportType, $status, $date, $week_number, $hoursWorked, $workDescription]);

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


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_document'])) {
        $studentId = $_SESSION['user_id'];
        $documentType = trim($_POST['document_type']);
        $file = $_FILES['document'];
    
        var_dump($documentType); // Check what is being passed for document_type

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
    
            // Retrieve student's full name from the database
            $stmt = $pdo->prepare("SELECT full_name FROM students WHERE id = ?");
            $stmt->execute([$studentId]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$student) {
                throw new Exception("Student not found.");
            }
    
            $fullName = preg_replace('/[^a-zA-Z0-9_ ]/', '', $student['full_name']); // Clean name for file
            $documentName = preg_replace('/[^a-zA-Z0-9_]/', '_', $documentType); // Clean document type for file
            $fileName = $fullName . "_" . $documentName . ".pdf"; // Combine full name and document name
    
            $targetFilePath = $uploadDir . $fileName;
            error_log("Target File Path: {$targetFilePath}");
    
            // Move file to the uploads directory
            if (!move_uploaded_file($file['tmp_name'], $targetFilePath)) {
                error_log("move_uploaded_file failed: tmp_name={$file['tmp_name']}, target={$targetFilePath}");
                throw new Exception("Failed to move uploaded file.");
            } else {
                error_log("File successfully moved to: {$targetFilePath}");
            }
    
            // Read the file as a BLOB
            $fileContent = file_get_contents($file['tmp_name']);
            
            // Insert or update the document record in the database (storing the file as BLOB)
            $stmt = $pdo->prepare("
                INSERT INTO documents (student_id, document_name, document_type, document_path, uploaded_file, document_status, date_uploaded)
                VALUES (?, ?, ?, ?, ?, 'For Approval', NOW())
                ON DUPLICATE KEY UPDATE 
                    document_name = VALUES(document_name), 
                    document_path = VALUES(document_path), 
                    uploaded_file = VALUES(uploaded_file), 
                    document_status = 'For Approval', 
                    date_uploaded = NOW()
            ");
            $stmt->execute([
                $studentId,
                $fileName,             // Document name based on student's full name and document type
                $documentType,         // Document type
                $targetFilePath,       // File path where the file is saved
                $fileContent           // File content as BLOB
            ]);
            error_log("Document record inserted/updated in database.");
    
            // Redirect with success
            header("Location: ../../public/student/checklist.php?upload_success=1");
        } catch (Exception $e) {
            error_log("Upload Error: " . $e->getMessage());
            header("Location: ../../public/student/checklist.php?upload_error=" . urlencode($e->getMessage()));
            exit();
        }
    }
    

    try {
        // Fetch required hours for the student
        $stmt = $pdo->prepare("SELECT required_hours FROM students WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$student) {
            throw new Exception("Student record not found.");
        }
    
        $requiredHours = $student['required_hours'];
    
        // Fetch total hours worked from the reports table
        $stmt = $pdo->prepare("SELECT SUM(hours_worked) AS total_hours_worked FROM reports WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $report = $stmt->fetch(PDO::FETCH_ASSOC);
    
        $totalHoursWorked = $report['total_hours_worked'] ?? 0;
    
        // Calculate the progress percentage
        $progressPercentage = $requiredHours > 0 ? ($totalHoursWorked / $requiredHours) * 100 : 0;
        $progressPercentage = min(100, max(0, $progressPercentage)); // Clamp value between 0 and 100
    } catch (Exception $e) {
        $requiredHours = 0;
        $totalHoursWorked = 0;
        $progressPercentage = 0;
        error_log("Error fetching student progress: " . $e->getMessage());
    }



    function fetchReportTemplates($searchQuery = '') {
        // Define the templates directory path
        $templatesDir = __DIR__ . '/../../report_templates/';
        if ($templatesDir === false || !is_dir($templatesDir)) {
            die("Templates directory does not exist or is inaccessible.");
        }
        
    
        // Fetch templates
        $templates = [];
        foreach (glob($templatesDir . '/*') as $filePath) {
            if (is_file($filePath)) {
                $fileName = basename($filePath);
                $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
    
                // Apply search filter
                if ($searchQuery && stripos($fileName, $searchQuery) === false) {
                    continue;
                }
    
                $templates[] = [
                    'name' => $fileName,
                    'type' => strtoupper($fileType),
                    'path' => $filePath,
                ];
            }
        }
    
        return $templates;
    }

    // Function to generate the base URL dynamically
// Function to generate the base URL dynamically
function base_url($path = '') {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $baseDir = '/DCISM-Intership-Tracker/src/controllers/';
    return $protocol . '://' . $host . $baseDir . $path;
}


if (isset($_GET['file'])) {
    $file = $_GET['file'];
    $filePath = '/Applications/XAMPP/xamppfiles/htdocs/DCISM-Intership-Tracker/report_templates/' . basename($file);  // Sanitize file name for security

    if (file_exists($filePath)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        readfile($filePath);
        exit;
    } else {
        echo 'File not found.';
    }
}

function fetchStudentNotifications($studentId) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("
            SELECT n.id, n.message, n.date_sent, c.full_name AS coordinator_name
            FROM notifications n
            LEFT JOIN coordinators c ON n.sender_id = c.id
            WHERE n.recipient_id = :studentId OR n.recipient_id IS NULL
            ORDER BY n.date_sent DESC
        ");
        $stmt->execute([':studentId' => $studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching notifications: " . $e->getMessage());
        return [];
    }
}


function countUnreadNotifications($studentId) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("
            SELECT COUNT(*) 
            FROM notifications 
            WHERE (recipient_id = :studentId OR recipient_id IS NULL)
              AND is_read = 0
        ");
        $stmt->execute([':studentId' => $studentId]);
        return (int)$stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Error counting unread notifications: " . $e->getMessage());
        return 0;
    }
}

function markNotificationAsRead($notificationId, $studentId) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("
            UPDATE notifications 
            SET is_read = 1 
            WHERE id = :id AND (recipient_id = :studentId OR recipient_id IS NULL)
        ");
        $stmt->execute([':id' => $notificationId, ':studentId' => $studentId]);
        return true;
    } catch (PDOException $e) {
        error_log("Error marking notification as read: " . $e->getMessage());
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_as_read'])) {
    $notificationId = intval($_POST['notification_id']);
    $studentId = $_SESSION['user_id'];

    if (markNotificationAsRead($notificationId, $studentId)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}
