<?php
session_start();

/* ============================
    Database Class
============================ */
class Database_demo {
    private $host = "localhost";
    private $db = "mobile-store"; 
    private $user = "root";
    private $pass = "";
    public $conn;

    public function connect() {
        try {
            $this->conn = new PDO(
                "mysql:host=".$this->host.";dbname=".$this->db,
                $this->user,
                $this->pass
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch(PDOException $e) {
            die("Connection Error: ".$e->getMessage());
        }
    }
}

/* ============================
    Admin Class
============================ */
class Admin {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($email, $password) {
        $query = "SELECT * FROM user_info WHERE email=? AND password=?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$email, $password]);
        return $stmt->rowCount() > 0;
    }
}

/* ============================
    Logic Execution
============================ */
$db = (new Database_demo())->connect();
$admin = new Admin($db);
$error = "";

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if($admin->login($email, $password)) {
        $_SESSION['admin'] = $email;
        header("Location: admin_panel.php");
        exit;
    } else {
        $error = "Invalid Email or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3D Admin Login | Mobile Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            /* 3D Background Gradient */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 20px;
            /* Multi-layered shadow for 3D depth */
            box-shadow: 
                0 10px 30px rgba(0, 0, 0, 0.2),
                0 20px 60px rgba(0, 0, 0, 0.1),
                inset 0 0 0 1px rgba(255, 255, 255, 0.5);
            transform: perspective(1000px) rotateX(2deg);
            transition: all 0.4s ease;
            max-width: 400px;
            width: 100%;
        }

        .login-card:hover {
            transform: perspective(1000px) rotateX(0deg) translateY(-5px);
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.3);
        }

        .card-header-3d {
            background: #4e54c8;
            background: -webkit-linear-gradient(to right, #8f94fb, #4e54c8);
            background: linear-gradient(to right, #8f94fb, #4e54c8);
            color: white;
            border-radius: 20px 20px 0 0 !important;
            padding: 25px;
            text-align: center;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .form-control {
            border-radius: 10px;
            padding: 12px;
            border: 1px solid #ddd;
            transition: 0.3s;
        }

        .form-control:focus {
            box-shadow: 0 0 15px rgba(118, 75, 162, 0.3);
            border-color: #764ba2;
        }

        .btn-3d {
            background: linear-gradient(to right, #667eea, #764ba2);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            color: white;
            box-shadow: 0 4px 15px rgba(118, 75, 162, 0.4);
            transition: 0.3s;
        }

        .btn-3d:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(118, 75, 162, 0.6);
            color: #fff;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center">
    <div class="card login-card">
        <div class="card-header-3d">
            <h3 class="m-0">ADMIN PORTAL</h3>
        </div>
        <div class="card-body p-4">
            
            <?php if($error): ?>
                <div class="alert alert-danger text-center small py-2" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold">EMAIL ADDRESS</label>
                    <input type="email" name="email" class="form-control" placeholder="admin@store.com" required>
                </div>
                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold">PASSWORD</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="d-grid">
                    <button type="submit" name="login" class="btn btn-3d">Authorize Login</button>
                </div>
            </form>
        </div>
        <div class="card-footer bg-transparent border-0 text-center pb-4">
            <small class="text-muted">Secure Access Only</small>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>