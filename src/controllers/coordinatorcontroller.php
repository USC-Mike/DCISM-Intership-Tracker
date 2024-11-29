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
        $conditions[] = "(full_name LIKE ? OR student_id LIKE ? OR company_name LIKE ?)";
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
    $query = "SELECT reports.id, students.full_name, reports.date, students.required_hours, reports.hours_worked,
    (reports.hours_worked / students.required_hours) * 100 AS progress_percentage, 
     reports.work_description, reports.report_status, reports.report_type
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
