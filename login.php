<?php
require_once '../config.php';

if(isset($_SESSION['admin_id'])){
    header("Location: index.php");
    exit();
}

$error = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    if(empty($email) || empty($password)){
        $error = "Please fill in all fields.";
    } else {
        $sql = "SELECT * FROM admins WHERE email='$email'";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) == 1){
            $admin = mysqli_fetch_assoc($result);
            if(password_verify($password, $admin['password'])){
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['name'];
                $_SESSION['admin_email'] = $admin['email'];
                header("Location: index.php");
                exit();
            } else {
                $error = "Incorrect password. Please try again.";
            }
        } else {
            $error = "No admin account found with that email.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Kaka Grand Hotel</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <section class="auth-section" style="background:#1a1a2e; min-height:100vh;">
        <div class="auth-card">
            <div class="auth-header">
                <img src="../images/logo.svg" alt="Kaka Grand Hotel" height="60" style="margin-bottom:15px;">
                <h2>Admin Login</h2>
                <p>Sign in to access the admin panel</p>
            </div>

            <?php if($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Admin Email</label>
                    <input type="email" name="email" placeholder="Enter admin email" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Password</label>
                    <input type="password" name="password" placeholder="Enter admin password" required>
                </div>
                <button type="submit" class="btn-auth">
                    <i class="fas fa-sign-in-alt"></i> Login to Admin Panel
                </button>
            </form>

            <p class="auth-switch">
                <a href="../index.php" style="color:#f0a500;">
                    <i class="fas fa-arrow-left"></i> Back to Website
                </a>
            </p>
        </div>
    </section>
</body>
</html>