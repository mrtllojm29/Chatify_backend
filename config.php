// config.php
<?php
// Database connection settings
$host = 'localhost';
$dbname = 'chatify';  // Your database name
$username = 'root';  // Your database username (default for XAMPP)
$password = '';  // Your database password (default for XAMPP is empty)

try {
    // Create a PDO instance (database connection)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // In case of an error, display an error message
    die("Connection failed: " . $e->getMessage());
}
?>
