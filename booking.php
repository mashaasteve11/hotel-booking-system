<?php 
require_once '../config.php';

iif(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

// Confirm booking
if(isset($_GET['confirm'])){
    $id = $_GET['confirm'];
    mysqli_query($conn, "UPDATE bookings SET status='confirmed' WHERE id='$id'");
    header("Location: bookings.php?msg=confirmed");
    exit();
}

// Cancel booking
if(isset($_GET['cancel'])){
    $id = $_GET['cancel'];
    mysqli_query($conn, "UPDATE bookings SET status='cancelled' WHERE id='$id'");
    header("Location: bookings.php?msg=cancelled");
    exit();
}

// Get all bookings
$bookings = mysqli_query($conn, "
    SELECT b.*, u.name as user_name, u.email, u.phone, r.room_number, r.room_type 
    FROM bookings b 
    JOIN users u ON b.user_id = u.id 
    JOIN rooms r ON b.room_id = r.id 
    ORDER BY b.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="admin-body">

    <!-- SIDEBAR -->
    <div class="admin-sidebar">
        <div class="logo">
    <img src="../images/logo.svg" alt="Kaka Grand Hotel" height="45">
</div>
        <nav class="sidebar-nav">
            <a href="index.php">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="bookings.php" class="active">
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

    <!-- CONTENT -->
    <div class="admin-content">
        <div class="admin-topbar">
            <h1>Manage Bookings</h1>
            <div class="admin-user">
                <i class="fas fa-user-circle"></i>
                <span><?php echo $_SESSION['admin_name']; ?></span>
            </div>
        </div>

        <?php if(isset($_GET['msg'])): ?>
            <div class="alert <?php echo $_GET['msg'] == 'confirmed' ? 'alert-success' : 'alert-error'; ?>">
                <i class="fas fa-check-circle"></i>
                Booking <?php echo $_GET['msg']; ?> successfully!
            </div>
        <?php endif; ?>

        <div class="admin-card">
            <div class="admin-card-header">
                <h2><i class="fas fa-calendar-check"></i> All Bookings</h2>
            </div>
            <div class="table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Guest</th>
                            <th>Phone</th>
                            <th>Room</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Guests</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; while($b = mysqli_fetch_assoc($bookings)): ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td>
                                <strong><?php echo $b['user_name']; ?></strong><br>
                                <small><?php echo $b['email']; ?></small>
                            </td>
                            <td><?php echo $b['phone'] ?? 'N/A'; ?></td>
                            <td>Room <?php echo $b['room_number']; ?><br>
                                <small><?php echo $b['room_type']; ?></small>
                            </td>
                            <td><?php echo $b['check_in']; ?></td>
                            <td><?php echo $b['check_out']; ?></td>
                            <td><?php echo $b['guests']; ?></td>
                            <td>KSh <?php echo number_format($b['total_price'], 0); ?></td>
                            <td>
                                <span class="status-badge <?php echo $b['status']; ?>">
                                    <?php echo ucfirst($b['status']); ?>
                                </span>
                            </td>
                            <td class="action-btns">
                                <?php if($b['status'] == 'pending'): ?>
                                    <a href="?confirm=<?php echo $b['id']; ?>" class="btn-confirm">
                                        <i class="fas fa-check"></i> Confirm
                                    </a>
                                    <a href="?cancel=<?php echo $b['id']; ?>" class="btn-cancel"
                                       onclick="return confirm('Cancel this booking?')">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
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