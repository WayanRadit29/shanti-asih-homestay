<?php
// Jalankan session_start()
session_start();

// Hapus seluruh data session
$_SESSION = array();

// Destroy session
session_destroy();

// Redirect ke login.php
header("Location: login.php");

// Exit setelah redirect
exit();
?>
