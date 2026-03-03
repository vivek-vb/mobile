<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'includes/config.php'; // must contain $conn mysqli connection

include 'includes/header.php';
// ============================
// PAGINATION + FILTER
// ============================
$limit = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$offset = ($page - 1) * $limit;

$type = isset($_GET['type']) ? mysqli_real_escape_string($conn, $_GET['type']) : '';
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$where = "WHERE 1";

if($type != ''){
    $where .= " AND category='$type'";
}

if($search != ''){
    $where .= " AND name LIKE '%$search%'";
}

// Count products
$count_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM products $where");
$count_row = mysqli_fetch_assoc($count_query);
$total_products = $count_row['total'];
$total_pages = ceil($total_products / $limit);

// Fetch products
$products_query = mysqli_query($conn,
    "SELECT * FROM products $where ORDER BY id DESC LIMIT $limit OFFSET $offset"
);

// ============================
// ADD TO CART
// ============================
if (isset($_POST['add_to_cart'])) {

    if(!isset($_SESSION['user_id'])){
        header('location:login.php');
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $p_name  = mysqli_real_escape_string($conn, $_POST['product_name']);
    $p_price = $_POST['product_price'];
    $p_image = $_POST['product_image'];
    $p_qty   = $_POST['product_quantity'];

    $check = mysqli_query($conn, 
        "SELECT * FROM cart WHERE name='$p_name' AND user_id='$user_id'"
    );

    if(mysqli_num_rows($check) > 0){
        echo "<script>alert('Product already in cart');</script>";
    } else {
        mysqli_query($conn,
            "INSERT INTO cart(user_id,name,price,image,quantity)
             VALUES('$user_id','$p_name','$p_price','$p_image','$p_qty')"
        );
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Mobile Store</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    /* ===============================
   GLOBAL RESET
=================================*/
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}



/* --- MODERN GRADIENT BACKGROUND --- */
body {
    margin: 0;
    padding: 0;
    min-height: 100vh;
    /* This creates a "Mesh" effect using multiple radial gradients */
    background-color: #f5f5f7; /* Fallback color */
    background-image: 
        radial-gradient(at 0% 0%, rgba(0, 113, 227, 0.15) 0px, transparent 50%),
        radial-gradient(at 100% 0%, rgba(94, 92, 230, 0.15) 0px, transparent 50%),
        radial-gradient(at 100% 100%, rgba(0, 113, 227, 0.1) 0px, transparent 50%),
        radial-gradient(at 0% 100%, rgba(94, 92, 230, 0.1) 0px, transparent 50%);
    background-attachment: fixed; /* Keeps the background still while you scroll */
    background-repeat: no-repeat;
    background-size: cover;
}

/* --- GLASSMORPHISM UTILITY (Add this class to your .product-card) --- */
.product-card {
    background: rgba(255, 255, 255, 0.6) !important; /* Semi-transparent white */
    backdrop-filter: blur(12px); /* The "Frosted Glass" effect */
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.4) !important;
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07) !important;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-card:hover {
    transform: translateY(-10px);
    background: rgba(255, 255, 255, 0.8) !important;
    box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.15) !important;
}

/* --- OPTIONAL: ANIMATED TEXT GRADIENT FOR TITLES --- */
.hero h1 {
    background: linear-gradient(90deg, #0071e3, #5e5ce6, #0071e3);
    background-size: 200% auto;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: gradient-flow 5s linear infinite;
}

@keyframes gradient-flow {
    to { background-position: 200% center; }
}

/* ===============================
   HERO SECTION
=================================*/
.hero{
    background:linear-gradient(135deg,#ffffff,#f2f2f2);
    text-align:center;
    padding:100px 20px;
    border-bottom:1px solid #e5e5e5;
    background-color: #f5f5f7; /* Fallback color */
    background-image: 
        radial-gradient(at 0% 0%, rgba(0, 113, 227, 0.15) 0px, transparent 50%),
        radial-gradient(at 100% 0%, rgba(94, 92, 230, 0.15) 0px, transparent 50%),
        radial-gradient(at 100% 100%, rgba(0, 113, 227, 0.1) 0px, transparent 50%),
        radial-gradient(at 0% 100%, rgba(94, 92, 230, 0.1) 0px, transparent 50%);
    background-attachment: fixed; /* Keeps the background still while you scroll */
    background-repeat: no-repeat;
    background-size: cover;
}

.hero h1{
    font-size:3.2rem;
    font-weight:800;
    letter-spacing:-1px;
    margin-bottom:15px;
}

.hero p{
    color:#6e6e73;
    font-size:1.2rem;
    max-width:600px;
    margin:auto;
}

/* ===============================
   SEARCH BAR
=================================*/
.search-box{
    text-align:center;
    margin:50px 0;
}

.search-box input{
    width:300px;
    padding:12px 15px;
    border-radius:30px;
    border:1px solid #ddd;
    font-size:0.95rem;
    transition:0.3s;
}

.search-box input:focus{
    outline:none;
    border-color:#0071e3;
    box-shadow:0 0 0 3px rgba(0,113,227,0.1);
}

/* ===============================
   CATEGORY BUTTONS
=================================*/
.cat-container{
    display:flex;
    justify-content:center;
    gap:15px;
    flex-wrap:wrap;
    margin-bottom:50px;
}

.cat-card{
    background:#fff;
    padding:10px 20px;
    border-radius:50px;
    border:1px solid #ddd;
    text-decoration:none;
    color:#1d1d1f;
    font-weight:600;
    transition:0.3s;
}

.cat-card:hover{
    background:#0071e3;
    color:#fff;
    border-color:#0071e3;
    transform:translateY(-3px);
}

/* ===============================
   CONTAINER
=================================*/
.container{
    max-width:1200px;
    margin:auto;
    padding:0 20px;
}

/* ===============================
   PRODUCT GRID
=================================*/
.products-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(260px,1fr));
    gap:30px;
}

