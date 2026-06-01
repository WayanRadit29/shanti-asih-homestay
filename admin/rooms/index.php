<?php
include "../../config/koneksi.php";

$query = "SELECT * FROM rooms ORDER BY id_room DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Kamar - Shanti Asih Homestay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">Manajemen Kamar</h2>
            <p class="text-muted mb-0">Kelola data kamar Shanti Asih Homestay</p>
        </div>

        <a href="create.php" class="btn btn-primary">
            + Tambah Kamar
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Nama Kamar</th>
                        <th>Harga</th>
                        <th>Kapasitas</th>
                        <th>Status</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $no = 1;

                    if (mysqli_num_rows($result) > 0) {
                        while ($room = mysqli_fetch_assoc($result)) {
                    ?>
                            <tr>
                                <td><?= $no++; ?></td>

                                <td>
                                    <?php if (!empty($room['main_image'])) { ?>
                                        <img src="../../uploads/rooms/<?= $room['main_image']; ?>" width="90" height="60" style="object-fit: cover;" class="rounded">
                                    <?php } else { ?>
                                        <span class="text-muted">Tidak ada gambar</span>
                                    <?php } ?>
                                </td>

                                <td><?= htmlspecialchars($room['nama_room']); ?></td>

                                <td>
                                    Rp <?= number_format($room['harga'], 0, ',', '.'); ?>
                                </td>

                                <td><?= $room['kapasitas']; ?> orang</td>

                                <td>
                                    <?php if ($room['status'] == 'available') { ?>
                                        <span class="badge bg-success">Available</span>
                                    <?php } else { ?>
                                        <span class="badge bg-secondary">Unavailable</span>
                                    <?php } ?>
                                </td>

                                <td>
                                    <a href="edit.php?id=<?= $room['id_room']; ?>" class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <a href="delete.php?id=<?= $room['id_room']; ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Yakin ingin menghapus kamar ini?')">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                    ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Belum ada data kamar.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>