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
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand fw-bold" href="../index.php">Shanti Asih Homestay</a>
            <div class="ms-auto">
                <a href="../rooms.php" class="btn btn-outline-light btn-sm me-2">Lihat Kamar</a>
                <a href="../logout.php" class="btn btn-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <div class="mb-4">
            <h2 class="fw-bold">Dashboard User</h2>
            <p class="text-muted mb-0">
                Selamat datang, <?= htmlspecialchars($_SESSION['nama']); ?>.
            </p>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-3 col-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <p class="text-muted mb-1">Total Booking</p>
                        <h3 class="fw-bold mb-0"><?= $total_booking; ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <p class="text-muted mb-1">Pending</p>
                        <h3 class="fw-bold mb-0"><?= $total_pending; ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <p class="text-muted mb-1">Confirmed</p>
                        <h3 class="fw-bold mb-0"><?= $total_confirmed; ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <p class="text-muted mb-1">Cancelled</p>
                        <h3 class="fw-bold mb-0"><?= $total_cancelled; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="fw-bold mb-3">Riwayat Booking Saya</h4>

                <?php if (count($bookings) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-success">
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
                                        <td><?= htmlspecialchars($booking['nama_room']); ?></td>
                                        <td><?= htmlspecialchars($booking['check_in']); ?></td>
                                        <td><?= htmlspecialchars($booking['check_out']); ?></td>
                                        <td><?= htmlspecialchars($booking['jumlah_tamu']); ?></td>
                                        <td>Rp <?= number_format($booking['total_harga'], 0, ',', '.'); ?></td>
                                        <td>
                                            <span class="badge bg-<?= statusBadge($booking['status']); ?>">
                                                <?= htmlspecialchars($booking['status']); ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($booking['created_at']); ?></td>
                                        <td>
                                            <a href="../reports/booking_pdf.php?id=<?= $booking['id_booking']; ?>" class="btn btn-sm btn-success" target="_blank">
                                                Cetak PDF
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mb-0">
                        Belum ada riwayat booking.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>