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

// Get the user ID from session
$userId = $_SESSION['user_id'];

// Check database connection
if (!isset($pdo)) {
    die('Database connection failed.');
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Update personal information
    if (isset($_POST['coordinatorinfo'])) {
        $fullName = $_POST['full_name'];
        $email = $_POST['email'];
        $courseCoordinator = $_POST['coursecoordinator'];
        $userId = $_SESSION['user_id'];

        // Perform database update query
        $stmt = $pdo->prepare("UPDATE coordinators SET full_name = ?, email = ?, course_coordinator = ? WHERE id = ?");
        $stmt->execute([$fullName, $email, $courseCoordinator, $userId]);  // Pass the parameters as an array
    
        // Redirect with success
        header("Location: ../../public/coordinator/profile.php?personal_success=1");
        exit();
    }
}

//var_dump($_GET);

// Handle GET requests (for displaying student data)
function fetchStudents($filters = []) {
    global $pdo;

    $query = "SELECT id, full_name, year_level, course, company_name, status FROM students";
    $conditions = [];
    $params = [];

    // Add conditions based on filters
    if (!empty($filters['search'])) {
        $conditions[] = "(full_name LIKE ? OR id LIKE ? OR company_name LIKE ?)";
        $params[] = '%' . $filters['search'] . '%';
        $params[] = '%' . $filters['search'] . '%';
        $params[] = '%' . $filters['search'] . '%';
    }

    if (!empty($filters['status'])) {
        $conditions[] = "status = ?";
        $params[] = $filters['status'];
    }

    if (!empty($filters['year_level'])) {
        $conditions[] = "year_level = ?";
        $params[] = $filters['year_level'];
    }

    if (!empty($filters['company'])) {
        $conditions[] = "company_name = ?";
        $params[] = $filters['company'];
    }

    // Append conditions to query
    if (!empty($conditions)) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    // Execute query
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching students: " . $e->getMessage();
        exit();
    }   
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'approve') {
    $reportId = $_POST['report_id'];
    
    // Update the report status to "Approved"
    $query = "UPDATE reports SET status = 'Approved' WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $reportId);
    $stmt->execute();
    
    // Redirect back to the report management page
    header('Location: report_management.php');
    exit();
}

// student management side
// Function to fetch reports
function fetchReports($filters = []) {
    global $pdo;

    // Base query for reports
    $query = "SELECT reports.id, students.full_name, reports.date, reports.hours_worked, reports.work_description, reports.report_status
              FROM reports
              JOIN students ON reports.user_id = students.id";
    $conditions = [];
    $params = [];

    // Apply filters if any
    if (!empty($filters['search'])) {
        $search = "%" . $filters['search'] . "%";
        $conditions[] = "(students.full_name LIKE ? OR students.id LIKE ? OR students.company_name LIKE ?)";
        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
    }

    if (!empty($filters['date'])) {
        $conditions[] = "reports.date = ?";
        $params[] = $filters['date'];
    }

    if (!empty($filters['report_status'])) {
        $conditions[] = "reports.report_status = ?";
        $params[] = $filters['status'];
    }

    // Combine base query with conditions
    if (count($conditions) > 0) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    // Prepare and execute query
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    // Fetch all reports
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Student Mngmt
// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) && isset($_GET['status'])) {
    $studentId = $_GET['id'];
    $newStatus = $_GET['status'];

    // Validate the status
    $validStatuses = ['active', 'completed', 'on-hold'];
    if (in_array($newStatus, $validStatuses)) {
        // Update the student's status in the database
        $stmt = $pdo->prepare("UPDATE students SET status = ? WHERE id = ?");
        $stmt->execute([$newStatus, $studentId]);

        // Redirect back to the student management page after the update
        header("Location: student_management.php?status_update_success=1");
        exit();
    }
}

// Student Mngmt
// Handle GET request to fetch reports
$filters = [];

if (isset($_GET['search'])) {
    $filters['search'] = $_GET['search'];
}

if (isset($_GET['date'])) {
    $filters['date'] = $_GET['date'];
}

if (isset($_GET['report_status'])) {
    $filters['status'] = $_GET['report_status'];
}
// Student Mngmt
// Fetch reports based on filters
$reports = fetchReports($filters);
// Student Mngmt
// Fetch filters from GET request
$filters = [
    'search' => $_GET['search'] ?? null,
    'status' => $_GET['status'] ?? null,
    'year_level' => $_GET['year_level'] ?? null,
    'company' => $_GET['company'] ?? null,
];

// Student Mngmt
// Fetch students based on filters
$students = fetchStudents($filters);

