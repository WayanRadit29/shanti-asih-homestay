<?php
// kode ini untuk proteksi hanya admin yang bisa akses halaman ini
include '../../auth/check_admin.php';

// kode ini untuk menghubungkan database
include '../../config/koneksi.php';

// kode ini untuk mengambil statistik total room
$result_rooms = mysqli_query($conn, "SELECT COUNT(*) as total FROM rooms");
$total_rooms = mysqli_fetch_assoc($result_rooms)['total'];

// kode ini untuk mengambil statistik total user
$result_users = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role = 'user'");
$total_users = mysqli_fetch_assoc($result_users)['total'];

// kode ini untuk mengambil statistik total booking
$result_bookings = mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings");
$total_bookings = mysqli_fetch_assoc($result_bookings)['total'];

// kode ini untuk mengambil statistik booking pending
$result_pending = mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE status = 'pending'");
$total_pending = mysqli_fetch_assoc($result_pending)['total'];

// kode ini untuk mengambil statistik booking confirmed
$result_confirmed = mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE status = 'confirmed'");
$total_confirmed = mysqli_fetch_assoc($result_confirmed)['total'];

// kode ini untuk mengambil 5 booking terbaru dengan JOIN
$query_latest = "SELECT 
                    b.id_booking,
                    u.nama,
                    r.nama_room,
                    b.check_in,
                    b.check_out,
                    b.status,
                    b.created_at
                FROM bookings b
                JOIN users u ON b.user_id = u.id
                JOIN rooms r ON b.room_id = r.id_room
                ORDER BY b.created_at DESC
                LIMIT 5";
