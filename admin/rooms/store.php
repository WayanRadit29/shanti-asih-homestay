<?php
include '../../auth/check_admin.php';
include "../../config/koneksi.php";

$nama_room = trim($_POST['nama_room']);
$harga = $_POST['harga'];
$kapasitas = $_POST['kapasitas'];
$deskripsi = trim($_POST['deskripsi']);
$status = $_POST['status'];

$main_image = null;

if (!empty($_FILES['main_image']['name'])) {
    $file_name = $_FILES['main_image']['name'];
    $file_tmp = $_FILES['main_image']['tmp_name'];
    $file_size = $_FILES['main_image']['size'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
    $max_size = 2 * 1024 * 1024;

    if (!in_array($file_ext, $allowed_ext)) {
        die("Format file tidak diizinkan. Gunakan jpg, jpeg, png, atau webp.");
    }

    if ($file_size > $max_size) {
        die("Ukuran file terlalu besar. Maksimal 2MB.");
    }

    if (getimagesize($file_tmp) === false) {
        die("File yang diupload bukan gambar valid.");
    }

    $main_image = time() . "_" . uniqid("room_", true) . "." . $file_ext;
    $upload_path = "../../uploads/rooms/" . $main_image;

    if (!move_uploaded_file($file_tmp, $upload_path)) {
        die("Gagal upload gambar.");
    }
}

$query = "INSERT INTO rooms (nama_room, harga, kapasitas, deskripsi, status, main_image)
          VALUES (?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "sdisss", $nama_room, $harga, $kapasitas, $deskripsi, $status, $main_image);
mysqli_stmt_execute($stmt);

header("Location: index.php");
exit;
?>