// report management side
// Function to fetch reports
function fetchStudentReports($filters = []) {
    global $pdo;

    // Base query for reports
    $query = "SELECT reports.id, students.full_name, reports.date, reports.hours_worked, reports.work_description, reports.report_status, reports.report_type
              FROM reports
              JOIN students ON reports.user_id = students.id";
    $conditions = [];
    $params = [];

    // Apply filters if any
    if (!empty($filters['search'])) {
        $search = "%" . $filters['search'] . "%";
        $conditions[] = "(students.full_name LIKE ? OR students.id LIKE ? OR students.company_name LIKE ?)";
        $params[] = $search;
        $params[] = $search;
        $params[] = $search;
    }

    if (!empty($filters['date'])) {
        $conditions[] = "reports.date = ?";
        $params[] = $filters['date'];
    }

    if (!empty($filters['report_status'])) {
        $conditions[] = "reports.report_status = ?";
        $params[] = $filters['report_status'];
    }

    if (!empty($filters['report_type'])) {
        $conditions[] = "reports.report_type = ?";
        $params[] = $filters['report_type'];
    }

    // Combine base query with conditions
    if (count($conditions) > 0) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    // Prepare and execute query
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    // Fetch all reports
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// report Mngmt
// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) && isset($_GET['report_status'])) {
    $studentId = $_GET['id'];
    $newStatus = $_GET['report_status'];

    // Validate the status
    $validStatuses = ['Pending', 'Approved', 'Rejected'];
    if (in_array($newStatus, $validStatuses)) {
        // Update the student's status in the database
        $stmt = $pdo->prepare("UPDATE reports SET report_status = ? WHERE id = ?");
        $stmt->execute([$newStatus, $studentId]);

        // Redirect back to the report management page after the update
        header("Location: report_management.php?status_update_success=1");
        exit();
    }
}


// Handle GET request to fetch reports
$filters = [];

if (isset($_GET['search'])) {
    $filters['search'] = $_GET['search'];
}

if (isset($_GET['date'])) {
    $filters['date'] = $_GET['date'];
}

if (isset($_GET['report_status'])) {
    $filters['report_status'] = $_GET['report_status'];
}

if (isset($_GET['report_type'])) {
    $filters['report_type'] = $_GET['report_type'];
}

// Fetch filters from GET request
$filters = [
    'search' => $_GET['search'] ?? null,
    'date' => $_GET['date'] ?? null,
    'report_type' => $_GET['report_type'] ?? null,
    'report_status' => $_GET['report_status'] ?? null,
];

$reportsq = fetchStudentReports($filters);

// FIle handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '/../../storage/documents/';
        $fileName = basename($_FILES['document']['name']);
        $targetFilePath = $uploadDir . $fileName;

        // Validate file type
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        if ($fileType === 'pdf') {
            if (move_uploaded_file($_FILES['document']['tmp_name'], $targetFilePath)) {
                // Insert into the database
                $studentId = $_POST['student_id']; // Replace with actual student ID
                $documentName = $fileName;

                $sql = "INSERT INTO documents (student_id, document_name, document_path, document_status)
                        VALUES ('$studentId', '$documentName', '$targetFilePath', 'Pending')";
                if ($db->query($sql)) {
                    echo "Document uploaded successfully!";
                } else {
                    echo "Database error: " . $db->error;
                }
            } else {
                echo "Failed to move uploaded file.";
            }
        } else {
            echo "Only PDF files are allowed.";
        }
    } else {
        echo "Error uploading file.";
    }
}

// Fetch counts for dashboard
// Fetch the number of active students
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE status = 'active'");
    $stmt->execute();
    $activeStudents = $stmt->fetchColumn();
} catch (PDOException $e) {
    echo "Error fetching active students: " . $e->getMessage();
    $activeStudents = 0;
}

// Fetch the number of pending documents
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM documents WHERE document_status = 'Pending'");
    $stmt->execute();
    $pendingDocuments = $stmt->fetchColumn();
} catch (PDOException $e) {
    echo "Error fetching pending documents: " . $e->getMessage();
    $pendingDocuments = 0;
}

// Fetch the number of pending reports
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reports WHERE report_status = 'Pending'");
    $stmt->execute();
    $pendingReports = $stmt->fetchColumn();
} catch (PDOException $e) {
    echo "Error fetching pending reports: " . $e->getMessage();
    $pendingReports = 0;
}

// Send notification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['send_notification'])) {
        $recipient = $_POST['recipient'];
        $message = $_POST['message'];
        $senderId = $_SESSION['user_id']; // Coordinator ID

        if (empty($message)) {
            echo "Error: Message cannot be empty.";
            exit();
        }

        $senderId = $_SESSION['user_id']; // Coordinator ID

        try {
            if ($recipient === 'all') {
                // Notification for all students
                $stmt = $pdo->prepare("INSERT INTO notifications (sender_id, message) VALUES (?, ?)");
                $stmt->execute([$senderId, $message]);
            } elseif ($recipient === 'specific') {
                // Notification for a specific student
                $recipientId = $_POST['student_id'] ?? null;

                if (empty($recipientId)) {
                    echo "Error: Student must be selected for specific notifications.";
                    exit();
                }

                $stmt = $pdo->prepare("INSERT INTO notifications (recipient_id, sender_id, message) VALUES (?, ?, ?)");
                $stmt->execute([$recipientId, $senderId, $message]);
            }
            header("Location: ../../public/coordinator/notifications.php?send_success=1");
            exit();
        } catch (PDOException $e) {
            echo "Error sending notification: " . $e->getMessage();
            exit();
        }
    }
}

