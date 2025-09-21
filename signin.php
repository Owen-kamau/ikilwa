<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task App - Sign In</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f8fb;
            margin: 40px;
        }
        .container {
            max-width: 400px;
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
        input, button {
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
        .signup-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }
        .signup-link a {
            color: #0078D7;
            text-decoration: none;
            font-weight: bold;
        }
        .signup-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sign In 🔑</h2>
        <form action="login.php" method="POST">
            <label for="email">Email Address:</label>
            <input type="email" name="email" id="email" placeholder="Enter your email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" placeholder="Enter your password" required>

            <button type="submit">Sign In</button>
        </form>

        <!-- Signup Option -->
        <div class="signup-link">
            Don’t have an account? <a href="index.html">Sign up here</a>
        </div>
    </div>
</body>
</html>
