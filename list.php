<?php
$host = "localhost";
$user = "taskuser";   // DB user
$pass = "taskpass";   // DB password
$db   = "task_app_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

// Handle "Clear All Users" action
if (isset($_POST['clear_users'])) {
    $conn->query("TRUNCATE TABLE users");
    header("Location: list.php?cleared=1");
    exit;
}

// Handle "Export Users" action
if (isset($_POST['export_users'])) {
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=users_export.csv");

    $output = fopen("php://output", "w");
    // CSV header
    fputcsv($output, ["ID", "Name", "Email", "Favorite Car Color"]);

    $res = $conn->query("SELECT id, name, email, car_color FROM users ORDER BY id ASC");
    while ($row = $res->fetch_assoc()) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

// Sorting logic
$allowedSort = ["id", "name", "email", "car_color"];
$sort = isset($_GET['sort']) && in_array($_GET['sort'], $allowedSort) ? $_GET['sort'] : "id";

// Allow ASC or DSC
$order = isset($_GET['order']) && $_GET['order'] === "DESC" ? "DESC" : "ASC";

$result = $conn->query("SELECT * FROM users ORDER BY $sort $order");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registered Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background: #f9f9f9;
        }
        h2 {
            color: #333;
        }
        table {
            width: 90%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background: #0078D7;
            color: #fff;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
        .color-box {
            padding: 6px 10px;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
        }
        a, button {
            display: inline-block;
            margin: 10px 5px;
            text-decoration: none;
            padding: 8px 14px;
            background: #0078D7;
            color: #fff;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
        a:hover, button:hover {
            background: #005a9e;
        }
        .success-msg {
            color: green;
            margin-bottom: 15px;
            font-weight: bold;
        }
    </style>

    <script>
        function confirmClear() {
            return confirm("⚠️ Are you sure you want to clear ALL users? This cannot be undone.");
        }
        function changeSort(select) {
           const urlParams = new URLSearchParams(window.location.search);
           urlParams.set("sort", select.value);
           urlParams.set("order", "ASC"); // reset to ASC when changing column
           window.location = "list.php?" + urlParams.toString();
        }
        function toggleOrder() {
           const urlParams = new URLSearchParams(window.location.search);
           let currentOrder = urlParams.get("order") || "ASC";
           urlParams.set("order", currentOrder === "ASC" ? "DESC" : "ASC");
           window.location = "list.php?" + urlParams.toString();
        }
        </script>

</head>
<body>
    <h2>Registered Users 🚀</h2>

    <?php if (isset($_GET['cleared'])): ?>
        <div class="success-msg">✅ All users have been cleared successfully!</div>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
        <!-- Action buttons -->
        <form method="POST" style="display:inline;" onsubmit="return confirmClear();">
            <button type="submit" name="clear_users">🗑️ Clear All Users</button>
        </form>
        <form method="POST" style="display:inline;">
            <button type="submit" name="export_users">📥 Export Users (CSV)</button>
        </form>

        <!-- Sorting dropdown -->
<label for="sort">🔽 Sort By:</label>
<select id="sort" onchange="changeSort(this)">
    <option value="id" <?= $sort=="id" ? "selected" : "" ?>>ID</option>
    <option value="name" <?= $sort=="name" ? "selected" : "" ?>>Name</option>
    <option value="email" <?= $sort=="email" ? "selected" : "" ?>>Email</option>
    <option value="car_color" <?= $sort=="car_color" ? "selected" : "" ?>>Favorite Car Color</option>
</select>

         <!-- Sorting controls -->
<label for="sort">🔽 Sort By:</label>
<select id="sort" onchange="changeSort(this)">
    <option value="id" <?= $sort=="id" ? "selected" : "" ?>>ID</option>
    <option value="name" <?= $sort=="name" ? "selected" : "" ?>>Name</option>
    <option value="email" <?= $sort=="email" ? "selected" : "" ?>>Email</option>
    <option value="car_color" <?= $sort=="car_color" ? "selected" : "" ?>>Favorite Car Color</option>
</select>

<!-- Toggle ASC/DESC -->
<button type="button" onclick="toggleOrder()">
    <?= $order === "ASC" ? "⬇ Descending" : "⬆ Ascending" ?>
</button>



        <table>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Favorite Car Color</th>
            </tr>
            <?php 
            $counter = 1;
            while ($row = $result->fetch_assoc()): 
                $carColor = $row['car_color'] ?? "Unknown";

                // map colors to backgrounds
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
                
                $bgColor = $colorMap[$carColor] ?? "#555";
                $textColor = in_array($carColor, ["White", "Silver", "Yellow"]) ? "#000" : "#fff";
            ?>
                <tr>
                    <td><?= $counter++; ?></td>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td>
                        <span class="color-box" style="background: <?= $bgColor ?>; color: <?= $textColor ?>">
                            <?= htmlspecialchars($carColor) ?> 🚗
                        </span>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>

    <a href="index.php">⬅ Back to Signup</a>
</body>
</html>
