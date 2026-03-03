<?php
// Note: session_start() should be at the very top of your main index.php file
?>
<style>
    /* Navbar Core Styles */
    .glass-nav {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        position: sticky;
        top: 0;
        z-index: 9999;
        padding: 12px 0;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
    }

    .nav-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px;
    }

    .brand-logo {
        font-size: 1.6rem;
        font-weight: 800;
        text-decoration: none;
        color: #1d1d1f;
        letter-spacing: -0.5px;
    }

    .brand-logo span {
        color: #0071e3;
    }

    .nav-menu {
        display: flex;
        gap: 30px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .nav-item a {
        text-decoration: none;
        color: #1d1d1f;
        font-weight: 500;
        font-size: 0.95rem;
        transition: color 0.3s ease;
    }

    .nav-item a:hover {
        color: #0071e3;
    }

    /* Cart Bubble Style */
    .cart-badge {
        background: #0071e3;
        color: white;
        font-size: 0.7rem;
        padding: 2px 6px;
        border-radius: 10px;
        margin-left: 4px;
        vertical-align: middle;
    }

    /* Action Button (Login/Logout) */
    .nav-btn {
        background: #1d1d1f;
        color: #fff !important;
        padding: 8px 20px;
        border-radius: 20px;
        transition: transform 0.2s ease, background 0.2s ease;
    }

    .nav-btn:hover {
        background: #333;
        transform: scale(1.05);
    }

    /* Mobile Responsive Logic */
    @media (max-width: 768px) {
        .nav-menu { gap: 15px; }
        .nav-item:not(.mobile-visible) { display: none; }
    }
</style>

<header class="glass-nav">
    <div class="nav-wrapper">
        <a href="index.php" class="brand-logo">Mobile<span>Store</span></a>

        <ul class="nav-menu">
            <li class="nav-item"><a href="index.php">Home</a></li>
            <li class="nav-item"><a href="mobiles.php">mobiles</a></li>
            <li class="nav-item"><a href="about.php">About</a></li>
            <li class="nav-item mobile-visible">
                <a href="cart.php">
                    Cart
                    <?php 
                        // Example: Show cart count if items exist
                        if(isset($_SESSION['cart_count']) && $_SESSION['cart_count'] > 0) {
                            echo '<span class="cart-badge">'.$_SESSION['cart_count'].'</span>';
                        }
                    ?>
                </a>
            </li>
            
            <li class="nav-item mobile-visible">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="logout.php" class="nav-btn">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="nav-btn">Login</a>
                <?php endif; ?>
            </li>
        </ul>
    </div>
</header>