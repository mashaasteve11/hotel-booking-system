<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaka Grand Hotel</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <!-- NAVIGATION -->
    <nav class="navbar">
        <div class="logo">
    <img src="images/logo.svg" alt="Kaka Grand Hotel">
</div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="rooms.php">Rooms</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
            <?php if(isset($_SESSION['user_id'])): ?>
                <li><a href="dashboard.php">My Bookings</a></li>
                <li><a href="logout.php" class="btn-nav">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php" class="btn-nav">Login</a></li>
                <li><a href="register.php" class="btn-nav btn-register">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to Kaka Grand Hotel</h1>
            <p>Experience luxury and comfort in the heart of Nairobi, Kenya</p>
            <a href="rooms.php" class="btn-hero">View Our Rooms</a>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="features">
        <div class="feature-card">
            <i class="fas fa-wifi"></i>
            <h3>Free WiFi</h3>
            <p>High speed internet in all rooms</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-utensils"></i>
            <h3>Restaurant</h3>
            <p>World class dining experience</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-swimming-pool"></i>
            <h3>Swimming Pool</h3>
            <p>Heated pool open 24 hours</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-spa"></i>
            <h3>Spa & Wellness</h3>
            <p>Relax and rejuvenate your body</p>
        </div>
    </section>

    <!-- ABOUT SECTION -->
    <section class="about" id="about">
        <div class="about-text">
            <h2>About Our Hotel</h2>
            <p>Kaka Grand Hotel is a 5-star luxury hotel located in the heart of Nairobi, Kenya. We offer world-class accommodation, dining and conference facilities for both business and leisure travelers.</p>
            <p>With over 20 years of hospitality excellence, we pride ourselves in delivering unforgettable experiences to our guests from around the world.</p>
            <a href="rooms.php" class="btn-about">Book a Room</a>
        </div>
        <div class="about-image">
            <i class="fas fa-hotel"></i>
        </div>
    </section>

    <!-- CONTACT SECTION -->
    <section class="contact" id="contact">
        <h2>Contact Us</h2>
        <div class="contact-grid">
            <div class="contact-item">
                <i class="fas fa-map-marker-alt"></i>
                <p>Nairobi CBD, Kenya</p>
            </div>
            <div class="contact-item">
                <i class="fas fa-phone"></i>
                <p>+254 710 304 802</p>
            </div>
            <div class="contact-item">
                <i class="fas fa-envelope"></i>
                <p>stevieskaka3096@gmail.com</p>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2026 Serena Grand Hotel. Built by Steve Macharia.</p>
    </footer>

</body>
</html>