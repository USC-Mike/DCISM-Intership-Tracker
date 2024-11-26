<?php
// Database configuration
$host = 'localhost';   // Database host
$dbname = 'internship_tracker'; // Database name
$username = 'root';     // Database username (change if using another user)
$password = '';         // Database password (leave empty for default XAMPP setup)

try {
    // Create a PDO instance for database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception for debugging
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Optionally, set the default fetch mode
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // If the connection fails, display an error
    die("Connection failed: " . $e->getMessage());
}

// Function to check if a student or coordinator exists based on email
function userExists($email, $role) {
    global $pdo;
    $table = $role === 'student' ? 'students' : 'coordinators';
    $sql = "SELECT * FROM $table WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    return $stmt->fetch();
}

// Function to insert a new session into the sessions table
function createSession($user_id, $user_role, $session_token, $expires_at) {
    global $pdo;
    $sql = "INSERT INTO sessions (user_id, user_role, session_token, expires_at)
            VALUES (:user_id, :user_role, :session_token, :expires_at)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'user_id' => $user_id,
        'user_role' => $user_role,
        'session_token' => $session_token,
        'expires_at' => $expires_at
    ]);
    return $pdo->lastInsertId(); // Return the last inserted session id
}

// Function to fetch session by token
function getSessionByToken($session_token) {
    global $pdo;
    $sql = "SELECT * FROM sessions WHERE session_token = :session_token";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['session_token' => $session_token]);
    return $stmt->fetch();
}

// Function to delete a session by its ID
function deleteSession($session_id) {
    global $pdo;
    $sql = "DELETE FROM sessions WHERE id = :session_id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute(['session_id' => $session_id]);
}

// Function to validate user credentials (example)
function validateUser($email, $password, $role) {
    global $pdo;
    $table = $role === 'student' ? 'students' : 'coordinators';
    $sql = "SELECT * FROM $table WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        return $user; // Return the user if credentials match
    }
    return false; // Return false if credentials don't match
}

// Function to register a new student or coordinator
function registerUser($fullname, $email, $password, $role, $yrlevel, $course) {
    global $pdo;
    // Determine which table to insert based on role (students or coordinators)
    $table = $role === 'student' ? 'students' : 'coordinators';
    
    // Hash the password before saving
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // SQL query to insert a new user into the appropriate table
    $sql = "INSERT INTO $table (full_name, email, password, role, year_level, course) 
            VALUES (:fullname, :email, :password, :role, :yrlevel, :course)";
    
    // Prepare the statement and execute it
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'fullname' => $fullname,
        'email' => $email,
        'password' => $hashed_password,
        'role' => $role,      // Role is also needed to be inserted
        'yrlevel' => $yrlevel,
        'course' => $course
    ]);
}
?>
