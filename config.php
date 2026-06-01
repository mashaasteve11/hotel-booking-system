<?php
define('DB_HOST', 'sql105.infinityfree.com');
define('DB_USER', 'if0_42060566');
define('DB_PASS', 'Steve2026kaka');
define('DB_NAME', 'if0_42060566_hotel_db');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>