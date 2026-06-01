<?php 
require_once '../config.php';

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

// Handle image upload
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['room_id'])){
    $room_id = $_POST['room_id'];
    
    if(isset($_FILES['room_image']) && $_FILES['room_image']['error'] == 0){
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $filename = $_FILES['room_image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if(in_array($ext, $allowed)){
            $new_name = 'room' . $room_id . '_' . time() . '.' . $ext;
            $upload_path = '../images/rooms/' . $new_name;
            
            if(move_uploaded_file($_FILES['room_image']['tmp_name'], $upload_path)){
    $image_path = 'images/rooms/' . $new_name;
    mysqli_query($conn, "UPDATE rooms SET image='$image_path' WHERE id='$room_id'");
    $success = "Photo uploaded! Path: " . $image_path;
} else {
    $error = "Upload failed! Check if images/rooms/ folder exists. Path tried: " . $upload_path;
}
        } else {
            $error = "Only JPG, PNG and WEBP files allowed.";
        }
    }
}

$rooms = mysqli_query($conn, "SELECT * FROM rooms ORDER BY id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rooms - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="admin-body">

    <div class="admin-sidebar">
        <div class="sidebar-logo">
            <img src="../images/logo.svg" alt="Kaka Grand Hotel" height="40">
        </div>
        <nav class="sidebar-nav">
            <a href="index.php">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="bookings.php">
                <i class="fas fa-calendar-check"></i> Bookings
            </a>
            <a href="rooms.php" class="active">
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

    <div class="admin-content">
        <div class="admin-topbar">
            <h1>Manage Rooms</h1>
            <div class="admin-user">
                <i class="fas fa-user-circle"></i>
                <span><?php echo $_SESSION['admin_name']; ?></span>
            </div>
        </div>

        <?php if(isset($success)): ?>
            <div class="alert alert-success" style="margin: 20px 30px;">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if(isset($error)): ?>
            <div class="alert alert-error" style="margin: 20px 30px;">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="rooms-manage-grid">
            <?php while($room = mysqli_fetch_assoc($rooms)): ?>
            <div class="room-manage-card">

                <!-- Room Photo -->
                <div class="room-manage-image">
                    <?php if($room['image'] && file_exists('../' . $room['image'])): ?>
                        <img src="../<?php echo $room['image']; ?>" alt="Room <?php echo $room['room_number']; ?>">
                    <?php else: ?>
                        <div class="no-photo">
                            <i class="fas fa-camera"></i>
                            <p>No photo yet</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Room Info -->
                <div class="room-manage-info">
                    <h3>Room <?php echo $room['room_number']; ?></h3>
                    <p class="room-type-badge"><?php echo $room['room_type']; ?></p>
                    <p class="room-price">KSh <?php echo number_format($room['price'], 0); ?> / night</p>
                    <span class="status-badge <?php echo $room['status']; ?>">
                        <?php echo ucfirst($room['status']); ?>
                    </span>
                </div>

                <!-- Upload Photo Form -->
                <div class="room-upload-form">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                        <label class="upload-label">
                            <i class="fas fa-upload"></i> Upload Photo
                            <input type="file" name="room_image" accept="image/*" onchange="this.form.submit()">
                        </label>
                    </form>
                </div>

            </div>
            <?php endwhile; ?>
        </div>

    </div>

</body>
</html>