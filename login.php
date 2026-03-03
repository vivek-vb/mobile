<?php
require_once 'includes/config.php';
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$message = [];

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $select = mysqli_query($conn, "SELECT * FROM user_info WHERE email='$email' LIMIT 1");
    if ($select && mysqli_num_rows($select) > 0) {
        $row = mysqli_fetch_assoc($select);
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            header('Location: index.php');
            exit();
        } else {
            $message[] = 'Incorrect password!';
        }
    } else {
        $message[] = 'Email not found!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Mobile Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0071e3;
            --dark: #1d1d1f;
            --light-bg: #f5f5f7;
            --white: #ffffff;
            --error: #ff3b30;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--light-bg);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* --- Message/Toast --- */
        .message {
            position: fixed;
            top: 20px;
            background: var(--error);
            color: white;
            padding: 15px 25px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            cursor: pointer;
            font-weight: 600;
            z-index: 1000;
            animation: slideDown 0.4s ease;
        }

        @keyframes slideDown {
            from { transform: translateY(-100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* --- Form Container --- */
        .form-container {
            background: var(--white);
            width: 100%;
            max-width: 400px;
            padding: 40px;
            border-radius: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
            text-align: center;
        }

        .form-container h3 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .form-container p.subtitle {
            color: #86868b;
            margin-bottom: 30px;
            font-size: 0.9rem;
        }

        .form-container input[type="email"],
        .form-container input[type="password"] {
            width: 100%;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 12px;
            border: 1px solid #d2d2d7;
            background: #fbfbfd;
            font-size: 1rem;
            transition: 0.3s;
        }

        .form-container input:focus {
            outline: none;
            border-color: var(--primary);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(0,113,227,0.1);
        }

        .form-container input[type="submit"] {
            width: 100%;
            padding: 15px;
            border-radius: 12px;
            border: none;
            background: var(--primary);
            color: white;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        .form-container input[type="submit"]:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .form-container .footer-text {
            margin-top: 25px;
            font-size: 0.9rem;
            color: #86868b;
        }

        .form-container a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .form-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php
if (!empty($message)) {
    foreach ($message as $msg) {
        echo '<div class="message" onclick="this.remove();">'.$msg.'</div>';
    }
}
?>

<div class="form-container">
    <form method="post">
        <h3>Welcome Back</h3>
        <p class="subtitle">Enter your credentials to access your account</p>
        
        <input type="email" name="email" required placeholder="Email Address">
        <input type="password" name="password" required placeholder="Password">
        
        <input type="submit" name="submit" value="Sign In">
        
        <p class="footer-text">
            Don’t have an account? <a href="register.php">Create one now</a>
        </p>
    </form>
</div>

</body>
</html>