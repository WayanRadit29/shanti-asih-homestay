<?php
include "../../config/koneksi.php";

$nama_room = $_POST['nama_room'];
$harga = $_POST['harga'];
$kapasitas = $_POST['kapasitas'];
$deskripsi = $_POST['deskripsi'];
$status = $_POST['status'];

$main_image = null;

if (!empty($_FILES['main_image']['name'])) {
    $file_name = $_FILES['main_image']['name'];
    $file_tmp = $_FILES['main_image']['tmp_name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($file_ext, $allowed_ext)) {
        die("Format file tidak diizinkan.");
    }

    $main_image = time() . "_" . uniqid() . "." . $file_ext;
    $upload_path = "../../uploads/rooms/" . $main_image;

    move_uploaded_file($file_tmp, $upload_path);
}

$query = "INSERT INTO rooms (nama_room, harga, kapasitas, deskripsi, status, main_image)
          VALUES ('$nama_room', '$harga', '$kapasitas', '$deskripsi', '$status', '$main_image')";

mysqli_query($conn, $query);

header("Location: index.php");
exit;
?>