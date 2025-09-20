<?php
// Capture data from redirect
$name      = $_GET['name'] ?? "User";
$email     = $_GET['email'] ?? "unknown@example.com";
$car_color = $_GET['car_color'] ?? "Unknown";
$status    = $_GET['status'] ?? "simulated"; // success | simulated | failed
$errorMsg  = $_GET['error'] ?? null;

// color mapping for badge
$colorMap = [
    "Red" => "red",
    "Blue" => "blue",
    "Black" => "black",
    "White" => "lightgray",
    "Silver" => "silver",
    "Green" => "green",
    "Yellow" => "gold",
    "Orange" => "orange",
    "Purple" => "purple",
    "Custom Wrap" => "teal"
];
$bgColor = $colorMap[$car_color] ?? "#555";
$textColor = in_array($car_color, ["White", "Silver", "Yellow"]) ? "#000" : "#fff";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f8fb;
            margin: 40px;
            color: #333;
        }
        .container {
            max-width: 650px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        h2 {
            color: #0078D7;
        }
        .success { color: green; }
        .warning { color: orange; }
        .failed { color: red; }
        .email-box {
            margin-top: 15px;
            padding: 12px;
            border: 1px solid #ddd;
            background: #f9f9f9;
            text-align: left;
            font-family: monospace;
        }
        .color-box {
            padding: 6px 10px;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            margin-top: 8px;
        }
        a.button, button {
            display: inline-block;
            margin: 10px;
            text-decoration: none;
            padding: 10px 18px;
            background: #0078D7;
            color: #fff;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        a.button:hover, button:hover {
            background: #005a9e;
        }
        .error-msg {
            margin-top: 15px;
            padding: 10px;
            border: 1px solid #e74c3c;
            background: #fdecea;
            color: #c0392b;
            border-radius: 5px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">

        <?php if ($status === "success"): ?>
            <h2 class="success">🎉 Signup Successful!</h2>
            <p>✅ A welcome email has been sent to your inbox.</p>

        <?php elseif ($status === "simulated"): ?>
            <h2 class="warning">⚠️ Signup Recorded (Email Simulated)</h2>
            <p>We saved your signup but could not send the email (SMTP issue).</p>
            <p>Here’s what the email would have looked like:</p>
            <div class="email-box">
                To: <?= htmlspecialchars($email) ?><br>
                Subject: Welcome to Task App 🎉<br><br>
                Hello <?= htmlspecialchars($name) ?>, welcome to our application!<br>
                Your favorite car color is <b><?= htmlspecialchars($car_color) ?></b> 🚗
            </div>

        <?php elseif ($status === "failed"): ?>
            <h2 class="failed">❌ Signup Completed but Email Failed</h2>
            <p>We saved your signup, but sending the welcome email failed.</p>
            <?php if ($errorMsg): ?>
                <div class="error-msg">
                    <b>Error Details:</b><br>
                    <?= nl2br(htmlspecialchars($errorMsg)) ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <h2>ℹ️ Unknown Status</h2>
        <?php endif; ?>

        <p>Welcome, <b><?= htmlspecialchars($name) ?></b>!<br>
           We’ve recorded your email: <b><?= htmlspecialchars($email) ?></b></p>

        <p>Your favorite car color is:</p>
        <span class="color-box" style="background: <?= $bgColor ?>; color: <?= $textColor ?>">
            <?= htmlspecialchars($car_color) ?> 🚗
        </span>

        <br><br>
        <a class="button" href="list.php">📋 View User List</a>
        <a class="button" href="index.php">⬅ Back to Signup</a>
    </div>
</body>
</html>
