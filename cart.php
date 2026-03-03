<?php
$pageTitle = 'Your Cart';
require_once 'includes/config.php';

// Ensure session is started and user is logged in
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$user_id = $_SESSION['user_id'] ?? header('location:login.php');

/* --- REMOVE SINGLE ITEM --- */
if(isset($_GET['remove'])){
   $remove_id = intval($_GET['remove']);
   $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
   $stmt->bind_param("ii", $remove_id, $user_id);
   $stmt->execute();
   $stmt->close();
   header('location:cart.php');
   exit();
}

/* --- DELETE ALL ITEMS --- */
if(isset($_GET['delete_all'])){
   $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
   $stmt->bind_param("i", $user_id);
   $stmt->execute();
   $stmt->close();
   header('location:cart.php');
   exit();
}

/* --- UPDATE QUANTITY --- */
if(isset($_POST['update_cart'])){
   $cart_id = intval($_POST['cart_id']);
   $cart_quantity = intval($_POST['cart_quantity']);
   if($cart_quantity < 1) $cart_quantity = 1;

   $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
   $stmt->bind_param("iii", $cart_quantity, $cart_id, $user_id);
   $stmt->execute();
   $stmt->close();
   header('location:cart.php');
   exit();
}

include 'includes/header.php';
?>

<style>
    /* ADDED MISSING VARIABLES */
    :root {
        --primary: #0071e3;
        --dark: #1d1d1f;
        --grey: #86868b;
        --danger: #ff3b30;
        --white: #ffffff;
        --light-bg: #f5f5f7;
        --shadow: 0 8px 30px rgba(0,0,0,0.05);
    }

    .shopping-cart {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
        min-height: 60vh;
    }

    .heading {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 40px;
        text-align: center;
        letter-spacing: -1px;
    }

    .cart-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 15px;
    }

    .cart-table thead th {
        padding: 10px 20px;
        color: var(--grey);
        font-weight: 500;
        text-align: left;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 1px;
    }

    .cart-table tbody tr {
        background: var(--white);
        box-shadow: var(--shadow);
        transition: transform 0.2s;
    }

    .cart-table td {
        padding: 20px;
        vertical-align: middle;
    }

    /* Rounding the corners of the floating rows */
    .cart-table td:first-child { border-radius: 20px 0 0 20px; }
    .cart-table td:last-child { border-radius: 0 20px 20px 0; }

    /* IMAGE STYLING FIX */
    .product-img {
        width: 100px;
        height: 100px;
        object-fit: contain;
        background: #fff;
        border-radius: 12px;
        border: 1px solid #eee;
    }

    .qty-form { display: flex; gap: 8px; align-items: center; }
    
    .qty-input {
        width: 50px;
        padding: 10px;
        border: 1px solid #d2d2d7;
        border-radius: 10px;
        font-weight: 600;
    }

    .option-btn {
        background: var(--dark);
        color: white;
        padding: 10px 18px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: 0.3s;
    }

    .delete-text {
        color: var(--danger);
        text-decoration: none;
        font-weight: 600;
    }

    .grand-total-row td {
        background: transparent !important;
        box-shadow: none !important;
        font-size: 1.2rem;
        padding-top: 40px;
    }

    .cart-bottom-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 50px;
        padding: 30px;
        background: rgba(255,255,255,0.5);
        border-radius: 24px;
        backdrop-filter: blur(10px);
    }

    .checkout-btn {
        background: var(--primary);
        color: white;
        padding: 18px 45px;
        border-radius: 16px;
        text-decoration: none;
        font-weight: 700;
        box-shadow: 0 10px 20px rgba(0, 113, 227, 0.2);
    }

    .disabled { opacity: 0.5; pointer-events: none; }
</style>



<div class="shopping-cart">
    <h1 class="heading">Review Your Bag</h1>

    <table class="cart-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $grand_total = 0;
        $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            while($fetch_cart = $result->fetch_assoc()){
                $sub_total = $fetch_cart['price'] * $fetch_cart['quantity'];
                $grand_total += $sub_total;
        ?>
            <tr>
                <td><img src="<?php echo $fetch_cart['image']; ?>" class="product-img"></td>
                <td style="font-weight: 700;"><?php echo htmlspecialchars($fetch_cart['name']); ?></td>
                <td>₹<?php echo number_format($fetch_cart['price']); ?></td>
                <td>
                    <form method="post" class="qty-form">
                        <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                        <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>" class="qty-input">
                        <input type="submit" name="update_cart" value="Update" class="option-btn">
                    </form>
                </td>
                <td style="font-weight: 800; color: var(--primary);">₹<?php echo number_format($sub_total); ?></td>
                <td>
                    <a href="cart.php?remove=<?php echo $fetch_cart['id']; ?>" 
                       onclick="return confirm('Remove this item?');" 
                       class="delete-text">Remove</a>
                </td>
            </tr>
        <?php
            }
        } else {
            echo '<tr><td colspan="6" style="text-align:center; padding:100px;">
                    <h2 style="color:var(--grey)">Your bag is empty.</h2>
                    <a href="index.php" class="checkout-btn" style="margin-top:20px; display:inline-block;">Go to Shop</a>
                  </td></tr>';
        }
        ?>

        <?php if($grand_total > 0): ?>
        <tr class="grand-total-row">
            <td colspan="4" style="text-align: right;"><strong>Bag Total:</strong></td>
            <td colspan="2">
                <span style="color: var(--dark); font-size: 2rem; font-weight: 800;">
                    ₹<?php echo number_format($grand_total); ?>
                </span>
            </td>
        </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <?php if($grand_total > 0): ?>
    <div class="cart-bottom-actions">
        <a href="cart.php?delete_all" 
           onclick="return confirm('Are you sure you want to clear your entire bag?');" 
           class="delete-text" 
           style="padding: 12px 24px; border: 1px solid var(--danger); border-radius: 12px;">
           Clear Bag
        </a>
        
        <div style="display: flex; gap: 20px; align-items: center;">
            <a href="index.php" style="text-decoration:none; color:var(--dark); font-weight:600;">Continue Shopping</a>
            <a href="checkout.php" class="checkout-btn">
                Check Out
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>