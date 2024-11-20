<?php
// Add CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");  // Ensure the response is JSON

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0); // Exit pre-flight request
}

// Database connection
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect input data
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Email and password are required.']);
        exit;
    }

    // Query database for user
    try {
        $stmt = $pdo->prepare("SELECT * FROM chatify_accounts WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // User exists, now check the password
            if (password_verify($password, $user['password'])) {
                echo json_encode(['status' => 'success', 'message' => 'Login successful!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.']);
            }
        } else {
            // User does not exist
            echo json_encode(['status' => 'not_found', 'message' => 'Account does not exist. Please register first.']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database query error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
