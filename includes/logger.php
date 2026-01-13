<?php
function log_event(string $eventType, string $description): void {
    $ts = date('Y-m-d H:i:s');
    $message = "[$ts] $eventType - $description";
    error_log($message);
}

?>
