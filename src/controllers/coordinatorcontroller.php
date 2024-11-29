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

// Fetch filters from GET request
$filters = [
    'search' => $_GET['search'] ?? null,
    'status' => $_GET['status'] ?? null,
    'year_level' => $_GET['year_level'] ?? null,
    'company' => $_GET['company'] ?? null,
];

// Fetch students based on filters
$students = fetchStudents($filters);

