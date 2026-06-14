<?php
// Jalankan session_start()
session_start();

// Cek apakah $_SESSION['user_id'] ada
if (!isset($_SESSION['user_id'])) {
    // Jika tidak ada, redirect ke login.php
    header("Location: /shanti-asih-homestay/login.php");
    
    // Exit setelah redirect
    exit();
}
?>
