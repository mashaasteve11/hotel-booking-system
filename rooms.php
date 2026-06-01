<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Rooms - Kaka Grand Hotel</title>
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
            <li><a href="index.php#about">About</a></li>
            <li><a href="index.php#contact">Contact</a></li>
            <?php if(isset($_SESSION['user_id'])): ?>
                <li><a href="dashboard.php">My Bookings</a></li>
                <li><a href="logout.php" class="btn-nav">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php" class="btn-nav">Login</a></li>
                <li><a href="register.php" class="btn-nav btn-register">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- PAGE HEADER -->
    <section class="page-header">
        <h1>Our Rooms</h1>
        <p>Choose from our selection of luxury rooms and suites</p>
    </section>

    <!-- SEARCH AND FILTER -->
    <section class="filter-section">
        <form method="GET" action="" class="filter-form">
            <div class="filter-group">
                <label><i class="fas fa-bed"></i> Room Type</label>
                <select name="type">
                    <option value="">All Types</option>
                    <option value="Standard Room" <?php echo isset($_GET['type']) && $_GET['type'] == 'Standard Room' ? 'selected' : ''; ?>>Standard Room</option>
                    <option value="Deluxe Room" <?php echo isset($_GET['type']) && $_GET['type'] == 'Deluxe Room' ? 'selected' : ''; ?>>Deluxe Room</option>
                    <option value="Suite" <?php echo isset($_GET['type']) && $_GET['type'] == 'Suite' ? 'selected' : ''; ?>>Suite</option>
                    <option value="Family Room" <?php echo isset($_GET['type']) && $_GET['type'] == 'Family Room' ? 'selected' : ''; ?>>Family Room</option>
                </select>
            </div>
            <div class="filter-group">
                <label><i class="fas fa-money-bill"></i> Min Price (KSh)</label>
                <input type="number" name="min_price" placeholder="e.g. 3000" value="<?php echo isset($_GET['min_price']) ? $_GET['min_price'] : ''; ?>">
            </div>
            <div class="filter-group">
                <label><i class="fas fa-money-bill"></i> Max Price (KSh)</label>
                <input type="number" name="max_price" placeholder="e.g. 10000" value="<?php echo isset($_GET['max_price']) ? $_GET['max_price'] : ''; ?>">
            </div>
            <div class="filter-group">
                <label><i class="fas fa-sort"></i> Sort By</label>
                <select name="sort">
                    <option value="">Default</option>
                    <option value="price_asc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'price_asc' ? 'selected' : ''; ?>>Price: Low to High</option>
                    <option value="price_desc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
                </select>
            </div>
            <div class="filter-buttons">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-search"></i> Search
                </button>
                <a href="rooms.php" class="btn-clear">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
    </section>

    <!-- RESULTS COUNT -->
    <?php
    // Build query based on filters
    $where = "WHERE status = 'available'";

    if(isset($_GET['type']) && !empty($_GET['type'])){
        $type = mysqli_real_escape_string($conn, $_GET['type']);
        $where .= " AND room_type = '$type'";
    }

    if(isset($_GET['min_price']) && !empty($_GET['min_price'])){
        $min = (int)$_GET['min_price'];
        $where .= " AND price >= $min";
    }

    if(isset($_GET['max_price']) && !empty($_GET['max_price'])){
        $max = (int)$_GET['max_price'];
        $where .= " AND price <= $max";
    }

    $order = "ORDER BY id";
    if(isset($_GET['sort'])){
        if($_GET['sort'] == 'price_asc') $order = "ORDER BY price ASC";
        if($_GET['sort'] == 'price_desc') $order = "ORDER BY price DESC";
    }

    $result = mysqli_query($conn, "SELECT * FROM rooms $where $order");
    $count = mysqli_num_rows($result);
    ?>

    <div class="results-count">
        <p><i class="fas fa-door-open"></i> Showing <strong><?php echo $count; ?></strong> room(s) available</p>
    </div>

    <!-- ROOMS GRID -->
    <section class="rooms-section">
        <?php if($count > 0): ?>
            <?php while($room = mysqli_fetch_assoc($result)): ?>
            <div class="room-card">
                <div class="room-image">
                    <?php if($room['image'] && file_exists($room['image'])): ?>
                        <img src="<?php echo $room['image']; ?>" alt="<?php echo $room['room_type']; ?>" class="room-photo">
                    <?php else: ?>
                        <i class="fas fa-bed"></i>
                    <?php endif; ?>
                    <span class="room-badge"><?php echo $room['room_type']; ?></span>
                </div>
                <div class="room-details">
                    <h3>Room <?php echo $room['room_number']; ?> — <?php echo $room['room_type']; ?></h3>
                    <p><?php echo $room['description']; ?></p>
                    <div class="room-amenities">
                        <span><i class="fas fa-wifi"></i> WiFi</span>
                        <span><i class="fas fa-tv"></i> TV</span>
                        <span><i class="fas fa-snowflake"></i> AC</span>
                        <span><i class="fas fa-coffee"></i> Breakfast</span>
                    </div>
                    <div class="room-footer">
                        <div class="room-price">
                            <span class="price">KSh <?php echo number_format($room['price'], 0); ?></span>
                            <span class="per-night">/ night</span>
                        </div>
                        <a href="<?php echo isset($_SESSION['user_id']) ? 'booking.php?room_id='.$room['id'] : 'login.php'; ?>" class="btn-book">
                            <?php echo isset($_SESSION['user_id']) ? 'Book Now' : 'Login to Book'; ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-rooms">
                <i class="fas fa-search"></i>
                <p>No rooms found matching your search.</p>
                <a href="rooms.php" class="btn-about">View All Rooms</a>
            </div>
        <?php endif; ?>
    </section>

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2026 Kaka Grand Hotel. Built by Steve Macharia.</p>
    </footer>

</body>
</html>