/* ===============================
   PRODUCT CARD
=================================*/
.product-card{
    background:#fff;
    border-radius:20px;
    padding:25px;
    text-align:center;
    transition:all 0.3s ease;
    border:1px solid transparent;
}

.product-card:hover{
    transform:translateY(-8px);
    box-shadow:0 15px 35px rgba(0,0,0,0.08);
    border-color:#eee;
}

.product-card img{
    width:100%;
    height:200px;
    object-fit:contain;
    margin-bottom:20px;
}

.product-card h3{
    font-size:1.1rem;
    margin-bottom:10px;
    font-weight:600;
}

.price{
    display:block;
    font-size:1.1rem;
    font-weight:700;
    color:#0071e3;
    margin-bottom:15px;
}

/* ===============================
   QUANTITY INPUT
=================================*/
.product-card input[type="number"]{
    width:70px;
    padding:6px;
    border-radius:8px;
    border:1px solid #ccc;
    margin-bottom:15px;
}

/* ===============================
   BUTTONS
=================================*/
button{
    background:#0071e3;
    color:#fff;
    border:none;
    padding:12px;
    width:100%;
    border-radius:12px;
    font-weight:700;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    background:#005bb5;
    transform:translateY(-2px);
}
.main-nav {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    position: sticky;
    top: 0;
    z-index: 1000;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.nav-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    font-size: 1.5rem;
    font-weight: 800;
    text-decoration: none;
    color: #1d1d1f;
}

.logo span { color: #0071e3; }

.nav-links {
    list-style: none;
    display: flex;
    gap: 30px;
    margin: 0;
    align-items: center;
}

.nav-links a {
    text-decoration: none;
    color: #1d1d1f;
    font-weight: 500;
    font-size: 0.95rem;
    transition: 0.3s;
}

.nav-links a:hover { color: #0071e3; }

.nav-btn {
    background: #0071e3;
    color: #fff !important;
    padding: 8px 20px;
    border-radius: 20px;
}

/* ===============================
   PAGINATION
=================================*/
.pagination{
    display:flex;
    justify-content:center;
    margin:60px 0;
    gap:10px;
}

.page-btn{
    padding:10px 16px;
    border-radius:10px;
    border:1px solid #ddd;
    background:#fff;
    text-decoration:none;
    color:#1d1d1f;
    font-weight:600;
    transition:0.3s;
}

.page-btn:hover{
    background:#0071e3;
    color:#fff;
    border-color:#0071e3;
}

.page-btn.active{
    background:#0071e3;
    color:#fff;
    border-color:#0071e3;
}

/* ===============================
   RESPONSIVE DESIGN
=================================*/
@media (max-width:768px){

    .hero h1{
        font-size:2.2rem;
    }

    .search-box input{
        width:90%;
    }

    .products-grid{
        grid-template-columns:repeat(auto-fill,minmax(180px,1fr));
    }

    .product-card{
        padding:15px;
    }

    .product-card img{
        height:150px;
    }
}

/* ===============================
   SMOOTH SCROLL
=================================*/
html{
    scroll-behavior:smooth;
}
</style>
</head>
    
<body>

<section class="hero">
    <h1>Mobile Store</h1>
    <p>Premium smartphones & accessories</p>
</section>

<div class="search-box">
    <form method="GET">
        <input type="text" name="search" placeholder="Search products...">
    </form>
</div>

<div class="cat-container">
    <a href="?type=bluetooth" class="cat-card">Bluetooth</a>
    <a href="?type=handsfree" class="cat-card">Handsfree</a>
    <a href="?type=charger" class="cat-card">Chargers</a>
    <a href="?type=powerbank" class="cat-card">Power Bank</a>
    <a href="?type=cables" class="cat-card">Cables</a>
    <a href="?type=cover" class="cat-card">Covers</a>
</div>

<div class="container">
    <div class="products-grid">

        <?php while($product = mysqli_fetch_assoc($products_query)) { ?>
        <div class="product-card">
            <img src="<?php echo $product['image']; ?>">
            <h3><?php echo $product['name']; ?></h3>
            <p class="price">₹<?php echo number_format($product['price']); ?></p>

            <form method="post">
                <input type="hidden" name="product_name" value="<?php echo $product['name']; ?>">
                <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                <input type="hidden" name="product_image" value="<?php echo $product['image']; ?>">
                <input type="number" name="product_quantity" value="1" min="1">
                <br><br>
                <button type="submit" name="add_to_cart">Add to Cart</button>
            </form>
        </div>
        <?php } ?>

    </div>

    <?php if($total_pages > 1): ?>
    <div class="pagination">
        <?php for($i=1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" 
               class="page-btn <?php if($i==$page) echo 'active'; ?>">
               <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>

</div>

</body>
</html>