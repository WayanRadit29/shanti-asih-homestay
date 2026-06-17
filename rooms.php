<?php
// kode ini untuk memulai session agar navbar bisa menyesuaikan status login
session_start();

// kode ini untuk menghubungkan database
include "config/koneksi.php";

// kode ini untuk mengambil semua data kamar dari database, urutkan dari terbaru
$query = "SELECT * FROM rooms ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$rooms = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kamar - Shanti Asih Homestay</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        body {
            padding-top: 80px;
            background-color: #ffffff;
        }

        .page-header {
            background: linear-gradient(135deg, #5f7a3a 0%, #8b6f47 100%);
            color: white;
            padding: 70px 0;
            margin-bottom: 50px;
        }

        .page-header h1 {
            font-weight: 800;
            font-size: 2.5rem;
            letter-spacing: 0.5px;
        }

        .page-header p {
            font-size: 1.05rem;
            opacity: 0.9;
        }

        .section-title {
            color: #2f2f2f;
            padding-bottom: 10px;
            border-bottom: 3px solid #5f7a3a;
        }

        .room-card {
            border: none;
            border-radius: 18px;
            overflow: hidden;
            height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .room-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
        }

        .room-image {
            width: 100%;
            height: 260px;
            object-fit: cover;
        }

        .badge-status {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 0.85rem;
            padding: 8px 12px;
            border-radius: 999px;
        }

        .room-card-body {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .room-price {
            color: #5f7a3a;
        }

        .room-description {
            flex-grow: 1;
        }

        .room-footer {
            margin-top: auto;
        }

        .empty-box {
            background-color: #f8f5ef;
            border-left: 4px solid #5f7a3a;
            border-radius: 16px;
            padding: 32px;
        }

        .cta-section {
            background-color: #f5f1e8;
        }

        @media (max-width: 768px) {
            .page-header {
                padding: 50px 0;
                margin-bottom: 35px;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .room-image {
                height: 230px;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center fw-bold" href="index.php">
                <img src="assets/images/logo.png" alt="Logo" class="logo-img me-2" style="height: 40px;">
                <span>Shanti Asih</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Beranda</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active" href="rooms.php">Kamar</a>
                    </li>

                    <?php if (isset($_SESSION['user_id'])): ?>

                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin/dashboard/index.php">Dashboard Admin</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="user/dashboard.php">Dashboard</a>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-main btn-sm" href="logout.php">Logout</a>
                        </li>

                    <?php else: ?>

                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>

                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-main btn-sm" href="register.php">Register</a>
                        </li>

                    <?php endif; ?>
                </ul>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Daftar Kamar Kami</h1>
            <p class="mb-0">Pilih kamar terbaik untuk pengalaman menginap yang tenang dan nyaman di Ubud.</p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container mb-5">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <h5 class="fw-bold section-title mb-0">
                Pilihan Kamar
            </h5>

            <span class="badge bg-light text-dark border px-3 py-2">
                Total: <?= count($rooms); ?> kamar
            </span>
        </div>

        <?php if (count($rooms) > 0): ?>

            <div class="row g-4">
                <?php foreach ($rooms as $room): ?>
                    <?php
                    // kode ini untuk format harga ke Rupiah
                    $harga_rupiah = "Rp" . number_format($room['harga'], 0, ',', '.');

                    // kode ini untuk membuat potongan deskripsi singkat
                    $deskripsi_singkat = strlen($room['deskripsi']) > 75
                        ? substr($room['deskripsi'], 0, 75) . '...'
                        : $room['deskripsi'];

                    // kode ini untuk menentukan status badge
                    $is_available = $room['status'] === 'available';
                    $badge_status = $is_available
                        ? '<span class="badge badge-status text-white" style="background:#5f7a3a;">Tersedia</span>'
                        : '<span class="badge badge-status bg-secondary">Tidak Tersedia</span>';
                    ?>

                    <div class="col-md-6 col-lg-4">
                        <div class="card room-card shadow-sm">
                            <div style="position: relative;">
                                <img
                                    src="uploads/rooms/<?php echo htmlspecialchars($room['main_image']); ?>"
                                    class="card-img-top room-image"
                                    alt="<?php echo htmlspecialchars($room['nama_room']); ?>"
                                >

                                <?php echo $badge_status; ?>
                            </div>

                            <div class="card-body room-card-body">
                                <h5 class="card-title fw-bold mb-2">
                                    <?php echo htmlspecialchars($room['nama_room']); ?>
                                </h5>

                                <p class="fw-bold fs-5 mb-2 room-price">
                                    <?php echo $harga_rupiah; ?> / malam
                                </p>

                                <p class="card-text text-muted small mb-2">
                                    Kapasitas: <?php echo htmlspecialchars($room['kapasitas']); ?> orang
                                </p>

                                <p class="card-text small room-description text-muted">
                                    <?php echo htmlspecialchars($deskripsi_singkat); ?>
                                </p>

                                <div class="room-footer">
                                    <a
                                        href="detail.php?id=<?php echo htmlspecialchars($room['id_room']); ?>"
                                        class="btn btn-main btn-sm w-100"
                                    >
                                        Detail Kamar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>

        <?php else: ?>

            <div class="empty-box text-center">
                <h5 class="mb-3">Belum ada kamar tersedia</h5>
                <p class="text-muted mb-3">
                    Silakan cek kembali nanti atau hubungi kami untuk informasi lebih lanjut.
                </p>
                <a href="index.php" class="btn btn-main">Kembali ke Beranda</a>
            </div>

        <?php endif; ?>
    </div>

    <!-- CTA Section -->
    <section class="py-5 text-center cta-section">
        <div class="container">
            <h3 class="fw-bold mb-3">Siap Menginap di Ubud?</h3>
            <p class="text-muted mb-4">
                Pilih kamar terbaik dan lakukan booking dalam beberapa langkah sederhana.
            </p>
            <a href="rooms.php" class="btn btn-main btn-lg">Pilih Kamar Sekarang</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4 footer-custom text-center">
        <p class="mb-0">&copy; 2026 Shanti Asih Homestay. All Rights Reserved.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>