// Fetch notifications
function fetchNotifications() {
    global $pdo;

    try {
        $stmt = $pdo->prepare("
            SELECT n.id, n.message, n.date_sent, 
                   s.full_name AS recipient_name
            FROM notifications n
            LEFT JOIN students s ON n.recipient_id = s.id
            ORDER BY n.date_sent DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching notifications: " . $e->getMessage();
        return [];
    }
}

// Fetch notifications to use in the view
$notifications = fetchNotifications();


// Resend and delete functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $notificationId = $_POST['notification_id'];

        if ($_POST['action'] === 'resend') {
            try {
                // Resend notification by duplicating it
                $stmt = $pdo->prepare("
                    INSERT INTO notifications (recipient_id, sender_id, message, date_sent)
                    SELECT recipient_id, sender_id, message, NOW()
                    FROM notifications
                    WHERE id = ?
                ");
                $stmt->execute([$notificationId]);
                header("Location: ../../public/coordinator/notifications.php?resend_success=1");
                exit();
            } catch (PDOException $e) {
                echo "Error resending notification: " . $e->getMessage();
                exit();
            }
        }

        if ($_POST['action'] === 'delete') {
            try {
                // Delete notification
                $stmt = $pdo->prepare("DELETE FROM notifications WHERE id = ?");
                $stmt->execute([$notificationId]);
                header("Location: ../../public/coordinator/notifications.php?delete_success=1");
                exit();
            } catch (PDOException $e) {
                echo "Error deleting notification: " . $e->getMessage();
                exit();
            }
        }
    }
}

/*
// Fetching uploaded documents
function fetchDocuments($status = null) {
    global $pdo;

    $query = "SELECT d.id, d.student_id, s.full_name AS student_name, d.document_name, d.document_type, 
              d.document_status, d.date_uploaded, d.document_path
              FROM documents d
              JOIN students s ON d.student_id = s.id";
    $params = [];

    if ($status) {
        $query .= " WHERE d.document_status = ?";
        $params[] = $status;
    }

    $query .= " ORDER BY d.date_uploaded DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$pendingDocuments = fetchDocuments('Pending');
$approvedDocuments = fetchDocuments('Approved');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $documentId = $_POST['document_id'];
    $action = $_POST['action'];

    if ($action === 'approve' || $action === 'reject') {
        $status = $action === 'approve' ? 'Approved' : 'Rejected';
        if (updateDocumentStatus($documentId, $status)) {
            header("Location: document_management.php?success=1");
            exit();
        } else {
            echo "Failed to update document status.";
        }
    }
}

function updateDocumentStatus($documentId, $status) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("UPDATE documents SET document_status = ? WHERE id = ?");
        $stmt->execute([$status, $documentId]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}
*/

function fetchDocuments($status = null) {
    global $pdo;

    $query = "SELECT d.id, d.student_id, s.full_name AS student_name, d.document_name, d.document_type, 
                     d.document_status, d.date_uploaded, d.document_path
              FROM documents d
              JOIN students s ON d.student_id = s.id";
    $params = [];

    if ($status) {
        $query .= " WHERE d.document_status = ?";
        $params[] = $status;
    }

    $query .= " ORDER BY d.date_uploaded DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch documents for specific statuses
$forApprovalDocuments = fetchDocuments('For Approval');
$approvedDocuments = fetchDocuments('Approved');
$rejectedDocuments = fetchDocuments('Rejected');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $documentId = $_POST['document_id'];
    $action = $_POST['action'];

    if (in_array($action, ['approve', 'reject', 'reset'])) {
        $status = match ($action) {
            'approve' => 'Approved',
            'reject' => 'Rejected',
            'reset' => 'For Approval', // Reset to 'For Approval' if needed
        };

        if (updateDocumentStatus($documentId, $status)) {
            header("Location: ../../public/coordinator/document_management.php?success=1");
            exit();
        } else {
            echo "Failed to update document status.";
        }
    }
}

function updateDocumentStatus($documentId, $status) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("UPDATE documents SET document_status = ? WHERE id = ?");
        $stmt->execute([$status, $documentId]);
        return true;
    } catch (PDOException $e) {
        error_log("Update Document Status Error: " . $e->getMessage());
        return false;
    }
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && isset($_POST['document_id'])) {
        $documentId = $_POST['document_id'];
        $action = $_POST['action'];

        // Determine status based on the action
        $status = $action == 'Approved' ? 'Approved' : 'Rejected';

        // Update document status
        if (updateDocumentStatus($documentId, $status)) {
            // Optionally, redirect to refresh the page after updating
            header('Location: ../../public/coordinator/document_management.php');
            exit;
        } else {
            echo "<p class='text-red-500'>There was an error updating the document status.</p>";
        }
    }
}