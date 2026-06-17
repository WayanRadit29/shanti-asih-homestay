<?php
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
        }

        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
        }

        .page-header h1 {
            font-weight: bold;
            font-size: 2.5rem;
        }

        .page-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .room-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            height: 100%;
        }

        .room-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .room-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .badge-status {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 0.85rem;
            padding: 8px 12px;
        }

        .room-card-body {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .room-description {
            flex-grow: 1;
        }

        .room-footer {
            margin-top: auto;
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
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link active" href="rooms.php">Kamar</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1>Daftar Kamar Kami</h1>
            <p>Pilih kamar impian Anda di Shanti Asih Homestay</p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container mb-5">
        <?php
        // kode ini untuk cek apakah ada kamar
        if (count($rooms) > 0) {
        ?>

        <div class="row g-4">
            <?php
            // kode ini untuk loop menampilkan setiap kamar dalam card
            foreach ($rooms as $room) {
                // kode ini untuk format harga ke Rupiah
                $harga_rupiah = "Rp" . number_format($room['harga'], 0, ',', '.');

                // kode ini untuk membuat potongan deskripsi singkat (60 karakter)
                $deskripsi_singkat = strlen($room['deskripsi']) > 60 
                    ? substr($room['deskripsi'], 0, 60) . '...' 
                    : $room['deskripsi'];

                // kode ini untuk tentukan status badge
                $is_available = $room['status'] === 'available';
                $badge_status = $is_available 
                    ? '<span class="badge bg-success badge-status">Tersedia</span>' 
                    : '<span class="badge bg-danger badge-status">Tidak Tersedia</span>';
            ?>

            <div class="col-md-6 col-lg-4">
                <div class="card room-card shadow-sm">
                    <!-- Container gambar dengan badge -->
                    <div style="position: relative;">
                        <img src="uploads/rooms/<?php echo htmlspecialchars($room['main_image']); ?>" 
                             class="card-img-top room-image" 
                             alt="<?php echo htmlspecialchars($room['nama_room']); ?>">
                        
                        <!-- Badge status kamar -->
                        <?php echo $badge_status; ?>
                    </div>

                    <div class="card-body room-card-body">
                        <!-- Nama kamar -->
                        <h5 class="card-title fw-bold">
                            <?php echo htmlspecialchars($room['nama_room']); ?>
                        </h5>

                        <!-- Harga per malam -->
                        <p class="card-text text-primary fw-bold fs-6">
                            <?php echo $harga_rupiah; ?> / malam
                        </p>

                        <!-- Kapasitas kamar -->
                        <p class="card-text text-muted small mb-2">
                            <i class="bi bi-people"></i> Kapasitas: <?php echo htmlspecialchars($room['kapasitas']); ?> orang
                        </p>

                        <!-- Deskripsi singkat -->
                        <p class="card-text small room-description text-muted">
                            <?php echo htmlspecialchars($deskripsi_singkat); ?>
                        </p>

                        <!-- Tombol detail -->
                        <div class="room-footer">
                            <a href="detail.php?id=<?php echo htmlspecialchars($room['id_room']); ?>" 
                               class="btn btn-main btn-sm w-100">
                                Detail Kamar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            }
            ?>
        </div>

        <?php
        } else {
            // kode ini untuk tampilkan pesan jika tidak ada kamar
        ?>

        <div class="alert alert-info text-center py-5">
            <h5 class="mb-3">Belum ada kamar tersedia</h5>
            <p class="text-muted mb-3">Silakan cek kembali nanti atau hubungi kami untuk informasi lebih lanjut.</p>
            <a href="index.php" class="btn btn-main">Kembali ke Beranda</a>
        </div>

        <?php
        }
        ?>
    </div>

    <!-- CTA Section -->
    <section class="py-5 bg-light text-center">
        <div class="container">
            <h3 class="fw-bold mb-3">Tertarik untuk Menginap?</h3>
            <p class="text-muted mb-4">Hubungi kami untuk reservasi atau pertanyaan lebih lanjut.</p>
            <a href="booking.php" class="btn btn-main btn-lg">Lakukan Booking</a>
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