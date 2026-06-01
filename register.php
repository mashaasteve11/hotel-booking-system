<?php 
require_once 'config.php';

// If already logged in redirect to dashboard
if(isset($_SESSION['user_id'])){
    header("Location: dashboard.php");
    exit();
}

$error = "";
$success = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate
    if(empty($name) || empty($email) || empty($password)){
        $error = "Please fill in all required fields.";
    } elseif($password !== $confirm_password){
        $error = "Passwords do not match.";
    } elseif(strlen($password) < 6){
        $error = "Password must be at least 6 characters.";
    } else {
        // Check if email exists
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
        if(mysqli_num_rows($check) > 0){
            $error = "Email already registered. Please login.";
        } else {
            // Hash password and save
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (name, email, phone, password) VALUES ('$name', '$email', '$phone', '$hashed')";
            if(mysqli_query($conn, $sql)){
                $success = "Account created successfully! You can now login.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Kaka Grand Hotel</title>
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
            <li><a href="login.php" class="btn-nav">Login</a></li>
            <li><a href="register.php" class="btn-nav btn-register">Register</a></li>
        </ul>
    </nav>

    <!-- REGISTER FORM -->
    <section class="auth-section">
        <div class="auth-card">
            <div class="auth-header">
                <i class="fas fa-user-plus"></i>
                <h2>Create Account</h2>
                <p>Join us and start booking your stay</p>
            </div>

            <?php if($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                    <br><a href="login.php">Click here to Login</a>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Full Name *</label>
                    <input type="text" name="name" placeholder="Enter your full name" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email Address *</label>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Phone Number</label>
                    <input type="text" name="phone" placeholder="e.g. +254 710 304 802">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Password *</label>
                    <input type="password" name="password" placeholder="Minimum 6 characters" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Confirm Password *</label>
                    <input type="password" name="confirm_password" placeholder="Repeat your password" required>
                </div>
                <button type="submit" class="btn-auth">Create Account</button>
            </form>

            <p class="auth-switch">
                Already have an account? <a href="login.php">Login here</a>
            </p>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2026 Serena Grand Hotel. Built by Steve Macharia.</p>
    </footer>

</body>
</html>