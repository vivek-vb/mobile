<?php 
include 'includes/header.php'; 

// 1. Get the category from the URL (e.g., category.php?type=bluetooth)
$type = isset($_GET['type']) ? mysqli_real_escape_string($conn, $_GET['type']) : 'mobile';

// 2. Add to Cart Logic
if (isset($_POST['add_to_cart'])) {
    $p_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $p_price = $_POST['product_price'];
    $p_image = $_POST['product_image'];
    $p_qty = $_POST['product_quantity'];

    mysqli_query($conn, "INSERT INTO cart(user_id, name, price, image, quantity) VALUES('$user_id','$p_name','$p_price','$p_image','$p_qty')");
    header("location:category.php?type=$type");
    exit();
}
?>

<div class="main-content" style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">
    <h2 style="text-transform: capitalize; font-size: 2.5rem; margin-bottom: 30px;">
        Explore <?php echo $type; ?>
    </h2>

    <div class="products-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px;">
        <?php 
        // 3. Fetch products where category matches the link clicked
        $select_products = mysqli_query($conn, "SELECT * FROM products WHERE category = '$type'");
        
        if(mysqli_num_rows($select_products) > 0) {
            while($product = mysqli_fetch_assoc($select_products)) { ?>
                <div class="card" style="background:#fff; padding:25px; border-radius:20px; text-align:center; border:1px solid #eee;">
                    <img src="<?php echo $product['image']; ?>" style="width:100%; height:200px; object-fit:contain;">
                    <h3><?php echo $product['name']; ?></h3>
                    <span class="price" style="color:#0071e3; font-weight:700; display:block; margin: 10px 0;">₹<?php echo number_format($product['price']); ?></span>
                    
                    <form method="post">
                        <input type="hidden" name="product_name" value="<?php echo $product['name']; ?>">
                        <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                        <input type="hidden" name="product_image" value="<?php echo $product['image']; ?>">
                        <input type="number" name="product_quantity" value="1" min="1" style="width:50px; margin-bottom:10px; padding:5px; border-radius:5px; border:1px solid #ddd;"><br>
                        <button type="submit" name="add_to_cart" class="btn-buy" style="background:#0071e3; color:#fff; border:none; width:100%; padding:12px; border-radius:10px; font-weight:700; cursor:pointer;">Add to Cart</button>
                    </form>
                </div>
            <?php } 
        } else {
            echo "<p>No products found in the $type category.</p>";
        } ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>