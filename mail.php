<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Database connection
$host = "localhost";
$user = "taskuser";
$pass = "taskpass";
$db   = "task_app_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] !== "POST" || 
    !isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['car_color'])) {
    die("⚠️ Please submit the signup form from <a href='index.php'>index.php</a>");
}

$name      = trim($_POST['name']);
$email     = trim($_POST['email']);
$car_color = trim($_POST['car_color']);

// ✅ Insert user into database
$stmt = $conn->prepare("INSERT INTO users (name, email, car_color) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $car_color);
$stmt->execute();

$token = bin2hex(random_bytes(16)); // secure random token

// Insert into database with token
$stmt = $conn->prepare("INSERT INTO users (name, email, car_color, verification_token) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $car_color, $token);
$stmt->execute();

// Setup PHPMailer
$mail = new PHPMailer(true);

try {
    // Gmail SMTP settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'owen.kamau@strathmore.edu';   // 🔑 your Gmail
    $mail->Password   = 'wrjt oiof uvfs utsv';     // 🔑 16-char App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Sender & Recipient
    $mail->setFrom('owen.kamau@strathmore.edu', 'Task App');
    $mail->addAddress($email, $name);

    
// Verification link
$verifyLink = "http://localhost/task_app/verify.php?email=" . urlencode($email) . "&token=$token";


    // Email Content
    $mail->isHTML(true);
    $mail->Subject = 'Welcome to Task App 🎉';
    $mail->Body    = "<h3>Hello <b>$name</b>,</h3>
                      <p>Welcome to our application! 🚀</p>
                      <p>Your requested an account on BBIT 2.2</p>
                       <p>In order to use this account you need to <a href='{$verifyLink}' style='color:#1a73e8; text-decoration:none;'>Click Here</a> to complete the registration process.</p>
                       <br><p>Best Regards,<br><b>Systems Admin</b><br>BBT2.2<b><br>Task App</p>
                      <p>We also noted that your favorite car color is 
                      <b style='color:$car_color;'>$car_color</b> 🚗</p>";
                     
    $mail->send();

    // ✅ Log success
    $log = $conn->prepare("INSERT INTO email_logs (user_email, user_name, car_color, status) VALUES (?, ?, ?, 'success')");
    $log->bind_param("sss", $email, $name, $car_color);
    $log->execute();

    // Redirect to success page with success flag
    header("Location: success.php?name=" . urlencode($name) 
        . "&email=" . urlencode($email) 
        . "&car_color=" . urlencode($car_color) 
        . "&status=success");
    exit;

} catch (Exception $e) {
    // ⚠️ Log simulated failure with error message
    $errMsg = $e->getMessage();
    $log = $conn->prepare("INSERT INTO email_logs (user_email, user_name, car_color, status, error_message) VALUES (?, ?, ?, 'simulated', ?)");
    $log->bind_param("ssss", $email, $name, $car_color, $errMsg);
    $log->execute();

        $errorMsg = $e->getMessage();
    header("Location: success.php?name=" . urlencode($name) 
        . "&email=" . urlencode($email) 
        . "&car_color=" . urlencode($car_color) 
        . "&status=failed&error=" . urlencode($errorMsg));

    // Redirect to success page with simulated flag
    header("Location: success.php?name=" . urlencode($name) 
        . "&email=" . urlencode($email) 
        . "&car_color=" . urlencode($car_color) 
        . "&status=simulated");
    exit;
}
