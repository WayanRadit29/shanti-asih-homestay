<?php
include '../../auth/check_admin.php';
include "../../config/koneksi.php";

$query = "SELECT * FROM rooms ORDER BY id_room DESC";
$result = mysqli_query($conn, $query);

$total_rooms = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Kamar - Shanti Asih Homestay</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-green: #5f7a3a;
            --primary-green-dark: #4f6530;
            --warm-brown: #8b6f47;
            --cream-bg: #f5f1e8;
            --text-dark: #2f2f2f;
        }

        body {
            background-color: var(--cream-bg);
            color: var(--text-dark);
            min-height: 100vh;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--warm-brown) 100%);
            color: white;
            padding: 35px 0;
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-weight: 800;
        }

        .content-card {
            background: white;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 10px 24px rgba(47,47,47,0.08);
        }

        .section-title {
            color: var(--text-dark);
            border-bottom: 3px solid var(--primary-green);
            padding-bottom: 10px;
        }

        .btn-main {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
            color: white;
            border-radius: 999px;
            font-weight: 600;
        }

        .btn-main:hover {
            background-color: var(--primary-green-dark);
            border-color: var(--primary-green-dark);
            color: white;
        }

        .btn-outline-main {
            border: 1px solid var(--primary-green);
            color: var(--primary-green);
            border-radius: 999px;
            font-weight: 600;
        }

        .btn-outline-main:hover {
            background-color: var(--primary-green);
            color: white;
        }

        .table thead th {
            background-color: #e8eadf;
            color: var(--text-dark);
            white-space: nowrap;
        }

        .table tbody td {
            vertical-align: middle;
            white-space: nowrap;
        }

        .room-image {
            width: 90px;
            height: 60px;
            object-fit: cover;
            border-radius: 10px;
        }

        .badge {
            padding: 8px 10px;
            border-radius: 999px;
            font-weight: 600;
        }

        .badge-available {
            background-color: var(--primary-green);
            color: white;
        }

        .badge-unavailable {
            background-color: #6c757d;
            color: white;
        }

        .empty-message {
            background-color: #f8f5ef;
            border-left: 4px solid var(--primary-green);
            border-radius: 12px;
            padding: 18px;
            color: var(--text-dark);
        }

        .action-buttons {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .content-card {
                padding: 18px;
            }

            .page-header {
                padding: 25px 0;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-white shadow-sm">
        <div class="container-fluid">

            <a class="navbar-brand fw-bold" href="../dashboard/index.php">
                Admin - Shanti Asih
            </a>

            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarNav">

                <span class="navbar-toggler-icon"></span>

            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                <ul class="navbar-nav ms-auto align-items-lg-center">

                    <li class="nav-item">
                        <a class="nav-link" href="../dashboard/index.php">
                            Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active" href="../rooms/index.php">
                            Kamar
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../bookings/index.php">
                            Booking
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../users/index.php">
                            User
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../../index.php" target="_blank">
                            Lihat Website
                        </a>
                    </li>

                    <li class="nav-item ms-lg-2">
                        <a href="../../logout.php"
                        class="btn btn-sm btn-main">
                            Logout
                        </a>
                    </li>

                </ul>

            </div>

        </div>
    </nav>
    <section class="page-header">
        <div class="container">
            <h1 class="mb-1">Manajemen Kamar</h1>
            <p class="mb-0 text-white-50">
                Kelola seluruh kamar yang tersedia di Shanti Asih Homestay
            </p>
        </div>
    </section>

    <div class="container pb-5">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">

            <a href="../dashboard/index.php" class="btn btn-outline-main">
                ← Kembali ke Dashboard
            </a>

            <a href="create.php" class="btn btn-main">
                + Tambah Kamar
            </a>

        </div>

        <div class="content-card">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">

                <h5 class="fw-bold section-title mb-0">
                    Daftar Kamar
                </h5>

                <span class="badge bg-light text-dark border">
                    Total: <?= $total_rooms ?> kamar
                </span>

            </div>

            <?php if ($total_rooms > 0): ?>

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Nama Kamar</th>
                                <th>Harga</th>
                                <th>Kapasitas</th>
                                <th>Status</th>
                                <th width="200">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php
                        $no = 1;

                        while ($room = mysqli_fetch_assoc($result)):
                        ?>

                            <tr>

                                <td>
                                    <strong><?= $no++; ?></strong>
                                </td>

                                <td>

                                    <?php if (!empty($room['main_image'])): ?>

                                        <img
                                            src="../../uploads/rooms/<?= htmlspecialchars($room['main_image']); ?>"
                                            class="room-image"
                                            alt="<?= htmlspecialchars($room['nama_room']); ?>"
                                        >

                                    <?php else: ?>

                                        <span class="text-muted">
                                            Tidak ada gambar
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td class="fw-semibold">
                                    <?= htmlspecialchars($room['nama_room']); ?>
                                </td>

                                <td>
                                    Rp <?= number_format($room['harga'], 0, ',', '.'); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($room['kapasitas']); ?> orang
                                </td>

                                <td>

                                    <?php if ($room['status'] == 'available'): ?>

                                        <span class="badge badge-available">
                                            Available
                                        </span>

                                    <?php else: ?>

                                        <span class="badge badge-unavailable">
                                            Unavailable
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <div class="action-buttons">

                                        <a
                                            href="edit.php?id=<?= $room['id_room']; ?>"
                                            class="btn btn-warning btn-sm"
                                        >
                                            Edit
                                        </a>

                                        <a
                                            href="delete.php?id=<?= $room['id_room']; ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus kamar ini?')"
                                        >
                                            Hapus
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                        </tbody>

                    </table>

                </div>

            <?php else: ?>

                <div class="empty-message">
                    Belum ada data kamar.
                </div>

            <?php endif; ?>

        </div>

    </div>

</body>
</html>