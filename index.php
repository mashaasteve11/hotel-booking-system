<?php 
require_once '../config.php';

// Simple admin check - in real project use proper admin table
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

// Get statistics
$total_bookings = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM bookings"));
$total_users = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users"));
$total_rooms = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM rooms"));
$pending_bookings = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM bookings WHERE status='pending'"));
$confirmed_bookings = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM bookings WHERE status='confirmed'"));

// Get total revenue
$revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_price) as total FROM bookings WHERE status='confirmed'"));
$total_revenue = $revenue['total'] ? $revenue['total'] : 0;

// Get recent bookings
$recent_bookings = mysqli_query($conn, "
    SELECT b.*, u.name as user_name, u.email, r.room_number, r.room_type 
    FROM bookings b 
    JOIN users u ON b.user_id = u.id 
    JOIN rooms r ON b.room_id = r.id 
    ORDER BY b.created_at DESC 
    LIMIT 10
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Kaka Grand Hotel</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="admin-body">

    <!-- ADMIN SIDEBAR -->
    <div class="admin-sidebar">
        <div class="logo">
    <img src="../images/logo.svg" alt="Kaka Grand Hotel">
</div>
        <nav class="sidebar-nav">
            <a href="index.php" class="active">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="bookings.php">
                <i class="fas fa-calendar-check"></i> Bookings
            </a>
            <a href="rooms.php">
                <i class="fas fa-bed"></i> Rooms
            </a>
            <a href="users.php">
                <i class="fas fa-users"></i> Users
            </a>
            <a href="../index.php" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Website
            </a>
            <a href="logout.php" class="logout-link">
    <i class="fas fa-sign-out-alt"></i> Logout
</a>
        </nav>
    </div>

    <!-- ADMIN CONTENT -->
    <div class="admin-content">

        <!-- TOP BAR -->
        <div class="admin-topbar">
            <h1>Dashboard</h1>
            <div class="admin-user">
                <i class="fas fa-user-circle"></i>
                <span><?php echo $_SESSION['admin_name']; ?></span>
            </div>
        </div>

        <!-- STATS CARDS -->
        <div class="admin-stats">
            <div class="admin-stat-card blue">
                <div class="stat-info">
                    <h3><?php echo $total_bookings; ?></h3>
                    <p>Total Bookings</p>
                </div>
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="admin-stat-card green">
                <div class="stat-info">
                    <h3>KSh <?php echo number_format($total_revenue, 0); ?></h3>
                    <p>Total Revenue</p>
                </div>
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="admin-stat-card orange">
                <div class="stat-info">
                    <h3><?php echo $pending_bookings; ?></h3>
                    <p>Pending Bookings</p>
                </div>
                <i class="fas fa-clock"></i>
            </div>
            <div class="admin-stat-card purple">
                <div class="stat-info">
                    <h3><?php echo $total_users; ?></h3>
                    <p>Registered Users</p>
                </div>
                <i class="fas fa-users"></i>
            </div>
            <div class="admin-stat-card teal">
                <div class="stat-info">
                    <h3><?php echo $total_rooms; ?></h3>
                    <p>Total Rooms</p>
                </div>
                <i class="fas fa-bed"></i>
            </div>
            <div class="admin-stat-card red">
                <div class="stat-info">
                    <h3><?php echo $confirmed_bookings; ?></h3>
                    <p>Confirmed Bookings</p>
                </div>
                <i class="fas fa-check-circle"></i>
            </div>
        </div>

        <!-- RECENT BOOKINGS -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h2><i class="fas fa-list"></i> Recent Bookings</h2>
                <a href="bookings.php" class="btn-admin">View All</a>
            </div>
            <div class="table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Guest</th>
                            <th>Room</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; while($b = mysqli_fetch_assoc($recent_bookings)): ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td>
                                <strong><?php echo $b['user_name']; ?></strong><br>
                                <small><?php echo $b['email']; ?></small>
                            </td>
                            <td>Room <?php echo $b['room_number']; ?><br>
                                <small><?php echo $b['room_type']; ?></small>
                            </td>
                            <td><?php echo $b['check_in']; ?></td>
                            <td><?php echo $b['check_out']; ?></td>
                            <td>KSh <?php echo number_format($b['total_price'], 0); ?></td>
                            <td>
                                <span class="status-badge <?php echo $b['status']; ?>">
                                    <?php echo ucfirst($b['status']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="bookings.php?confirm=<?php echo $b['id']; ?>" class="btn-confirm">Confirm</a>
                                <a href="bookings.php?cancel=<?php echo $b['id']; ?>" class="btn-cancel">Cancel</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>
</html>