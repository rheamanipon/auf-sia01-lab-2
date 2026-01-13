<?php
session_start();
include '../includes/db.php';

if ($conn === null) {
    throw new Exception("Database connection failed.");
}

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Prepare and execute
        $stmt = $conn->prepare("SELECT passwd, fullname, email FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($db_password, $fullname, $email);
            $stmt->fetch();

            if (password_verify($password, $db_password)) {
                if (function_exists('log_event')) {
                    log_event('LOGIN_SUCCESS', 'User: ' . $username);
                } else {
                    error_log("Event: LOGIN_SUCCESS - User: " . $username);
                }
                $_SESSION['message'] = "Login successful";
                $_SESSION['is_logged_in'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['fullname'] = $fullname;
                $_SESSION['email'] = $email;
                $_SESSION['login_time'] = date('Y-m-d H:i:s');
                header("Location: ../pages/welcome.php");
                exit();
            } else {
                if (function_exists('log_event')) {
                    log_event('LOGIN_FAILED', 'Attempted User: ' . $username);
                } else {
                    error_log("Event: LOGIN_FAILED - Attempted User: " . $username);
                }
                $_SESSION['message'] = "Invalid username or password";
                $_SESSION['toastClass'] = "#dc3545"; // Danger color
            }
        } else {
            if (function_exists('log_event')) {
                log_event('LOGIN_FAILED', 'Attempted User: ' . $username);
            } else {
                error_log("Event: LOGIN_FAILED - Attempted User: " . $username);
            }
            $_SESSION['message'] = "Invalid username or password";
            $_SESSION['toastClass'] = "#dc3545"; // Danger color
        }

        $stmt->close();
        $conn->close();

        header("Location: login-form.php");
        exit();
    }
} catch (Exception $e) {
    if (function_exists('log_event')) {
        log_event('LOGIN_ERROR', $e->getMessage());
    } else {
        error_log("Login error: " . $e->getMessage());
    }
    $_SESSION['message'] = "An error occurred during login. Please try again later.";
    $_SESSION['toastClass'] = "#dc3545"; // Danger color
    header("Location: login-form.php");
    exit();
}
?>
