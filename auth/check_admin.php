<?php
// Include check_login.php untuk memastikan user sudah login
include 'check_login.php';

// Cek apakah $_SESSION['role'] == 'admin'
if ($_SESSION['role'] !== 'admin') {
    // Jika bukan admin, redirect ke login.php
    header("Location: /shanti-asih-homestay/login.php");
    
    // Exit setelah redirect
    exit();
}
?>
