<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task App - Signup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f8fb;
            margin: 40px;
        }
        .container {
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h2 {
            color: #0078D7;
            text-align: center;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 15px;
        }
        button {
            background: #0078D7;
            color: white;
            border: none;
            margin-top: 20px;
            cursor: pointer;
        }
        button:hover {
            background: #005a9e;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Signup Form 🚀</h2>
        <form action="mail.php" method="POST">
            <label for="name">Full Name:</label>
            <input type="text" name="name" id="name" placeholder="Enter your name" required>

            <label for="email">Email Address:</label>
            <input type="email" name="email" id="email" placeholder="Enter your email" required>

            <label for="car_color">Favorite Car Color:</label>
            <select name="car_color" id="car_color" required>
                <option value="">-- Select --</option>
                <option value="Red">Red ❤️</option>
                <option value="Blue">Blue 💙</option>
                <option value="Black">Black 🖤</option>
                <option value="Grey">Grey 🩶</option>
                <option value="White">White 🤍</option>
                <option value="Silver">Silver ✨</option>
                <option value="Green">Green 💚</option>
                <option value="Yellow">Yellow 💛</option>
                <option value="Orange">Orange 🧡</option>
                <option value="Purple">Purple 💜</option>
                <option value="Custom Wrap">Custom Wrap 🎨</option>
            </select>

            <button type="submit">Signup</button>
        </form>
    </div>
</body>
</html>
