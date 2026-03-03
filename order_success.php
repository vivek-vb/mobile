<?php
$pageTitle = 'Order Confirmed';
require_once 'includes/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if they try to access this page without a recent order
// (Optional: You can set this session variable in your checkout.php logic)
/*
if(!isset($_SESSION['order_placed'])){
    header('location:index.php');
    exit();
}
*/

include 'includes/header.php';
?>

<style>
    :root {
        --success: #34c759;
        --primary: #0071e3;
    }

    .success-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 70vh;
        text-align: center;
        padding: 20px;
    }

    /* --- CHECKMARK ANIMATION --- */
    .checkmark-wrapper {
        width: 100px;
        height: 100px;
        position: relative;
        margin-bottom: 30px;
    }

    .checkmark-circle {
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        stroke-width: 2;
        stroke-miterlimit: 10;
        stroke: var(--success);
        fill: none;
        animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }

    .checkmark {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: block;
        stroke-width: 2;
        stroke: #fff;
        stroke-miterlimit: 10;
        box-shadow: inset 0px 0px 0px var(--success);
        animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
    }

    .checkmark-check {
        transform-origin: 50% 50%;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
    }

    @keyframes stroke { 100% { stroke-dashoffset: 0; } }
    @keyframes scale { 0%, 100% { transform: none; } 50% { transform: scale3d(1.1, 1.1, 1); } }
    @keyframes fill { 100% { box-shadow: inset 0px 0px 0px 50px var(--success); } }

    /* --- TEXT STYLES --- */
    .success-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 10px;
        letter-spacing: -1px;
    }

    .success-msg {
        color: #86868b;
        font-size: 1.1rem;
        margin-bottom: 30px;
    }

    .redirect-text {
        font-size: 0.9rem;
        color: #aeaeb2;
    }

    .timer-num {
        font-weight: 700;
        color: var(--primary);
    }

    .home-btn {
        margin-top: 20px;
        display: inline-block;
        padding: 12px 30px;
        background: var(--primary);
        color: white;
        text-decoration: none;
        border-radius: 20px;
        font-weight: 600;
        transition: 0.3s;
    }

    .home-btn:hover {
        transform: scale(1.05);
        opacity: 0.9;
    }
</style>

<div class="success-container">
    <div class="checkmark-wrapper">
        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
            <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
            <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
        </svg>
    </div>

    <h1 class="success-title">Order Confirmed!</h1>
    <p class="success-msg">Thank you for your purchase. We've sent a confirmation email to your inbox.</p>
    
    <a href="index.php" class="home-btn">Go to Home Now</a>

    <p class="redirect-text">You will be redirected automatically in <span id="timer" class="timer-num">5</span> seconds...</p>
</div>

<script>
    // Countdown Timer Logic
    let seconds = 5;
    const timerDisplay = document.getElementById('timer');

    const countdown = setInterval(() => {
        seconds--;
        timerDisplay.textContent = seconds;
        
        if (seconds <= 0) {
            clearInterval(countdown);
            window.location.href = 'index.php'; // Redirect to home
        }
    }, 1000);
</script>

<?php include 'includes/footer.php'; ?>