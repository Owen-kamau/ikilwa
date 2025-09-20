<?php
$host = "localhost";
$user = "taskuser";
$pass = "taskpass";
$db   = "task_app_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

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
        .highlight {background: #fff8c4 !important; /* soft yellow */font-weight: bold;animation: flash 2s ease-in-out;}
        @keyframes flash {0%   { background-color: #fff8c4; }50%  { background-color: #ffe066; }100% { background-color: #fff8c4; }}

    </style>
</head>
<body>
    <h2>📧 Email Logs (latest 50) – Auto-refresh every 10s</h2>
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
