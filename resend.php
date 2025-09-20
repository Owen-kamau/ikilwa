<?php

// DB connection
$host = "localhost";
$user = "taskuser";
$pass = "taskpass";
$db   = "task_app_db";
$conn = new mysqli($host, $user, $pass, $db);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$name      = $_POST['name'] ?? "User";
$email     = $_POST['email'] ?? "unknown@example.com";
$car_color = $_POST['car_color'] ?? "Unknown";

$mail = new PHPMailer(true);

try {
    // ... PHPMailer SMTP setup ...

    $mail->send();

    // ✅ Log success
    $log = $conn->prepare("INSERT INTO email_logs (user_email, user_name, car_color, status) VALUES (?, ?, ?, 'success')");
    $log->bind_param("sss", $email, $name, $car_color);
    $log->execute();

    header("Location: success.php?name=" . urlencode($name) .
           "&email=" . urlencode($email) .
           "&car_color=" . urlencode($car_color) .
           "&status=success");
    exit;

} catch (Exception $e) {
    // ❌ Log failure
    $log = $conn->prepare("INSERT INTO email_logs (user_email, user_name, car_color, status, error_message) VALUES (?, ?, ?, 'failed', ?)");
    $errMsg = $e->getMessage();
    $log->bind_param("ssss", $email, $name, $car_color, $errMsg);
    $log->execute();
        $errorMsg = $e->getMessage();
    header("Location: success.php?name=" . urlencode($name) 
        . "&email=" . urlencode($email) 
        . "&car_color=" . urlencode($car_color) 
        . "&status=failed&error=" . urlencode($errorMsg));

    header("Location: success.php?name=" . urlencode($name) .
           "&email=" . urlencode($email) .
           "&car_color=" . urlencode($car_color) .
           "&status=failed");
    exit;
}