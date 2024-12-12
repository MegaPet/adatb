<?php
session_start()
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register</title>
    <style>
        /* Basic CSS for layout and design */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .switch-form {
            text-align: center;
            margin-top: 10px;
        }

        .switch-form a {
            color: #4CAF50;
            text-decoration: none;
        }

        .switch-form a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            font-size: 12px;
            text-align: center;
        }

        .role-group {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .role-group label {
            font-weight: normal;
        }

        .error {
            display: block;
            padding: 0.5rem;
            color: red;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Login Form -->
        <div id="login-form">
            <h2>Login</h2>
            <form action="loginAuth.php" method="post">
                <div class="form-group">
                    <label for="login-email">Email</label>
                    <input type="email" id="login-email" name="login-email" required>
                </div>
                <div class="form-group">
                    <label for="login-password">Password</label>
                    <input type="password" id="login-password" name="login-password" required>
                </div>
                <input type="submit" value="Login">
                <div class="switch-form">
                    Don't have an account? <a href="javascript:void(0);" onclick="showRegisterForm()">Register here</a>
                </div>
            </form>
        </div>

        <!-- Registration Form -->
        <div id="register-form" style="display: none;">
            <h2>Register</h2>
            <form action="registerAuth.php" method="post">
                <div class="form-group">
                    <label for="register-name">Full Name</label>
                    <input type="text" id="register-name" name="register-name" required>
                </div>
                <div class="form-group">
                    <label for="register-email">Email</label>
                    <input type="email" id="register-email" name="register-email" required>
                </div>





                <div class="form-group">
                    <label for="register-password">Password</label>
                    <input type="password" id="register-password" name="register-password" required>
                </div>
                <div class="form-group">
                    <label for="register-confirm-password">Confirm Password</label>
                    <input type="password" id="register-confirm-password" name="register-confirm-password" required>
                </div>
                <!-- Role selection (radio buttons) -->
                <div class="form-group role-group">
                    <label>Role</label>
                    <label>
                        <input type="radio" name="role" value="student" required> Student
                    </label>
                    <label>
                        <input type="radio" name="role" value="teacher"> Teacher
                    </label>
                </div>
                <input type="submit" value="Register">
                <div class="switch-form">
                    Already have an account? <a href="javascript:void(0);" onclick="showLoginForm()">Login here</a>
                </div>
            </form>
        </div>
    </div>
    <?php
    if (isset($_SESSION["passError"])) {
        echo '<div class = "error">';
        echo "<p>"  . $_SESSION["passError"] . "</p>";
        echo "</div>";
    };
    ?>


    <script>
        // JavaScript to switch between login and register forms
        function showRegisterForm() {
            document.getElementById('login-form').style.display = 'none';
            document.getElementById('register-form').style.display = 'block';
        }

        function showLoginForm() {
            document.getElementById('register-form').style.display = 'none';
            document.getElementById('login-form').style.display = 'block';
        }
    </script>
    <?php
    ?>
</body>

</html>