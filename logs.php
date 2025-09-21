<?php
session_start();
$host = "localhost";
$user = "taskuser";
$pass = "taskpass";
$db   = "task_app_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}// ✅ Handle login form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['email'], $_POST['password'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $query = $conn->prepare("SELECT id, password_hash FROM users WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 1) {
        $userRow = $result->fetch_assoc();
        if (password_verify($password, $userRow['password_hash'])) {
            $_SESSION['user_id'] = $userRow['id'];
            $_SESSION['user_email'] = $email;
        } else {
            $login_error = "❌ Invalid email or password.";
        }
    } else {
        $login_error = "❌ User not found.";
    }
}

// ✅ If not logged in, show login form only
if (!isset($_SESSION['user_id'])): ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Login - Task App</title>
        <style>
            body { font-family: Arial, sans-serif; background: #f4f8fb; margin: 40px; }
            .container { max-width: 400px; margin: auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
            h2 { color: #0078D7; text-align: center; }
            label { font-weight: bold; display: block; margin-top: 15px; }
            input, button { width: 100%; padding: 10px; margin-top: 8px; border-radius: 5px; border: 1px solid #ccc; font-size: 15px; }
            button { background: #0078D7; color: white; border: none; margin-top: 20px; cursor: pointer; }
            button:hover { background: #005a9e; }
            .error { color: red; text-align: center; }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>🔑 Sign In</h2>
            <?php if (!empty($login_error)): ?>
                <p class="error"><?= htmlspecialchars($login_error) ?></p>
            <?php endif; ?>
            <form action="logs.php" method="POST">
                <label for="email">Email Address:</label>
                <input type="email" name="email" id="email" required>

                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>

                <button type="submit">Sign In</button>
            </form>
        </div>
    </body>
    </html>
<?php
exit;
endif;

// ✅ If logged in, fetch logs

$result = $conn->query("SELECT * FROM email_logs ORDER BY created_at DESC LIMIT 50");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Email Logs</title>
    <meta http-equiv="refresh" content="10"> <!-- refresh every 10 seconds -->
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f9f9f9; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background: #0078D7; color: #fff; }
        tr:nth-child(even) { background: #f2f2f2; }
        .success { color: green; font-weight: bold; }
        .failed { color: red; font-weight: bold; }
        .highlight {background: #fff8c4 !important; /* soft yellow */font-weight: bold;}
        .highlight {
            background: #fff8c4 !important; /* soft yellow */
            font-weight: bold;
            animation: flash 2s ease-in-out;
        }
        @keyframes flash {
            0%   { background-color: #fff8c4; }
            50%  { background-color: #ffe066; }
            100% { background-color: #fff8c4; }}

    </style>
</head>
<body>
    <h2>📧 Email Logs (latest 50) – Auto-refresh every 12s</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>User Email</th>
            <th>Name</th>
            <th>Car Color</th>
            <th>Status</th>
            <th>Error</th>
            <th>Time</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['user_email']) ?></td>
                <td><?= htmlspecialchars($row['user_name']) ?></td>
                <td><?= htmlspecialchars($row['car_color']) ?></td>
                <td class="<?= $row['status'] === 'success' ? 'success' : 'failed' ?>">
                    <?= htmlspecialchars($row['status']) ?>
                </td>
                <td><?= htmlspecialchars($row['error_message']) ?></td>
                <td><?= $row['created_at'] ?></td>
            </tr>
            <?php $first = false; ?>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7">No email logs found</td></tr>
        <?php endif; ?>


        <?php 
         $first = true; 
         while ($row = $result->fetch_assoc()):?>
               
         <tr class="<?= $first ? 'highlight' : '' ?>">
         <td><?= $row['id'] ?></td>
         <td><?= htmlspecialchars($row['user_email']) ?></td>
         <td><?= htmlspecialchars($row['user_name']) ?></td>
         <td><?= htmlspecialchars($row['car_color']) ?></td>
         <td class="<?= $row['status'] === 'success' ? 'success' : 'failed' ?>">
         <?= htmlspecialchars($row['status']) ?>
         </td>
         <td><?= htmlspecialchars($row['error_message']) ?></td>
         <td><?= $row['created_at'] ?></td>
         </tr>
         <?php 
         $first = false; 
         endwhile; ?>

         <?php 
         $first = true; 
         while ($row = $result->fetch_assoc()): ?>
         <tr class="<?= $first ? 'highlight' : '' ?>">
         <td><?= $row['id'] ?></td>
         <td><?= htmlspecialchars($row['user_email']) ?></td>
         <td><?= htmlspecialchars($row['user_name']) ?></td>
         <td><?= htmlspecialchars($row['car_color']) ?></td>
         <td class="<?= $row['status'] === 'success' ? 'success' : 'failed' ?>">
         <?= htmlspecialchars($row['status']) ?> </td>
         <td><?= htmlspecialchars($row['error_message']) ?></td>
         <td><?= $row['created_at'] ?></td>
        </tr>
         <?php 
         $first = false; 
         endwhile; ?>


    </table>

    <form action="logs.php" method="POST">
      <label for="email">Email Address:</label>
      <input type="email" name="email" id="email" placeholder="Enter your email" required>

      <label for="password">Password:</label>
      <input type="password" name="password" id="password" placeholder="Enter your password" required>

      <button type="submit">Sign In</button>
    </form>


 <script>
        let autoRefresh = setInterval(() => {
            location.reload();
        }, 10000); // refresh every 10s

        const btn = document.getElementById("toggleRefresh");
        let isPaused = false;

        btn.addEventListener("click", () => {
            if (isPaused) {
                // resume
                autoRefresh = setInterval(() => location.reload(), 10000);
                btn.textContent = "⏸ Pause Auto-Refresh";
                isPaused = false;
            } else {
                // pause
                clearInterval(autoRefresh);
                btn.textContent = "▶ Resume Auto-Refresh";
                isPaused = true;
            }
        });
    </script>


</body>
</html>
