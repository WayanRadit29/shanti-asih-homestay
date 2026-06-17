<?php
// kode ini untuk proteksi hanya admin yang bisa akses halaman ini
include "../../auth/check_admin.php";

// kode ini untuk menghubungkan database
include "../../config/koneksi.php";

// kode ini untuk mengambil semua data booking dengan JOIN ke tabel users dan rooms
$query = "SELECT 
            b.id_booking,
            b.user_id,
            b.room_id,
            b.check_in,
            b.check_out,
            b.jumlah_tamu,
            b.total_harga,
            b.status,
            b.catatan,
            b.created_at,
            u.nama,
            u.email,
            u.no_hp,
            r.nama_room,
            r.harga
          FROM bookings b
          JOIN users u ON b.user_id = u.id
          JOIN rooms r ON b.room_id = r.id_room
          ORDER BY b.created_at DESC";

$result = mysqli_query($conn, $query);
$bookings = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Booking - Admin Shanti Asih Homestay</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../css/style.css">

    <style>
        body {
            padding-top: 80px;
            background-color: #f8f9fa;
        }

        .admin-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
        }

        .table-container {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        .badge-pending {
            background-color: #ffc107;
            color: #000;
        }

        .badge-confirmed {
            background-color: #28a745;
        }

        .badge-cancelled {
            background-color: #dc3545;
        }

        .badge-completed {
            background-color: #007bff;
        }

        .action-form {
            display: flex;
            gap: 5px;
        }

        .action-form select {
            font-size: 0.85rem;
            padding: 5px;
        }

        .action-form button {
            font-size: 0.85rem;
            padding: 5px 10px;
        }

        table {
            font-size: 0.9rem;
        }

        .text-muted-small {
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-white shadow-sm fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="../../admin/dashboard/index.php">
                Admin - Shanti Asih
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../../admin/dashboard/index.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../../admin/rooms/index.php">Kamar</a></li>
                    <li class="nav-item"><a class="nav-link active" href="../../admin/bookings/index.php">Booking</a></li>
                    <li class="nav-item"><a class="nav-link" href="../../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Admin Header -->
    <section class="admin-header">
        <div class="container-fluid">
            <h1 class="mb-0">Manajemen Booking</h1>
            <p class="mb-0 text-white-50">Kelola semua booking yang masuk</p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container-fluid">
        <!-- Alert Success -->
        <?php if (isset($_GET['message']) && $_GET['message'] === 'success'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Sukses!</strong> Status booking berhasil diperbarui.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Alert Error -->
        <?php if (isset($_GET['message']) && $_GET['message'] === 'error'): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> <?php echo htmlspecialchars($_GET['text'] ?? "Terjadi kesalahan."); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="row mb-3">
            <div class="col-12">
                <a href="../../admin/dashboard/index.php" class="btn btn-secondary">
                    &larr; Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Tabel Booking -->
        <div class="table-container">
            <h5 class="fw-bold mb-3">Daftar Booking (Total: <?php echo count($bookings); ?>)</h5>

            <?php
            // kode ini untuk cek apakah ada data booking
            if (count($bookings) > 0) {
            ?>

            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID Booking</th>
                        <th>Nama User</th>
                        <th>Email</th>
                        <th>No HP</th>
                        <th>Nama Kamar</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Jumlah Tamu</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // kode ini untuk loop menampilkan setiap booking
                    foreach ($bookings as $booking) {
                        // kode ini untuk tentukan warna badge berdasarkan status
                        $badge_class = '';
                        switch ($booking['status']) {
                            case 'pending':
                                $badge_class = 'badge-pending';
                                break;
                            case 'confirmed':
                                $badge_class = 'badge-confirmed';
                                break;
                            case 'cancelled':
                                $badge_class = 'badge-cancelled';
                                break;
                            case 'completed':
                                $badge_class = 'badge-completed';
                                break;
                        }

                        // kode ini untuk format total harga ke Rupiah
                        $harga_rupiah = "Rp" . number_format($booking['total_harga'], 0, ',', '.');
                    ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($booking['id_booking']); ?></strong></td>
                        <td><?php echo htmlspecialchars($booking['nama']); ?></td>
                        <td class="text-muted-small"><?php echo htmlspecialchars($booking['email']); ?></td>
                        <td class="text-muted-small"><?php echo htmlspecialchars($booking['no_hp']); ?></td>
                        <td><?php echo htmlspecialchars($booking['nama_room']); ?></td>
                        <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($booking['check_in']))); ?></td>
                        <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($booking['check_out']))); ?></td>
                        <td><?php echo htmlspecialchars($booking['jumlah_tamu']); ?> orang</td>
                        <td><strong><?php echo $harga_rupiah; ?></strong></td>
                        <td>
                            <!-- kode ini untuk tampilkan status dengan badge -->
                            <span class="badge <?php echo $badge_class; ?>">
                                <?php echo ucfirst(htmlspecialchars($booking['status'])); ?>
                            </span>
                        </td>
                        <td class="text-muted-small">
                            <?php 
                            echo !empty($booking['catatan']) 
                                ? substr(htmlspecialchars($booking['catatan']), 0, 30) . '...' 
                                : '-';
                            ?>
                        </td>
                        <td class="text-muted-small">
                            <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($booking['created_at']))); ?>
                        </td>
                        <td>
                            <!-- kode ini untuk form update status -->
                            <form method="POST" action="update_status.php" class="action-form">
                                <input type="hidden" name="id_booking" value="<?php echo htmlspecialchars($booking['id_booking']); ?>">
                                <select name="status" class="form-select form-select-sm" required>
                                    <option value="pending" <?php echo $booking['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="confirmed" <?php echo $booking['status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                    <option value="cancelled" <?php echo $booking['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    <option value="completed" <?php echo $booking['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            </form>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>

            <?php
            } else {
                // kode ini untuk tampilkan pesan jika tidak ada booking
            ?>

            <div class="alert alert-info">
                <p class="mb-0">Belum ada booking yang masuk.</p>
            </div>

            <?php
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>