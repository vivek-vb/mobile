<?php
require_once 'includes/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user_id = $_SESSION['user_id'] ?? header('location:login.php');

if(isset($_POST['order_btn'])){
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $number = $_POST['number'];
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $method = mysqli_real_escape_string($conn, $_POST['method']);
   $address = mysqli_real_escape_string($conn, 'flat no. '. $_POST['flat'].', '. $_POST['street'].', '. $_POST['city'].', '. $_POST['state'].', '. $_POST['country'].' - '. $_POST['pin_code']);
   $placed_on = date('d-M-Y');

   $cart_total = 0;
   $cart_products[] = '';

   $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'");
   if(mysqli_num_rows($cart_query) > 0){
      while($cart_item = mysqli_fetch_assoc($cart_query)){
         $cart_products[] = $cart_item['name'].' ('.$cart_item['quantity'].') ';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      }
   }

   $total_products = implode(', ', $cart_products);

   if($cart_total == 0){
      $message[] = 'Your cart is empty';
   }else{
      $insert_order = mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')");
      
      if($insert_order){
         mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'");
         header('location:order_success.php');
         exit();
      }
   }
}

include 'includes/header.php';
?>

<style>
   .checkout-container { max-width: 1100px; margin: 50px auto; padding: 0 20px; display: grid; grid-template-columns: 1fr 1.5fr; gap: 40px; }
   
   /* Summary Section */
   .display-order { background: #fff; padding: 30px; border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); align-self: start; }
   .display-order h3 { border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px; }
   .display-order span { display: block; margin-bottom: 10px; color: #666; font-size: 0.95rem; }
   .grand-total { font-size: 1.5rem; color: var(--primary); font-weight: 800; margin-top: 20px; border-top: 1px solid #eee; padding-top: 15px; }

   /* Form Section */
   .checkout-form { background: #fff; padding: 40px; border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
   .flex { display: flex; flex-wrap: wrap; gap: 20px; }
   .inputBox { flex: 1 1 45%; }
   .inputBox span { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
   .inputBox input, .inputBox select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 10px; outline: none; transition: 0.3s; }
   .inputBox input:focus { border-color: var(--primary); }

   /* --- PREMIUM CONFIRM BUTTON --- */
.order-btn {
    width: 100%;
    background: linear-gradient(180deg, #0071e3 0%, #0062c3 100%);
    color: #fff;
    padding: 18px;
    border: none;
    border-radius: 16px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    margin-top: 30px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 15px rgba(0, 113, 227, 0.25);
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
}

.order-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 113, 227, 0.4);
    background: linear-gradient(180deg, #0077ed 0%, #006bd6 100%);
}

.order-btn:active {
    transform: translateY(0) scale(0.98);
}

/* Optional: Add an icon or arrow after the text */
.order-btn::after {
    content: '→';
    font-size: 1.2rem;
    transition: transform 0.3s ease;
}

.order-btn:hover::after {
    transform: translateX(5px);
}

/* Disabled state when the cart is empty */
.order-btn:disabled {
    background: #d2d2d7;
    cursor: not-allowed;
    box-shadow: none;
    transform: none;
}
   @media (max-width: 768px) { .checkout-container { grid-template-columns: 1fr; } }
</style>

<div class="checkout-container">

   <div class="display-order">
      <h3>Order Summary</h3>
      <?php
         $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'");
         $total = 0;
         if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){
               $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
               $total += $total_price;
      ?>
      <span><?= $fetch_cart['name']; ?> (<?= $fetch_cart['quantity']; ?>) <b style="float:right;">₹<?= number_format($total_price); ?></b></span>
      <?php
            }
         }else{
            echo "<span>Your cart is empty!</span>";
         }
      ?>
      <div class="grand-total">Total: <span>₹<?= number_format($total); ?></span></div>
   </div>

   <form action="" method="post" class="checkout-form">
      <h3>Shipping Details</h3>
      <div class="flex">
         <div class="inputBox">
            <span>Full Name</span>
            <input type="text" placeholder="e.g. John Doe" name="name" required>
         </div>
         <div class="inputBox">
            <span>Phone Number</span>
            <input type="number" placeholder="Enter your 10 digit number" name="number" required>
         </div>
         <div class="inputBox">
            <span>Email</span>
            <input type="email" placeholder="email@example.com" name="email" required>
         </div>
         <div class="inputBox">
            <span>Payment Method</span>
            <select name="method">
               <option value="cash on delivery" selected>Cash on Delivery</option>
               <option value="credit card">Credit Card</option>
               <option value="upi">UPI / Google Pay</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Flat / House No.</span>
            <input type="text" placeholder="e.g. Flat No. 101" name="flat" required>
         </div>
         <div class="inputBox">
            <span>Street / Area</span>
            <input type="text" placeholder="e.g. MG Road" name="street" required>
         </div>
         <div class="inputBox">
            <span>City</span>
            <input type="text" placeholder="e.g. Mumbai" name="city" required>
         </div>
         <div class="inputBox">
            <span>State</span>
            <input type="text" placeholder="e.g. Maharashtra" name="state" required>
         </div>
         <div class="inputBox">
            <span>Country</span>
            <input type="text" placeholder="e.g. India" name="country" required>
         </div>
         <div class="inputBox">
            <span>Pin Code</span>
            <input type="number" placeholder="e.g. 123456" name="pin_code" required>
         </div>
      </div>
      <input type="submit" value="Complete Purchase" name="order_btn" class="order-btn">
   </form>

</div>

<?php include 'includes/footer.php'; ?>