<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users";

// Enable mysqli exceptions
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // include logger for consistent logging
    include_once __DIR__ . '/logger.php';
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
} catch (mysqli_sql_exception $e) {
    if (function_exists('log_event')) {
        log_event('DB_CONNECTION_ERROR', $e->getMessage());
    } else {
        error_log("Database connection error: " . $e->getMessage());
    }
    $conn = null; // Set to null to indicate connection failure, allowing graceful handling in other files
}

?>