$result_latest = mysqli_query($conn, $query_latest);
$latest_bookings = mysqli_fetch_all($result_latest, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Shanti Asih Homestay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f1e8;
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(135deg, #5f7a3a 0%, #8b6f47 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 20px;
            color: white !important;
        }

        .dashboard-container {
            margin-top: 40px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            padding: 0 15px 40px 15px;
        }

        .dashboard-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
        }

        .dashboard-title {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
        }

        .info-item {
            background-color: #f5f1e8;
            border-left: 4px solid #5f7a3a;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: center;
        }

        .info-label {
            font-size: 12px;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 18px;
            color: #333;
            font-weight: 700;
        }

        .btn-logout {
            background: linear-gradient(135deg, #5f7a3a 0%, #8b6f47 100%);
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 600;
            margin-top: 15px;
            max-width: 300px;
            display: inline-block;
            transition: transform 0.2s;
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-logout:active {
            transform: translateY(0);
        }

        .dashboard-container-full {
            margin-top: 40px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            padding: 0 15px 40px 15px;
        }

        .dashboard-card-main {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #5f7a3a 0%, #8b6f47 100%);
            color: white;
            border-radius: 10px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-value {
            font-size: 32px;
            font-weight: bold;
            margin: 10px 0;
        }

        .stat-label {
            font-size: 13px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .recent-bookings-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 40px;
        }

        .section-title {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #5f7a3a;
        }

        .recent-table {
            font-size: 0.95rem;
        }

        .recent-table thead {
            background: linear-gradient(135deg, #5f7a3a 0%, #8b6f47 100%);
            color: white;
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

        .btn-manage {
            background: linear-gradient(135deg, #5f7a3a 0%, #8b6f47 100%);
            border: none;
            color: white;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            transition: transform 0.2s;
        }

        .btn-manage:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .empty-message {
            background-color: #e7f3ff;
            border-left: 4px solid #5f7a3a;
            padding: 20px;
            border-radius: 5px;
            color: #333;
            text-align: center;
        }
        .action-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .btn-action {
            background: linear-gradient(135deg, #5f7a3a 0%, #8b6f47 100%);
            border: none;
            color: white;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.2s;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        @media (max-width: 768px) {
            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }

            .stat-card {
                padding: 15px;
            }

            .stat-value {
                font-size: 24px;
            }

            .stat-label {
                font-size: 11px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <span class="navbar-brand">📊 Shanti Asih Homestay - Admin</span>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Dashboard</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../rooms/index.php">Kamar</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../bookings/index.php">Booking</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../users/index.php">User</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../../index.php">Lihat Website</a>
                    </li>

                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-light btn-sm" href="../../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="dashboard-container">
        <!-- Welcome Card -->
        <div class="dashboard-card">
            <h1 class="dashboard-title">📊 Dashboard Admin</h1>

            <div class="info-item">
                <div class="info-label">Selamat Datang</div>
                <div class="info-value"><?php echo htmlspecialchars($_SESSION['nama']); ?></div>
            </div>

            <!-- Logout Button -->
            <a href="../../logout.php" class="btn btn-primary btn-logout">
                🚪 Logout
            </a>
        </div>

        <!-- Statistik Cards -->
        <div class="stats-container">
            <!-- Total Room -->
            <div class="stat-card">
                <div class="stat-label">🏠 Total Kamar</div>
                <div class="stat-value"><?php echo htmlspecialchars($total_rooms); ?></div>
            </div>

            <!-- Total User -->
            <div class="stat-card">
                <div class="stat-label">👥 Total User</div>
                <div class="stat-value"><?php echo htmlspecialchars($total_users); ?></div>
            </div>

            <!-- Total Booking -->
            <div class="stat-card">
                <div class="stat-label">📅 Total Booking</div>
                <div class="stat-value"><?php echo htmlspecialchars($total_bookings); ?></div>
            </div>

            <!-- Booking Pending -->
            <div class="stat-card">
                <div class="stat-label">⏳ Pending</div>
                <div class="stat-value"><?php echo htmlspecialchars($total_pending); ?></div>
            </div>

            <!-- Booking Confirmed -->
            <div class="stat-card">
                <div class="stat-label">✓ Confirmed</div>
                <div class="stat-value"><?php echo htmlspecialchars($total_confirmed); ?></div>
            </div>
        </div>

        <!-- Booking Terbaru Section -->
        <div class="recent-bookings-section">
            <h2 class="section-title">📋 Booking Terbaru</h2>

            <?php
            // kode ini untuk cek apakah ada booking terbaru
            if (count($latest_bookings) > 0) {
            ?>

            <div style="overflow-x: auto;">
                <table class="table table-striped table-hover recent-table">
                    <thead>
                        <tr>
                            <th>ID Booking</th>
                            <th>Nama User</th>
                            <th>Nama Kamar</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // kode ini untuk loop menampilkan setiap booking terbaru
                        foreach ($latest_bookings as $booking) {
                            // kode ini untuk tentukan class badge berdasarkan status
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
                        ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($booking['id_booking']); ?></strong></td>
                            <td><?php echo htmlspecialchars($booking['nama']); ?></td>
                            <td><?php echo htmlspecialchars($booking['nama_room']); ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($booking['check_in']))); ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($booking['check_out']))); ?></td>
                            <td>
                                <!-- kode ini untuk tampilkan status dengan badge -->
                                <span class="badge <?php echo $badge_class; ?>">
                                    <?php echo ucfirst(htmlspecialchars($booking['status'])); ?>
                                </span>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <?php
            } else {
                // kode ini untuk tampilkan pesan jika tidak ada booking terbaru
            ?>

            <div class="empty-message">
                📭 Belum ada booking yang masuk.
            </div>

            <?php
            }
            ?>

            <div class="action-buttons">
                <a href="../bookings/index.php" class="btn-action">
                    ⚙️ Kelola Booking
                </a>

                <a href="../users/index.php" class="btn-action">
                    👥 Kelola User
                </a>

                <a href="../rooms/index.php" class="btn-action">
                    🏠 Kelola Kamar
                </a>
                <a href="../../index.php" class="btn-action">
                    🌐 Lihat Website
                </a>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
