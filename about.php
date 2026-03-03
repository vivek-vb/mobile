<?php
$pageTitle = 'About Us | Mobile Store';
// require_once 'includes/config.php'; // Uncomment if needed
include 'includes/header.php';      // Uncomment if you have a header file
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <style>
        :root {
            --primary: #0071e3;
            --secondary: #5e5ce6;
            --text-dark: #1d1d1f;
            --text-muted: #86868b;
            --glass: rgba(255, 255, 255, 0.75);
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            margin: 0;
            color: var(--text-dark);
            background: #f5f5f7;
            /* Mesh Gradient Background */
            background-image: 
                radial-gradient(at 0% 0%, rgba(0, 113, 227, 0.1) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(94, 92, 230, 0.1) 0px, transparent 50%);
            background-attachment: fixed;
            line-height: 1.6;
        }

        /* Hero Section */
        .about-hero {
            text-align: center;
            padding: 100px 20px 60px;
        }

        .about-hero h1 {
            font-size: 3.5rem;
            letter-spacing: -0.02em;
            margin-bottom: 20px;
            background: linear-gradient(90deg, #1d1d1f, #434343);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .about-hero p {
            font-size: 1.4rem;
            color: var(--text-muted);
            max-width: 700px;
            margin: 0 auto;
        }

        /* Main Content Container */
        .content-wrapper {
            max-width: 1000px;
            margin: 0 auto 100px;
            padding: 0 20px;
        }

        /* Feature Card Layout */
        .about-card {
            background: var(--glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 30px;
            padding: 60px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.04);
        }

        .intro-text {
            font-size: 1.25rem;
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 40px;
            border-left: 4px solid var(--primary);
            padding-left: 25px;
        }

        .story-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 40px;
        }

        .story-item h3 {
            font-size: 1.5rem;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .story-item p {
            color: var(--text-muted);
            font-size: 1.05rem;
            margin-bottom: 0;
        }

        /* Statistics Section */
        .stats-bar {
            display: flex;
            justify-content: space-around;
            margin-top: 60px;
            padding-top: 40px;
            border-top: 1px solid rgba(0,0,0,0.05);
            text-align: center;
        }

        .stat-box h4 {
            font-size: 2rem;
            margin: 0;
            color: var(--text-dark);
        }

        .stat-box span {
            color: var(--text-muted);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        @media (max-width: 768px) {
            .about-hero h1 { font-size: 2.5rem; }
            .story-grid { grid-template-columns: 1fr; }
            .about-card { padding: 30px; }
            .stats-bar { flex-direction: column; gap: 30px; }
        }
    </style>
</head>
<body>

<section class="about-hero">
    <h1>Driven by Innovation.</h1>
    <p>We don't just sell phones. We deliver the future of connectivity directly to your hands.</p>
</section>

<main class="content-wrapper">
    <div class="about-card">
        
        <p class="intro-text">
            Welcome to Mobile Store, the ultimate destination for premium mobile technology. 
            Nestled in the heart of the city, we are a bustling hub where cutting-edge 
            innovation meets deeply personalized service.
        </p>

        <div class="story-grid">
            <div class="story-item">
                <h3>Our Collection</h3>
                <p>From the latest flagship smartphones to essential accessories, we curate a diverse array of tech from world-renowned brands. Whether you're a pro user or budget-conscious, we have the perfect fit.</p>
            </div>
            <div class="story-item">
                <h3>The Experience</h3>
                <p>Our sleek, modern layout invites you to explore. Experience vivid displays and interactive demo areas designed to let you feel the potential of every device before you take it home.</p>
            </div>
            <div class="story-item">
                <h3>Expert Guidance</h3>
                <p>Our staff aren't just salespeople—they are tech enthusiasts. We provide expert assistance with setup, troubleshooting, and upgrades to ensure you stay ahead of the curve.</p>
            </div>
            <div class="story-item">
                <h3>Our Commitment</h3>
                <p>Customer satisfaction is our DNA. Our relationship starts, rather than ends, at the point of sale. We provide 24/7 support and genuine parts for all major brands.</p>
            </div>
        </div>

        <div class="stats-bar">
            <div class="stat-box">
                <h4>10k+</h4>
                <span>Happy Clients</span>
            </div>
            <div class="stat-box">
                <h4>15+</h4>
                <span>Global Brands</span>
            </div>
            <div class="stat-box">
                <h4>24/7</h4>
                <span>Support</span>
            </div>
        </div>

    </div>
</main>

</body>
</html>

<?php include 'includes/footer.php'; ?>