<?php
session_start();
include(__DIR__ . '/.././config/db.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../public/login.php');
    exit();
}

if (!isset($pdo)) {
    die('Database connection failed.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Update personal information
        if (isset($_POST['userinfo'])) {
            $fullName = $_POST['full_name'];
            $mobile = $_POST['mobile'];
            $email = $_POST['email'];
            $userId = $_SESSION['user_id'];

            $stmt = $pdo->prepare("UPDATE students SET full_name = ?, mobile = ?, email = ? WHERE id = ?");
            $stmt->execute([$fullName, $mobile, $email, $userId]);

            // Redirect with personal info success
            header("Location: ../../public/student/profile.php?personal_success=1");
            exit();
        }

        // Update company information
        if (isset($_POST['companyinfo'])) {
            $companyName = $_POST['company_name'];
            $supervisorName = $_POST['supervisor_name'];
            $companyAddress = $_POST['company_address'];
            $companyEmail = $_POST['company_email'];
            $userId = $_SESSION['user_id'];

            $stmt = $pdo->prepare("UPDATE students SET company_name = ?, supervisor_name = ?, company_address = ?, company_email = ? WHERE id = ?");
            $stmt->execute([$companyName, $supervisorName, $companyAddress, $companyEmail, $userId]);

            // Redirect with company info success
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
    if ($reportType == 'weekly') {
        $week_number = $_POST['week-number']; // Only set if report type is 'weekly'
    }

    // Validate that the week_number is an integer if report type is weekly
    if ($reportType == 'weekly' && !empty($week_number) && is_numeric($week_number)) {
        $week_number = (int)$week_number; // Convert to integer if valid
    } else if ($reportType == 'weekly' && empty($week_number)) {
        // Redirect back with error message
        header("Location: ../../public/student/reports.php?error=week_required");
        exit();
    }

    // Debug: Check if week_number is set and valid
    if ($reportType == 'weekly') {
        echo "Week number: " . $week_number . "<br>";
    }

    // Prepare and execute the SQL statement
    try {
        $stmt = $pdo->prepare("INSERT INTO reports (user_id, report_type, date, week_number, hours_worked, work_description) 
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $reportType, $date, $week_number, $hoursWorked, $workDescription]);

        // Redirect with report success notification
        header("Location: ../../public/student/reports.php?success=1");
        exit();
    } catch (PDOException $e) {
        // Error handling in case the insert fails
        echo "Error: " . $e->getMessage();
    }
}




    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
        exit();
    }

    // Fetch previously submitted reports
    $stmt = $pdo->prepare("SELECT * FROM reports WHERE user_id = ? ORDER BY submitted_at DESC");
    $stmt->execute([$userId]);
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
}


