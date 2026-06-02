<?php
include "../../config/koneksi.php";

$id = $_GET['id'];
$query = "SELECT * FROM rooms WHERE id_room = $id";
$result = mysqli_query($conn, $query);
$room = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kamar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container py-5">
    <h2 class="fw-bold mb-4">Edit Kamar</h2>

    <form action="update.php" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm border-0">
        <input type="hidden" name="id_room" value="<?= $room['id_room']; ?>">
        <input type="hidden" name="old_image" value="<?= $room['main_image']; ?>">

        <div class="mb-3">
            <label class="form-label">Nama Kamar</label>
            <input type="text" name="nama_room" class="form-control" value="<?= htmlspecialchars($room['nama_room']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Harga</label>
            <input type="number" name="harga" class="form-control" value="<?= $room['harga']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Kapasitas</label>
            <input type="number" name="kapasitas" class="form-control" value="<?= $room['kapasitas']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4"><?= htmlspecialchars($room['deskripsi']); ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="available" <?= $room['status'] == 'available' ? 'selected' : ''; ?>>Available</option>
                <option value="unavailable" <?= $room['status'] == 'unavailable' ? 'selected' : ''; ?>>Unavailable</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Gambar Lama</label><br>
            <?php if (!empty($room['main_image'])) { ?>
                <img src="../../uploads/rooms/<?= $room['main_image']; ?>" width="120" class="rounded mb-2">
            <?php } else { ?>
                <p class="text-muted">Tidak ada gambar</p>
            <?php } ?>

            <input type="file" name="main_image" class="form-control mt-2" accept="image/*">
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-warning">Update</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>
</body>
</html>