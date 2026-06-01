<?php 
require_once 'config.php';

// If not logged in redirect to login
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Get room id from URL
if(!isset($_GET['room_id'])){
    header("Location: rooms.php");
    exit();
}

$room_id = $_GET['room_id'];

// Get room details
$room = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM rooms WHERE id='$room_id' AND status='available'"));

if(!$room){
    header("Location: rooms.php");
    exit();
}

$error = "";
$success = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $guests = $_POST['guests'];
    $user_id = $_SESSION['user_id'];

    // Validate dates
    if(empty($check_in) || empty($check_out)){
        $error = "Please select check-in and check-out dates.";
    } elseif($check_in >= $check_out){
        $error = "Check-out date must be after check-in date.";
    } elseif($check_in < date('Y-m-d')){
        $error = "Check-in date cannot be in the past.";
    } else {
        // Calculate number of nights and total price
        $nights = (strtotime($check_out) - strtotime($check_in)) / (60 * 60 * 24);
        $total_price = $nights * $room['price'];

        // Save booking
        $sql = "INSERT INTO bookings (user_id, room_id, check_in, check_out, guests, total_price, status) 
                VALUES ('$user_id', '$room_id', '$check_in', '$check_out', '$guests', '$total_price', 'pending')";

        if(mysqli_query($conn, $sql)){
            $booking_id = mysqli_insert_id($conn);
            $success = "Booking successful!";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Room - Kaka Grand Hotel</title>
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
        <h1>Book Your Room</h1>
        <p>Complete your reservation for Room <?php echo $room['room_number']; ?></p>
    </section>

    <section class="booking-section">

        <?php if($success): ?>
            <!-- SUCCESS MESSAGE -->
            <div class="booking-success">
                <i class="fas fa-check-circle"></i>
                <h2>Booking Confirmed! 🎉</h2>
                <p>Your reservation has been received successfully.</p>
                <div class="success-details">
                    <div class="success-item">
                        <span>Room</span>
                        <strong>Room <?php echo $room['room_number']; ?> — <?php echo $room['room_type']; ?></strong>
                    </div>
                    <div class="success-item">
                        <span>Check In</span>
                        <strong><?php echo date('D, d M Y', strtotime($_POST['check_in'])); ?></strong>
                    </div>
                    <div class="success-item">
                        <span>Check Out</span>
                        <strong><?php echo date('D, d M Y', strtotime($_POST['check_out'])); ?></strong>
                    </div>
                    <div class="success-item">
                        <span>Guests</span>
                        <strong><?php echo $_POST['guests']; ?> Guest(s)</strong>
                    </div>
                    <div class="success-item">
                        <span>Total Price</span>
                        <strong class="total-price">KSh <?php 
                            $nights = (strtotime($_POST['check_out']) - strtotime($_POST['check_in'])) / (60 * 60 * 24);
                            echo number_format($nights * $room['price'], 0); 
                        ?></strong>
                    </div>
                    <div class="success-item">
                        <span>Status</span>
                        <strong><span class="status-badge pending">Pending Confirmation</span></strong>
                    </div>
                </div>
                <div class="success-actions">
                    <a href="dashboard.php" class="btn-auth">View My Bookings</a>
                    <a href="rooms.php" class="btn-about">Book Another Room</a>
                </div>
            </div>

        <?php else: ?>
            <div class="booking-grid">

                <!-- ROOM SUMMARY -->
                <div class="room-summary">
                    <h2>Room Summary</h2>
                    <div class="summary-image">
                        <i class="fas fa-bed"></i>
                        <span class="room-badge"><?php echo $room['room_type']; ?></span>
                    </div>
                    <div class="summary-details">
                        <h3>Room <?php echo $room['room_number']; ?> — <?php echo $room['room_type']; ?></h3>
                        <p><?php echo $room['description']; ?></p>
                        <div class="room-amenities">
                            <span><i class="fas fa-wifi"></i> WiFi</span>
                            <span><i class="fas fa-tv"></i> TV</span>
                            <span><i class="fas fa-snowflake"></i> AC</span>
                            <span><i class="fas fa-coffee"></i> Breakfast</span>
                        </div>
                        <div class="summary-price">
                            <span class="price">KSh <?php echo number_format($room['price'], 0); ?></span>
                            <span class="per-night">/ night</span>
                        </div>
                    </div>
                </div>

                <!-- BOOKING FORM -->
                <div class="booking-form-card">
                    <h2>Booking Details</h2>

                    <?php if($error): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" id="booking-form">
                        <div class="form-group">
                            <label><i class="fas fa-user"></i> Full Name</label>
                            <input type="text" value="<?php echo $_SESSION['user_name']; ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-envelope"></i> Email</label>
                            <input type="text" value="<?php echo $_SESSION['user_email']; ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-calendar"></i> Check-In Date</label>
                            <input type="date" name="check_in" id="check_in" min="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-calendar"></i> Check-Out Date</label>
                            <input type="date" name="check_out" id="check_out" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-users"></i> Number of Guests</label>
                            <select name="guests" required>
                                <option value="1">1 Guest</option>
                                <option value="2">2 Guests</option>
                                <option value="3">3 Guests</option>
                                <option value="4">4 Guests</option>
                            </select>
                        </div>

                        <!-- PRICE CALCULATOR -->
                        <div class="price-calculator">
                            <div class="calc-row">
                                <span>Room Price</span>
                                <span>KSh <?php echo number_format($room['price'], 0); ?> / night</span>
                            </div>
                            <div class="calc-row">
                                <span>Number of Nights</span>
                                <span id="nights-count">0 nights</span>
                            </div>
                            <div class="calc-row total">
                                <span>Total Price</span>
                                <span id="total-price">KSh 0</span>
                            </div>
                        </div>

                        <button type="submit" class="btn-auth">
                            <i class="fas fa-check"></i> Confirm Booking
                        </button>
                    </form>
                </div>

            </div>
        <?php endif; ?>

    </section>

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2026 Kaka Grand Hotel. Built by Steve Macharia.</p>
    </footer>

    <!-- JAVASCRIPT for price calculator -->
    <script>
        const checkIn = document.getElementById('check_in');
        const checkOut = document.getElementById('check_out');
        const nightsCount = document.getElementById('nights-count');
        const totalPrice = document.getElementById('total-price');
        const roomPrice = <?php echo $room['price']; ?>;

        function calculatePrice(){
            if(checkIn.value && checkOut.value){
                const start = new Date(checkIn.value);
                const end = new Date(checkOut.value);
                const nights = (end - start) / (1000 * 60 * 60 * 24);

                if(nights > 0){
                    const total = nights * roomPrice;
                    nightsCount.textContent = nights + ' night(s)';
                    totalPrice.textContent = 'KSh ' + total.toLocaleString();
                    totalPrice.style.color = '#f0a500';
                } else {
                    nightsCount.textContent = '0 nights';
                    totalPrice.textContent = 'KSh 0';
                }
            }
        }

        checkIn.addEventListener('change', calculatePrice);
        checkOut.addEventListener('change', calculatePrice);
    </script>

</body>
</html>