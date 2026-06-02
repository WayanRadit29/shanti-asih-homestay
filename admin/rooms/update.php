<?php
include "../../config/koneksi.php";

$id_room = $_POST['id_room'];
$nama_room = $_POST['nama_room'];
$harga = $_POST['harga'];
$kapasitas = $_POST['kapasitas'];
$deskripsi = $_POST['deskripsi'];
$status = $_POST['status'];
$old_image = $_POST['old_image'];

$main_image = $old_image;

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

    if (!empty($old_image) && file_exists("../../uploads/rooms/" . $old_image)) {
        unlink("../../uploads/rooms/" . $old_image);
    }
}

$query = "UPDATE rooms SET
            nama_room = '$nama_room',
            harga = '$harga',
            kapasitas = '$kapasitas',
            deskripsi = '$deskripsi',
            status = '$status',
            main_image = '$main_image'
          WHERE id_room = $id_room";

mysqli_query($conn, $query);

header("Location: index.php");
exit;
?>