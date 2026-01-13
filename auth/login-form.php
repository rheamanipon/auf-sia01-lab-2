<?php
session_start();
if (isset($_GET['logout'])) {
    $_SESSION['message'] = "You have been logged out successfully.";
    $_SESSION['toastClass'] = "#28a745";
}
$message = "";
$toastClass = "";
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $toastClass = isset($_SESSION['toastClass']) ? $_SESSION['toastClass'] : "#17a2b8"; // Default to info if not set
    unset($_SESSION['message']);
    unset($_SESSION['toastClass']);
}
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
    <title>Login Form</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="bg-teal-light">
    <div class="container p-4 d-flex flex-column align-items-center">
        <?php if ($message): ?>
            <div class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true" style="background-color: <?php echo $toastClass; ?>;">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>
        <form action="login.php" method="post" class="form-control form-teal mt-5 p-4" style="height:auto; width:380px;">
            <div class="row">
                <i class="fa fa-user-circle-o fa-3x mt-1 mb-2 text-center text-teal"></i>
                <h5 class="text-center p-4" style="font-weight: 700;">Login Into Your Account</h5>
            </div>
            <div class="col-mb-3">
                <label for="username"><i class="fa fa-user"></i> Username</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="col mb-3 mt-3">
                <label for="password"><i class="fa fa-lock"></i> Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="col mb-3 mt-3">
                <button type="submit" class="btn btn-teal text-white w-100" style="font-weight: 600;">Login</button>
            </div>
            <div class="col mb-2 mt-4">
                <p class="text-center" style="font-weight: 600; color: navy;">
                    <a href="register.php" style="text-decoration: none;">Create Account</a>
                </p>
            </div>
        </form>
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
