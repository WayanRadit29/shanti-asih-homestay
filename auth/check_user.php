<?php
// Include check_login.php untuk memastikan user sudah login
include 'check_login.php';

// Cek apakah $_SESSION['role'] == 'user'
if ($_SESSION['role'] !== 'user') {
    // Jika bukan user, redirect ke login.php
    header("Location: /shanti-asih-homestay/login.php");
    
    // Exit setelah redirect
    exit();
}
?>
