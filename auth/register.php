<?php
session_start();
include '../includes/db.php';

if ($conn === null) {
    throw new Exception("Database connection failed.");
}

function isStrongPassword($password) {
    if (strlen($password) < 8) return false;
    if (!preg_match('/[A-Z]/', $password)) return false;
    if (!preg_match('/[a-z]/', $password)) return false;
    if (!preg_match('/[0-9]/', $password)) return false;
    if (!preg_match('/[^A-Za-z0-9]/', $password)) return false;
    return true;
}

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fullname = $_POST['fullname'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (!isStrongPassword($password)) {
            $_SESSION['message'] = "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
            $_SESSION['toastClass'] = "#dc3545"; // Danger color
            header("Location: registration-form.php");
            exit();
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if username or email already exists
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $checkStmt->bind_param("ss", $email, $username);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $_SESSION['message'] = "Username or Email already exists";
            $_SESSION['toastClass'] = "#dc3545"; // Danger color
            header("Location: registration-form.php");
            exit();
        } else {
            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO users (fullname, username, email, passwd) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $fullname, $username, $email, $hashedPassword);

            if ($stmt->execute()) {
                if (function_exists('log_event')) {
                    log_event('REGISTRATION_SUCCESS', 'User: ' . $username);
                } else {
                    error_log("Event: REGISTRATION_SUCCESS - User: " . $username);
                }
                $_SESSION['message'] = "Account created successfully";
                $_SESSION['toastClass'] = "#28a745"; // Success color
                header("Location: registration-form.php");
                exit();
            } else {
                $_SESSION['message'] = "Error: " . $stmt->error;
                $_SESSION['toastClass'] = "#dc3545"; // Danger color
                header("Location: registration-form.php");
                exit();
            }

            $stmt->close();
        }

        $checkStmt->close();
        $conn->close();
    } else {
        // If not POST, redirect to form
        header("Location: registration-form.php");
        exit();
    }
    } catch (Exception $e) {
    if (function_exists('log_event')) {
        log_event('REGISTRATION_ERROR', $e->getMessage());
    } else {
        error_log("Registration error: " . $e->getMessage());
    }
    $_SESSION['message'] = "An error occurred during registration. Please try again later.";
    $_SESSION['toastClass'] = "#dc3545"; // Danger color
    header("Location: registration-form.php");
    exit();
}
?>
