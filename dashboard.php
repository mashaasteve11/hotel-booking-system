<?php 
require_once 'config.php';

// If not logged in redirect to login
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Get user bookings
$user_id = $_SESSION['user_id'];
$bookings = mysqli_query($conn, "
    SELECT b.*, r.room_number, r.room_type, r.price 
    FROM bookings b 
    JOIN rooms r ON b.room_id = r.id 
    WHERE b.user_id = '$user_id' 
    ORDER BY b.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard - Kaka Grand Hotel</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <!-- NAVIGATION -->
    <nav class="navbar">
        <div class="logo">
    <img src="images/logo.svg" alt="Kaka Grand Hotel" height="45">
</div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="rooms.php">Rooms</a></li>
            <li><a href="dashboard.php">My Bookings</a></li>
            <li><a href="logout.php" class="btn-nav">Logout</a></li>
        </ul>
    </nav>

    <!-- PAGE HEADER -->
    <section class="page-header">
        <h1>Welcome, <?php echo $_SESSION['user_name']; ?>! 👋</h1>
        <p>Manage your bookings and reservations</p>
    </section>

    <!-- DASHBOARD CONTENT -->
    <section class="dashboard-section">

        <!-- STATS CARDS -->
        <div class="stats-grid">
            <?php
            $total = mysqli_num_rows($bookings);
            $confirmed = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM bookings WHERE user_id='$user_id' AND status='confirmed'"));
            $pending = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM bookings WHERE user_id='$user_id' AND status='pending'"));
            ?>
            <div class="stat-card">
                <i class="fas fa-calendar-check"></i>
                <h3><?php echo $total; ?></h3>
                <p>Total Bookings</p>
            </div>
            <div class="stat-card confirmed">
                <i class="fas fa-check-circle"></i>
                <h3><?php echo $confirmed; ?></h3>
                <p>Confirmed</p>
            </div>
            <div class="stat-card pending">
                <i class="fas fa-clock"></i>
                <h3><?php echo $pending; ?></h3>
                <p>Pending</p>
            </div>
            <div class="stat-card info">
                <i class="fas fa-user"></i>
                <h3><?php echo $_SESSION['user_name']; ?></h3>
                <p><?php echo $_SESSION['user_email']; ?></p>
            </div>
        </div>

        <!-- BOOKINGS TABLE -->
        <div class="bookings-container">
            <div class="bookings-header">
                <h2><i class="fas fa-list"></i> My Bookings</h2>
                <a href="rooms.php" class="btn-new-booking">
                    <i class="fas fa-plus"></i> New Booking
                </a>
            </div>

            <?php 
            // Reset bookings query
            $bookings = mysqli_query($conn, "
                SELECT b.*, r.room_number, r.room_type, r.price 
                FROM bookings b 
                JOIN rooms r ON b.room_id = r.id 
                WHERE b.user_id = '$user_id' 
                ORDER BY b.created_at DESC
            ");
            
            if(mysqli_num_rows($bookings) > 0): ?>
                <div class="table-wrapper">
                    <table class="bookings-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Room</th>
                                <th>Type</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Guests</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; while($b = mysqli_fetch_assoc($bookings)): ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td>Room <?php echo $b['room_number']; ?></td>
                                <td><?php echo $b['room_type']; ?></td>
                                <td><?php echo $b['check_in']; ?></td>
                                <td><?php echo $b['check_out']; ?></td>
                                <td><?php echo $b['guests']; ?></td>
                                <td>KSh <?php echo number_format($b['total_price'], 0); ?></td>
                                <td>
                                    <span class="status-badge <?php echo $b['status']; ?>">
                                        <?php echo ucfirst($b['status']); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-bookings">
                    <i class="fas fa-bed"></i>
                    <h3>No bookings yet</h3>
                    <p>You have not made any bookings yet.</p>
                    <a href="rooms.php" class="btn-about">Browse Rooms</a>
                </div>
            <?php endif; ?>
        </div>

    </section>

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2026 Kaka Grand Hotel. Built by Steve Macharia.</p>
    </footer>

</body>
</html>