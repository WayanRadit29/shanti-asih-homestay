<?php
include '../auth/check_user.php';
include '../config/koneksi.php';

$user_id = $_SESSION['user_id'];

function getBookingCount($conn, $user_id, $status = null) {
    if ($status === null) {
        $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM bookings WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $user_id);
    } else {
        $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM bookings WHERE user_id = ? AND status = ?");
        mysqli_stmt_bind_param($stmt, "is", $user_id, $status);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    return $row['total'] ?? 0;
}

$total_booking = getBookingCount($conn, $user_id);
$total_pending = getBookingCount($conn, $user_id, 'pending');
$total_confirmed = getBookingCount($conn, $user_id, 'confirmed');
$total_cancelled = getBookingCount($conn, $user_id, 'cancelled');

$stmt = mysqli_prepare($conn, "
    SELECT b.*, r.nama_room
    FROM bookings b
    JOIN rooms r ON b.room_id = r.id_room
    WHERE b.user_id = ?
    ORDER BY b.created_at DESC
");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$bookings = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

function statusBadge($status) {
    $status_lower = strtolower($status);

    if ($status_lower === 'pending') return 'warning text-dark';
    if ($status_lower === 'confirmed') return 'success';
    if ($status_lower === 'cancelled') return 'danger';
    if ($status_lower === 'completed') return 'primary';

    return 'secondary';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User - Shanti Asih Homestay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-green: #5f7a3a;
            --primary-green-dark: #4f6530;
            --warm-brown: #8b6f47;
            --cream-bg: #f5f1e8;
            --card-bg: #ffffff;
            --text-dark: #2f2f2f;
            --muted-text: #6c757d;
        }

        body {
            background-color: var(--cream-bg);
            color: var(--text-dark);
            min-height: 100vh;
        }

        .navbar-custom {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--warm-brown) 100%);
            box-shadow: 0 2px 12px rgba(47, 47, 47, 0.12);
        }

        .navbar-brand {
            letter-spacing: 0.3px;
        }

        .dashboard-header {
            background: linear-gradient(135deg, rgba(95, 122, 58, 0.12), rgba(139, 111, 71, 0.12));
            border: 1px solid rgba(95, 122, 58, 0.12);
            border-radius: 18px;
            padding: 28px;
            margin-bottom: 28px;
        }

        .dashboard-title {
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .stat-card {
            border: 0;
            border-radius: 18px;
            overflow: hidden;
            transition: 0.25s ease;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 24px rgba(47, 47, 47, 0.12);
        }

        .stat-label {
            font-size: 13px;
            color: var(--muted-text);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 34px;
            color: var(--primary-green);
            font-weight: 800;
        }

        .section-card {
            border: 0;
            border-radius: 18px;
            overflow: hidden;
        }

        .section-title {
            color: var(--text-dark);
            padding-bottom: 12px;
            border-bottom: 3px solid var(--primary-green);
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

        .btn-soft {
            background-color: rgba(255, 255, 255, 0.16);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.45);
            border-radius: 999px;
            font-weight: 600;
        }

        .btn-soft:hover {
            background-color: #ffffff;
            color: var(--primary-green);
        }

        .btn-logout {
            background-color: #ffffff;
            color: var(--primary-green);
            border-radius: 999px;
            font-weight: 600;
        }

        .btn-logout:hover {
            background-color: #f5f1e8;
            color: var(--primary-green-dark);
        }

        .badge {
            padding: 8px 10px;
            border-radius: 999px;
            text-transform: capitalize;
        }

        .empty-state {
            background-color: #f8f5ef;
            border-left: 4px solid var(--primary-green);
            border-radius: 12px;
            padding: 18px;
            color: var(--text-dark);
        }

        @media (max-width: 576px) {
            .dashboard-header {
                padding: 20px;
            }

            .stat-value {
                font-size: 28px;
            }

            .navbar-actions {
                margin-top: 12px;
                width: 100%;
                display: flex;
                gap: 8px;
            }

            .navbar-actions .btn {
                flex: 1;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container">
            <a class="navbar-brand fw-bold" href="../index.php">Shanti Asih Homestay</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#userNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="userNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a href="../index.php" class="nav-link">Beranda</a>
                    </li>

                    <li class="nav-item">
                        <a href="../rooms.php" class="nav-link">Kamar</a>
                    </li>

                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link active">Dashboard</a>
                    </li>

                    <li class="nav-item ms-lg-2">
                        <a href="../logout.php" class="btn btn-logout btn-sm">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <div class="dashboard-header shadow-sm">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h2 class="fw-bold dashboard-title">Dashboard User</h2>
                    <p class="text-muted mb-0">
                        Selamat datang, <?= htmlspecialchars($_SESSION['nama']); ?>. Pantau riwayat dan status booking Anda di sini.
                    </p>
                </div>

                <a href="../rooms.php" class="btn btn-main">
                    + Booking Baru
                </a>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-3 col-6">
                <div class="card stat-card shadow-sm">
                    <div class="card-body">
                        <p class="stat-label mb-1">Total Booking</p>
                        <div class="stat-value"><?= $total_booking; ?></div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card stat-card shadow-sm">
                    <div class="card-body">
                        <p class="stat-label mb-1">Pending</p>
                        <div class="stat-value"><?= $total_pending; ?></div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card stat-card shadow-sm">
                    <div class="card-body">
                        <p class="stat-label mb-1">Confirmed</p>
                        <div class="stat-value"><?= $total_confirmed; ?></div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card stat-card shadow-sm">
                    <div class="card-body">
                        <p class="stat-label mb-1">Cancelled</p>
                        <div class="stat-value"><?= $total_cancelled; ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card section-card shadow-sm">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-4 section-title">Riwayat Booking Saya</h4>

                <?php if (count($bookings) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Kamar</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Tamu</th>
                                    <th>Total Harga</th>
                                    <th>Status</th>
                                    <th>Tanggal Booking</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bookings as $booking): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($booking['id_booking']); ?></td>
                                        <td class="fw-semibold"><?= htmlspecialchars($booking['nama_room']); ?></td>
                                        <td><?= htmlspecialchars(date('d/m/Y', strtotime($booking['check_in']))); ?></td>
                                        <td><?= htmlspecialchars(date('d/m/Y', strtotime($booking['check_out']))); ?></td>
                                        <td><?= htmlspecialchars($booking['jumlah_tamu']); ?></td>
                                        <td class="fw-semibold">Rp <?= number_format($booking['total_harga'], 0, ',', '.'); ?></td>
                                        <td>
                                            <span class="badge bg-<?= statusBadge($booking['status']); ?>">
                                                <?= htmlspecialchars($booking['status']); ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($booking['created_at']))); ?></td>
                                        <td>
                                            <a href="../reports/booking_pdf.php?id=<?= htmlspecialchars($booking['id_booking']); ?>" class="btn btn-sm btn-main" target="_blank">
                                                Cetak PDF
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state mb-0">
                        Belum ada riwayat booking. Silakan pilih kamar untuk mulai melakukan booking.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>