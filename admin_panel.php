<?php
session_start();

/* ===============================
   DATABASE CONNECTION
=================================*/
class DB {
    private $host = "localhost";
    private $db   = "mobile-store";
    private $user = "root";
    private $pass = "";
    public $conn;

    public function connect() {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db};charset=utf8",
                $this->user,
                $this->pass,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            return $this->conn;
        } catch(PDOException $e) {
            die("Database Error: " . $e->getMessage());
        }
    }
}

/* ===============================
   ADMIN CLASS
=================================*/
class Admin {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    /* ---------- LOGIN ---------- */
    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM user_info WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    /* ---------- USERS ---------- */
    public function getUsers() {
        return $this->conn->query("SELECT * FROM user_info ORDER BY id DESC")
                          ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addUser($f,$l,$e,$p) {
        $hash = password_hash($p, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare(
            "INSERT INTO user_info(firstname,lastname,email,password) VALUES(?,?,?,?)"
        );
        return $stmt->execute([$f,$l,$e,$hash]);
    }

    public function deleteUser($id) {
        $stmt = $this->conn->prepare("DELETE FROM user_info WHERE id = ?");
        return $stmt->execute([(int)$id]);
    }

    /* ---------- PRODUCTS ---------- */
    public function getProducts($limit,$offset) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM products ORDER BY id DESC LIMIT ? OFFSET ?"
        );
        $stmt->bindValue(1,$limit,PDO::PARAM_INT);
        $stmt->bindValue(2,$offset,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countProducts() {
        return $this->conn->query("SELECT COUNT(*) FROM products")->fetchColumn();
    }

    public function getProduct($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id=?");
        $stmt->execute([(int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addProduct($n,$p,$i,$c) {
        $stmt = $this->conn->prepare(
            "INSERT INTO products(name,price,image,category) VALUES(?,?,?,?)"
        );
        return $stmt->execute([$n,$p,$i,$c]);
    }

    public function updateProduct($id,$n,$p,$i,$c) {
        $stmt = $this->conn->prepare(
            "UPDATE products SET name=?,price=?,image=?,category=? WHERE id=?"
        );
        return $stmt->execute([$n,$p,$i,$c,(int)$id]);
    }

    public function deleteProduct($id) {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id=?");
        return $stmt->execute([(int)$id]);
    }
}

/* ===============================
   INIT
=================================*/
$db = (new DB())->connect();
$admin = new Admin($db);

/* ===============================
   LOGIN
=================================*/
if(isset($_POST['login'])) {
    $user = $admin->login($_POST['email'], $_POST['password']);
    if($user){
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_email'] = $user['email'];
        header("Location: admin_panel.php");
        exit;
    } else {
        $error = "Invalid email or password!";
    }
}

if(isset($_GET['logout'])){
    session_destroy();
    header("Location: admin_panel.php");
    exit;
}

/* ===============================
   PROTECT PAGE
=================================*/
$loggedIn = isset($_SESSION['admin_id']);

/* ===============================
   PRODUCT ACTIONS
=================================*/
if($loggedIn && isset($_POST['add_prod'])){
    $admin->addProduct($_POST['name'],$_POST['price'],$_POST['image'],$_POST['category']);
    header("Location: admin_panel.php");
    exit;
}

if($loggedIn && isset($_POST['update_prod'])){
    $admin->updateProduct($_POST['id'],$_POST['name'],$_POST['price'],$_POST['image'],$_POST['category']);
    header("Location: admin_panel.php");
    exit;
}

if($loggedIn && isset($_GET['del_p'])){
    $admin->deleteProduct($_GET['del_p']);
    header("Location: admin_panel.php");
    exit;
}

/* ===============================
   USER ACTIONS
=================================*/
if($loggedIn && isset($_POST['add_user'])){
    $admin->addUser($_POST['firstname'],$_POST['lastname'],$_POST['email'],$_POST['password']);
    header("Location: admin_panel.php");
    exit;
}

if($loggedIn && isset($_GET['del_u'])){
    $deleteId = (int)$_GET['del_u'];
    if($deleteId !== (int)$_SESSION['admin_id']){
        $admin->deleteUser($deleteId);
    }
    header("Location: admin_panel.php");
    exit;
}

/* ===============================
   PAGINATION
=================================*/
$limit = 5;
$page = isset($_GET['page']) ? max((int)$_GET['page'],1) : 1;
$offset = ($page-1)*$limit;

$totalProducts = $admin->countProducts();
$totalPages = ceil($totalProducts/$limit);
$products = $admin->getProducts($limit,$offset);
$users = $admin->getUsers();

$editProduct = null;
if(isset($_GET['edit_p'])){
    $editProduct = $admin->getProduct($_GET['edit_p']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mobile Store Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .badge-cat { background: #eef2f7; color: #555; font-size: 0.75rem; text-transform: uppercase; }
    </style>
</head>
<body class="bg-light">

<?php if(!isset($_SESSION['admin_email'])): ?>
    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="card p-4 shadow-sm" style="width:400px; border-radius: 20px;">
            <h4 class="text-center mb-3 fw-bold">Admin Login</h4>
            <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form method="POST">
                <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
                <button name="login" class="btn btn-primary w-100 fw-bold py-2">Login</button>
            </form>
        </div>
    </div>
<?php else: ?>

<nav class="navbar navbar-dark bg-dark px-4">
    <span class="navbar-brand fw-bold">Mobile Store Admin</span>
    <div class="text-white">
        <?= $_SESSION['admin_email']; ?>
        <a href="?logout=1" class="btn btn-sm btn-outline-danger ms-3">Logout</a>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="card p-3 mb-4 shadow-sm" style="border-radius: 15px;">
                <h5 class="fw-bold mb-3">Inventory Management</h5>
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr><th>Image</th><th>Details</th><th>Price</th><th>Action</th></tr>
                    </thead>
                    <?php foreach($products as $p): ?>
                    <tr>
                        <td><img src="<?= $p['image'] ?>" width="50" class="rounded"></td>
                        <td>
                            <div class="fw-bold"><?= $p['name'] ?></div>
                            <span class="badge badge-cat"><?= $p['category'] ?? 'Uncategorized' ?></span>
                        </td>
                        <td class="text-primary fw-bold">₹<?= number_format($p['price'],2) ?></td>
                        <td>
                            <a href="?edit_p=<?= $p['id'] ?>&page=<?= $page ?>" class="btn btn-sm btn-light border">Edit</a>
                            <a href="?del_p=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete product?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>

                <nav><ul class="pagination pagination-sm justify-content-center">
                    <?php for($i=1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i==$page)?'active':'' ?>"><a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a></li>
                    <?php endfor; ?>
                </ul></nav>
            </div>

            <div class="card p-3 shadow-sm" style="border-radius: 15px;">
                <h5 class="fw-bold mb-3">System Users</h5>
                <table class="table table-sm">
                    <tr><th>Name</th><th>Email</th><th>Action</th></tr>
                    <?php foreach($users as $u): ?>
                    <tr>
                        <td><?= $u['firstname']." ".$u['lastname'] ?></td>
                        <td><?= $u['email'] ?></td>
                        <td>
                            <?php if($u['id'] != $_SESSION['admin_id']): ?>
                                <a href="?del_u=<?= $u['id'] ?>" class="text-danger text-decoration-none">Delete</a>
                            <?php else: ?>
                                <span class="text-muted">Self</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card p-4 mb-4 shadow-sm border-0" style="border-radius: 20px;">
                <h5 class="fw-bold mb-3 text-<?= $editProduct ? 'warning' : 'success'; ?>">
                    <?= $editProduct ? "Edit Product" : "Add New Product"; ?>
                </h5>
                <form method="POST">
                    <?php if($editProduct): ?>
                        <input type="hidden" name="id" value="<?= $editProduct['id']; ?>">
                    <?php endif; ?>

                    <label class="small fw-bold text-muted">Product Name</label>
                    <input type="text" name="name" class="form-control mb-2" value="<?= $editProduct['name'] ?? '' ?>" required>

                    <label class="small fw-bold text-muted">Category</label>
                    <select name="category" class="form-select mb-2" required>
                        <option value="mobile" <?= ($editProduct && $editProduct['category'] == 'mobile') ? 'selected' : ''; ?>>Mobile Phone</option>
                        <option value="bluetooth" <?= ($editProduct && $editProduct['category'] == 'bluetooth') ? 'selected' : ''; ?>>Bluetooth</option>
                        <option value="handsfree" <?= ($editProduct && $editProduct['category'] == 'handsfree') ? 'selected' : ''; ?>>Handsfree</option>
                        <option value="charger" <?= ($editProduct && $editProduct['category'] == 'charger') ? 'selected' : ''; ?>>Charger</option>
                        <option value="powerbank" <?= ($editProduct && $editProduct['category'] == 'powerbank') ? 'selected' : ''; ?>>Power Bank</option>
                        <option value="cables" <?= ($editProduct && $editProduct['category'] == 'cables') ? 'selected' : ''; ?>>Cables</option>
                        <option value="cover" <?= ($editProduct && $editProduct['category'] == 'cover') ? 'selected' : ''; ?>>Mobile Cover</option>
                    </select>

                    <label class="small fw-bold text-muted">Price (₹)</label>
                    <input type="number" step="0.01" name="price" class="form-control mb-2" value="<?= $editProduct['price'] ?? '' ?>" required>

                    <label class="small fw-bold text-muted">Image URL</label>
                    <input type="text" name="image" class="form-control mb-3" value="<?= $editProduct['image'] ?? '' ?>" required>

                    <?php if($editProduct): ?>
                        <button name="update_prod" class="btn btn-warning w-100 fw-bold">Save Changes</button>
                        <a href="admin_panel.php?page=<?= $page ?>" class="btn btn-light w-100 mt-2">Cancel</a>
                    <?php else: ?>
                        <button name="add_prod" class="btn btn-success w-100 fw-bold">Add to Inventory</button>
                    <?php endif; ?>
                </form>
            </div>

            <div class="card p-4 shadow-sm border-0" style="border-radius: 20px;">
                <h5 class="fw-bold mb-3">Add Administrator</h5>
                <form method="POST">
                    <div class="row">
                        <div class="col-6"><input name="firstname" class="form-control mb-2" placeholder="First" required></div>
                        <div class="col-6"><input name="lastname" class="form-control mb-2" placeholder="Last" required></div>
                    </div>
                    <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                    <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
                    <button name="add_user" class="btn btn-primary w-100 fw-bold">Register User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>

</body>
</html>