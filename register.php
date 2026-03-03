<?php
require_once 'includes/config.php';

// FIX: Prevents "Session already active" notice
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$message = [];

if (isset($_POST['submit'])) {
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname  = mysqli_real_escape_string($conn, $_POST['lastname']);
    $email     = mysqli_real_escape_string($conn, $_POST['email']);
    $password  = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    if ($password !== $cpassword) {
        $message[] = 'Passwords do not match!';
    } else {
        $check = mysqli_query($conn, "SELECT * FROM user_info WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $message[] = 'User already exists!';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $insert = mysqli_query($conn, "INSERT INTO user_info (firstname, lastname, email, password) VALUES('$firstname','$lastname','$email','$hashedPassword')");
            
            if($insert) {
                header('Location: login.php');
                exit();
            } else {
                $message[] = 'Registration failed. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Mobile Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0071e3;
            --dark: #1d1d1f;
            --light-grey: #f5f5f7;
            --border: #d2d2d7;
            --error: #ff3b30;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--light-grey);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .register-card {
            background: #ffffff;
            width: 100%;
            max-width: 450px;
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        h2 {
            font-size: 26px;
            color: var(--dark);
            margin-bottom: 8px;
            font-weight: 700;
        }

        .subtitle {
            color: #6e6e73;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .error-box {
            background: #fff0f0;
            color: var(--error);
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .input-row {
            display: flex;
            gap: 15px;
        }

        input {
            width: 100%;
            padding: 14px;
            margin-bottom: 15px;
            border: 1px solid var(--border);
            border-radius: 12px;
            font-size: 16px;
            outline: none;
            background: #fbfbfd;
            transition: 0.2s;
        }

        input:focus {
            border-color: var(--primary);
            background: #fff;
        }

        .btn-register {
            width: 100%;
            padding: 15px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-register:hover {
            background-color: #0077ed;
        }

        .footer {
            margin-top: 25px;
            font-size: 14px;
            color: #6e6e73;
        }

        .footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="register-card">
    <h2>Create Account</h2>
    <p class="subtitle">Join our mobile store community today.</p>

    <?php if (!empty($message)): ?>
        <div class="error-box">
            <?php foreach ($message as $msg) echo $msg; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <div class="input-row">
            <input type="text" name="firstname" required placeholder="First Name">
            <input type="text" name="lastname" required placeholder="Last Name">
        </div>
        <input type="email" name="email" required placeholder="Email Address">
        <input type="password" name="password" required placeholder="Create Password">
        <input type="password" name="cpassword" required placeholder="Confirm Password">
        
        <button type="submit" name="submit" class="btn-register">Create Account</button>
    </form>

    <div class="footer">
        Already have an account? <a href="login.php">Log in</a>
    </div>
</div>

</body>
</html>