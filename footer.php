<style>
    .footer {
        background: var(--dark);
        color: white;
        padding: 50px 2rem 20px;
        margin-top: 80px;
    }

    .footer-content {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 40px;
    }

    .footer-section h4 { margin-bottom: 20px; font-size: 1.1rem; }
    
    .footer-section p, .footer-section a {
        color: #86868b;
        text-decoration: none;
        font-size: 0.9rem;
        display: block;
        margin-bottom: 10px;
    }

    .footer-bottom {
        text-align: center;
        margin-top: 50px;
        padding-top: 20px;
        border-top: 1px solid #333;
        color: #555;
        font-size: 0.8rem;
    }
</style>

<footer class="footer">
    <div class="footer-content">
        <div class="footer-section">
            <h4>Mobile Store</h4>
            <p>Providing the best tech at the best prices since 2024.</p>
        </div>
        <div class="footer-section">
            <h4>Quick Links</h4>
            <a href="index.php">Home</a>
            <a href="cart.php">My Cart</a>
            <a href="about.php">About Us</a>
        </div>
        <div class="footer-section">
            <h4>Support</h4>
            <a href="#">Contact Support</a>
            <a href="#">Shipping Policy</a>
            <a href="#">Privacy Policy</a>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; <?php echo date("Y"); ?> Mobile Store UI. All rights reserved.
    </div>
</footer>
</body>
</html>