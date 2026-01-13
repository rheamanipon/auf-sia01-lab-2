<?php
session_start();
include 'db.php';

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
                error_log("Event: LOGIN_SUCCESS - User: " . $username);
                $_SESSION['message'] = "Login successful";
                $_SESSION['is_logged_in'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['fullname'] = $fullname;
                $_SESSION['email'] = $email;
                $_SESSION['login_time'] = date('Y-m-d H:i:s');
                header("Location: welcome.php");
                exit();
            } else {
                error_log("Event: LOGIN_FAILED - Attempted User: " . $username);
                $_SESSION['message'] = "Invalid username or password";
            }
        } else {
            error_log("Event: LOGIN_FAILED - Attempted User: " . $username);
            $_SESSION['message'] = "Invalid username or password";
        }

        $stmt->close();
        $conn->close();

        header("Location: login-form.php");
        exit();
    }
} catch (Exception $e) {
    error_log("Login error: " . $e->getMessage());
    $_SESSION['message'] = "An error occurred during login. Please try again later.";
    header("Location: login-form.php");
    exit();
}
?>
