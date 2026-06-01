<?php 
require_once 'config.php';

// If already logged in redirect to dashboard
if(isset($_SESSION['user_id'])){
    header("Location: dashboard.php");
    exit();
}

$error = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    if(empty($email) || empty($password)){
        $error = "Please fill in all fields.";
    } else {
        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) == 1){
            $user = mysqli_fetch_assoc($result);
            if(password_verify($password, $user['password'])){
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Incorrect password. Please try again.";
            }
        } else {
            $error = "No account found with that email.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kaka Grand Hotel</title>
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

    <!-- LOGIN FORM -->
    <section class="auth-section">
        <div class="auth-card">
            <div class="auth-header">
                <i class="fas fa-sign-in-alt"></i>
                <h2>Welcome Back</h2>
                <p>Login to manage your bookings</p>
            </div>

            <?php if($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Password</label>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn-auth">Login</button>
            </form>

            <p class="auth-switch">
                Don't have an account? <a href="register.php">Register here</a>
            </p>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2026 Kaka Grand Hotel. Built by Steve Macharia.</p>
    </footer>

</body>
</html>