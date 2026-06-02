<?php
include "../../config/koneksi.php";

$id = $_GET['id'];

$query = "SELECT main_image FROM rooms WHERE id_room = $id";
$result = mysqli_query($conn, $query);
$room = mysqli_fetch_assoc($result);

if (!empty($room['main_image']) && file_exists("../../uploads/rooms/" . $room['main_image'])) {
    unlink("../../uploads/rooms/" . $room['main_image']);
}

$delete = "DELETE FROM rooms WHERE id_room = $id";
mysqli_query($conn, $delete);

header("Location: index.php");
exit;
?>