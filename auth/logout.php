<?php
session_start();
session_unset();
session_destroy();
header("Location: login-form.php?logout=1");
exit();
?>
