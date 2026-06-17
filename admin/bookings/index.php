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
    <title>Management Booking - Admin Shanti Asih Homestay</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style.css">

    <style>
        :root {
            --primary-green: #5f7a3a;
            --primary-green-dark: #4f6530;
            --warm-brown: #8b6f47;
            --cream-bg: #f5f1e8;
            --text-dark: #2f2f2f;
            --muted-text: #6c757d;
        }

        body {
            padding-top: 76px;
            background-color: var(--cream-bg);
            color: var(--text-dark);
        }

        .navbar {
            box-shadow: 0 2px 12px rgba(47, 47, 47, 0.1);
        }

        .navbar-brand {
            color: var(--primary-green) !important;
        }

        .admin-header {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--warm-brown) 100%);
            color: white;
            padding: 34px 0;
            margin-bottom: 30px;
        }

        .admin-header h1 {
            font-weight: 800;
        }

        .table-container {
            background: white;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 10px 24px rgba(47, 47, 47, 0.08);
            overflow-x: auto;
        }

        .section-title {
            color: var(--text-dark);
            padding-bottom: 12px;
            border-bottom: 3px solid var(--primary-green);
        }

        .badge {
            padding: 8px 10px;
            border-radius: 999px;
            text-transform: capitalize;
        }

        .badge-pending {
            background-color: #ffc107;
            color: #000;
        }

        .badge-confirmed {
            background-color: #5f7a3a;
        }

        .badge-cancelled {
            background-color: #dc3545;
        }

        .badge-completed {
            background-color: #0d6efd;
        }

        .action-form {
            display: flex;
            gap: 8px;
            align-items: center;
            min-width: 230px;
        }

        .action-form select {
            font-size: 0.85rem;
            padding: 6px 8px;
            border-radius: 999px;
        }

        .btn-main {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
            color: #ffffff;
            border-radius: 999px;
            font-weight: 600;
        }

        .btn-main:hover {
            background-color: var(--primary-green-dark);
            border-color: var(--primary-green-dark);
            color: #ffffff;
        }

        .btn-outline-main {
            border: 1px solid var(--primary-green);
            color: var(--primary-green);
            border-radius: 999px;
            font-weight: 600;
        }

        .btn-outline-main:hover {
            background-color: var(--primary-green);
            color: #ffffff;
        }

        .table {
            font-size: 0.9rem;
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

        .text-muted-small {
            font-size: 0.85rem;
            color: var(--muted-text);
        }

        .empty-message {
            background-color: #f8f5ef;
            border-left: 4px solid var(--primary-green);
            border-radius: 12px;
            padding: 18px;
            color: var(--text-dark);
        }

        @media (max-width: 768px) {
            .admin-header {
                padding: 24px 0;
            }

            .table-container {
                padding: 18px;
            }

            .action-form {
                min-width: 220px;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg bg-white fixed-top">
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
                    <li class="nav-item"><a class="nav-link" href="../../admin/users/index.php">User</a></li>
                    <li class="nav-item"><a class="nav-link" href="../../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="admin-header">
        <div class="container-fluid">
            <h1 class="mb-1">Manajemen Booking</h1>
            <p class="mb-0 text-white-50">Kelola semua booking yang masuk ke sistem Shanti Asih Homestay.</p>
        </div>
    </section>

    <div class="container-fluid pb-5">
        <?php if (isset($_GET['message']) && $_GET['message'] === 'success'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Sukses!</strong> Status booking berhasil diperbarui.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['message']) && $_GET['message'] === 'error'): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> <?php echo htmlspecialchars($_GET['text'] ?? "Terjadi kesalahan."); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <a href="../../admin/dashboard/index.php" class="btn btn-outline-main">
                &larr; Kembali ke Dashboard
            </a>
        </div>

        <div class="table-container">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                <h5 class="fw-bold section-title mb-0">
                    Daftar Booking
                </h5>

                <span class="badge bg-light text-dark border">
                    Total: <?php echo count($bookings); ?> booking
                </span>
            </div>

            <?php if (count($bookings) > 0): ?>
                <table class="table table-striped table-hover align-middle">
                    <thead>
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
                        <?php foreach ($bookings as $booking): ?>
                            <?php
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

                                $harga_rupiah = "Rp" . number_format($booking['total_harga'], 0, ',', '.');

                                $catatan = !empty($booking['catatan'])
                                    ? substr(htmlspecialchars($booking['catatan']), 0, 30) . '...'
                                    : '-';
                            ?>

                            <tr>
                                <td><strong><?php echo htmlspecialchars($booking['id_booking']); ?></strong></td>
                                <td class="fw-semibold"><?php echo htmlspecialchars($booking['nama']); ?></td>
                                <td class="text-muted-small"><?php echo htmlspecialchars($booking['email']); ?></td>
                                <td class="text-muted-small"><?php echo htmlspecialchars($booking['no_hp']); ?></td>
                                <td><?php echo htmlspecialchars($booking['nama_room']); ?></td>
                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($booking['check_in']))); ?></td>
                                <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($booking['check_out']))); ?></td>
                                <td><?php echo htmlspecialchars($booking['jumlah_tamu']); ?> orang</td>
                                <td><strong><?php echo $harga_rupiah; ?></strong></td>
                                <td>
                                    <span class="badge <?php echo $badge_class; ?>">
                                        <?php echo ucfirst(htmlspecialchars($booking['status'])); ?>
                                    </span>
                                </td>
                                <td class="text-muted-small"><?php echo $catatan; ?></td>
                                <td class="text-muted-small">
                                    <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($booking['created_at']))); ?>
                                </td>
                                <td>
                                    <form method="POST" action="update_status.php" class="action-form">
                                        <input type="hidden" name="id_booking" value="<?php echo htmlspecialchars($booking['id_booking']); ?>">

                                        <select name="status" class="form-select form-select-sm" required>
                                            <option value="pending" <?php echo $booking['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="confirmed" <?php echo $booking['status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                            <option value="cancelled" <?php echo $booking['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                            <option value="completed" <?php echo $booking['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                        </select>

                                        <button type="submit" class="btn btn-sm btn-main">
                                            Update
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-message">
                    Belum ada booking yang masuk.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>