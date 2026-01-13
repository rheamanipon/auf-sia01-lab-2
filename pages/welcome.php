<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: ../auth/login-form.php");
    exit();
}

$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="shortcut icon" href="https://cdn-icons-png.flaticon.com/512/295/295128.png">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Welcome</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="bg-teal-light">
    <div class="container p-4">
        <?php if ($message): ?>
            <div class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true" style="background-color: #28a745;">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>

        <div class="card mt-4 border-teal">
            <div class="card-header bg-teal text-white">
                <h5>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h5>
            </div>
            <div class="card-body">
                <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
                <p><strong>Full Name:</strong> <?php echo htmlspecialchars($_SESSION['fullname']); ?></p>
                <p><strong>Login Time:</strong> <?php echo htmlspecialchars($_SESSION['login_time']); ?></p>
            </div>
        </div>

        <a href="../auth/logout.php" class="btn btn-danger logout-btn">Logout</a>

        <div class="card mt-4 border-teal">
            <div class="card-header bg-teal text-white">
                <h5>All Registered Users</h5>
            </div>
            <div class="card-body">
                <table class="table table-teal">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            if ($conn === null) {
                                throw new Exception("Database connection failed.");
                            }
                            $stmt = $conn->prepare("SELECT id, fullname, username, email FROM users");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['fullname']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "</tr>";
                            }
                            $stmt->close();
                        } catch (Exception $e) {
                            if (function_exists('log_event')) {
                                log_event('FETCH_USERS_ERROR', $e->getMessage());
                            } else {
                                error_log("Error fetching users: " . $e->getMessage());
                            }
                            echo "<div class='alert alert-danger text-center'>Unable to load user data at this time. Please try again later.</div>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        let toastElList = [].slice.call(document.querySelectorAll('.toast'))
        let toastList = toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl, { delay: 3000 });
        });
        toastList.forEach(toast => toast.show());
    </script>
</body>
</html>
<?php
if ($conn) $conn->close();
?>
