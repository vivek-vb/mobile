<?php 
include 'includes/header.php'; 
require_once 'includes/config.php';

// Add to Cart Logic (Same as before)
if (isset($_POST['add_to_cart'])) {
    if(!isset($user_id)){
        header('location:login.php');
        exit();
    }

    $p_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $p_price = $_POST['product_price'];
    $p_image = $_POST['product_image'];
    $p_qty = $_POST['product_quantity'];

    $check_cart = mysqli_query($conn, "SELECT * FROM cart WHERE name='$p_name' AND user_id='$user_id'");
    
    if (mysqli_num_rows($check_cart) > 0) {
        echo "<script>alert('This item is already in your cart');</script>";
    } else {
        mysqli_query($conn, "INSERT INTO cart(user_id, name, price, image, quantity) VALUES('$user_id','$p_name','$p_price','$p_image','$p_qty')");
        header('location:index.php');
        exit();
    }
}
?>

<style>
    /* ... (Keep your existing CSS styles here) ... */
    :root {
        --primary: #0071e3;
        --dark: #1d1d1f;
        --grey: #86868b;
        --glass-bg: rgba(255, 255, 255, 0.7);
    }
    body { 
        margin: 0;
        background-color: #f5f5f7;
        background-image: 
            radial-gradient(at 0% 0%, rgba(0, 113, 227, 0.08) 0px, transparent 50%),
            radial-gradient(at 100% 100%, rgba(94, 92, 230, 0.08) 0px, transparent 50%);
        background-attachment: fixed;
        color: var(--dark);
        font-family: -apple-system, BlinkMacSystemFont, sans-serif;
    }
    .main-content { padding: 80px 1.5rem; max-width: 1200px; margin: 0 auto; }
    .hero-text { text-align: center; margin-bottom: 80px; }
    .hero-text h2 { font-size: 3.5rem; font-weight: 800; letter-spacing: -2px; margin-bottom: 15px; background: linear-gradient(180deg, #1d1d1f, #434343); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .hero-text p { color: var(--grey); font-size: 1.2rem; }
    .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 35px; }
    .card { background: var(--glass-bg); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-radius: 28px; padding: 30px; text-align: center; border: 1px solid rgba(255, 255, 255, 0.4); transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); display: flex; flex-direction: column; justify-content: space-between; }
    .card:hover { transform: translateY(-12px); background: rgba(255, 255, 255, 0.9); box-shadow: 0 30px 60px rgba(0,0,0,0.1); }
    .card img { width: 100%; height: 220px; object-fit: contain; margin-bottom: 25px; transition: transform 0.4s ease; }
    .card:hover img { transform: scale(1.05); }
    .card h3 { margin: 0 0 10px 0; font-size: 1.4rem; font-weight: 700; }
    .price { color: var(--dark); font-size: 1.4rem; font-weight: 800; margin-bottom: 25px; display: block; }
    .qty-container { display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 20px; background: rgba(0,0,0,0.03); padding: 8px; border-radius: 12px; }
    .qty-input { width: 45px; padding: 5px; border: none; background: transparent; text-align: center; font-weight: 700; font-size: 1rem; outline: none; }
    .btn-buy { background: var(--primary); color: white; border: none; width: 100%; padding: 16px; border-radius: 14px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 113, 227, 0.2); }
    .btn-buy:hover { background: #0077ed; box-shadow: 0 6px 20px rgba(0, 113, 227, 0.4); transform: scale(1.02); }
</style>

<main class="main-content">
    <div class="hero-text">
        <h2>Smartphones.</h2>
        <p>The most powerful personal devices in the world.</p>
    </div>

    <section class="products-grid">
        <?php 
        // FILTER LOGIC: Only select products where category is 'mobile'
        $products_query = mysqli_query($conn, "SELECT * FROM products WHERE category = 'mobile'");
        
        if(mysqli_num_rows($products_query) > 0) {
            while($product = mysqli_fetch_assoc($products_query)) { 
        ?>
        <div class="card">
            <div>
                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" loading="lazy">
                <h3><?php echo $product['name']; ?></h3>
                <span class="price">₹<?php echo number_format($product['price']); ?></span>
            </div>
            
            <form method="post">
                <input type="hidden" name="product_name" value="<?php echo $product['name']; ?>">
                <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                <input type="hidden" name="product_image" value="<?php echo $product['image']; ?>">
                
                <div class="qty-container">
                    <span style="color: var(--grey); font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">Qty</span>
                    <input type="number" name="product_quantity" value="1" min="1" class="qty-input">
                </div>
                
                <button type="submit" name="add_to_cart" class="btn-buy">Add to Cart</button>
            </form>
        </div>
        <?php 
            }
        } else {
            echo "<p style='text-align:center; grid-column: 1/-1;'>No mobile phones available in this category.</p>";
        }
        ?>
    </section>
</main>

<?php include 'includes/footer.php'; ?>