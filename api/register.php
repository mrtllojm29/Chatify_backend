// Add CORS headers
<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle pre-flight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0); // Exit pre-flight request
}

// Database connection
require_once '../config.php';

// Ensure the PDO connection is established
if (!isset($pdo)) {
    die("Database connection failed.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect input data
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    // Hash password for security
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert into database
    $stmt = $pdo->prepare("INSERT INTO chatify_accounts (user_name, email, password, date_created) VALUES (?, ?, ?, NOW())");

    if ($stmt->execute([$username, $email, $hashedPassword])) {
        echo json_encode(['status' => 'success', 'message' => 'Registration successful.']);
    } else {
        // Enhanced error message with details
        $errorInfo = $stmt->errorInfo();
        echo json_encode(['status' => 'error', 'message' => 'Failed to register user. ' . $errorInfo[2]]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
