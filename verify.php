<?php
$host = "localhost";
$user = "taskuser";
$pass = "taskpass";
$db   = "task_app_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

$email = $_GET['email'] ?? '';
$token = $_GET['token'] ?? '';

if (!$email || !$token) {
    die("⚠️ Invalid verification link.");
}

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND verification_token = ? LIMIT 1");
$stmt->bind_param("ss", $email, $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Mark as verified
    $update = $conn->prepare("UPDATE users SET verified = 1, verification_token = NULL WHERE email = ?");
    $update->bind_param("s", $email);
    $update->execute();

    echo "<h2>✅ Your email has been successfully verified!</h2>
          <p>You can now log in and use your account.</p>";
} else {
    echo "<h2>❌ Invalid or expired verification link.</h2>";
}
