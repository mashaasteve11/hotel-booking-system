<?php 
require_once '../config.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

$users = mysqli_query($conn, "
    SELECT u.*, COUNT(b.id) as total_bookings 
    FROM users u 
    LEFT JOIN bookings b ON u.id = b.user_id 
    GROUP BY u.id 
    ORDER BY u.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="admin-body">

    <div class="admin-sidebar">
        <div class="logo">
    <img src="../images/logo.svg" alt="Kaka Grand Hotel" height="45">
</div>
        <nav class="sidebar-nav">
            <a href="index.php">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="bookings.php">
                <i class="fas fa-calendar-check"></i> Bookings
            </a>
            <a href="rooms.php">
                <i class="fas fa-bed"></i> Rooms
            </a>
            <a href="users.php" class="active">
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

    <div class="admin-content">
        <div class="admin-topbar">
            <h1>Manage Users</h1>
            <div class="admin-user">
                <i class="fas fa-user-circle"></i>
                <<span><?php echo $_SESSION['admin_name']; ?></span>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h2><i class="fas fa-users"></i> All Users</h2>
            </div>
            <div class="table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Total Bookings</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; while($u = mysqli_fetch_assoc($users)): ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><strong><?php echo $u['name']; ?></strong></td>
                            <td><?php echo $u['email']; ?></td>
                            <td><?php echo $u['phone'] ?? 'N/A'; ?></td>
                            <td>
                                <span class="status-badge confirmed">
                                    <?php echo $u['total_bookings']; ?> bookings
                                </span>
                            </td>
                            <td><?php echo date('d M Y', strtotime($u['created_at'])); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>