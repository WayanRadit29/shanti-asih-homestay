<?php
// kode ini untuk menghubungkan database
include "config/koneksi.php";

// kode ini untuk inisialisasi variabel
$room = null;
$images = [];
$error = null;

// kode ini untuk validasi id dari URL, harus angka
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_room = (int)$_GET['id'];
    
    // kode ini untuk query data kamar berdasarkan id_room menggunakan prepared statement
    $stmt = mysqli_prepare($conn, "SELECT * FROM rooms WHERE id_room = ?");
    mysqli_stmt_bind_param($stmt, "i", $id_room);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $room = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    // kode ini untuk jika kamar ditemukan, ambil gambar tambahan
    if ($room) {
        $stmt_images = mysqli_prepare($conn, "SELECT * FROM room_images WHERE room_id = ? ORDER BY created_at ASC");
        mysqli_stmt_bind_param($stmt_images, "i", $id_room);
        mysqli_stmt_execute($stmt_images);
        $result_images = mysqli_stmt_get_result($stmt_images);
        $images = mysqli_fetch_all($result_images, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt_images);
    } else {
        $error = "Kamar tidak ditemukan.";
    }
} else {
    $error = "Kamar tidak ditemukan.";
}

// kode ini untuk format harga ke Rupiah jika data kamar ada
$harga_rupiah = "";
if ($room) {
    $harga_rupiah = "Rp" . number_format($room['harga'], 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $room ? htmlspecialchars($room['nama_room']) : "Detail Kamar"; ?> - Shanti Asih Homestay</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        body {
            padding-top: 80px;
            background-color: #ffffff;
        }

        .detail-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .main-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .gallery-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 12px;
            cursor: pointer;
            transition: 0.3s;
        }

        .gallery-image:hover {
            transform: scale(1.03);
        }

        .detail-info {
            background: #f5f1e8;
            border-radius: 18px;
            padding: 28px;
            box-shadow: 0 10px 24px rgba(0,0,0,0.08);
        }

        .info-row {
            padding: 14px 0;
            border-bottom: 1px solid #ddd;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .room-price {
            color: #5f7a3a;
            font-size: 2rem;
            font-weight: 800;
        }

        .badge-status {
            padding: 10px 14px;
            border-radius: 999px;
            font-size: 0.9rem;
        }

        .section-card {
            background: white;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 10px 24px rgba(0,0,0,0.06);
        }

        .booking-btn {
            padding: 14px;
            font-weight: 700;
            font-size: 1rem;
        }

        .breadcrumb a {
            color: #5f7a3a;
            text-decoration: none;
        }

        .back-button {
            margin-bottom: 20px;
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

            <div class="ms-auto">
                <a href="rooms.php" class="btn btn-sm btn-outline-secondary">Kembali ke Kamar</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container detail-container py-5">
        <?php
        // kode ini untuk tampilkan pesan error jika kamar tidak ditemukan
        if ($error) {
        ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <div class="text-center mt-4">
                <a href="rooms.php" class="btn btn-main">Kembali ke Kamar</a>
            </div>
        <?php
        } else {
        ?>
            <div class="back-button">
                <a href="rooms.php" class="btn btn-outline-success">
                    ← Kembali ke Daftar Kamar
                </a>
            </div>

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="rooms.php">Kamar</a></li>
                    <li class="breadcrumb-item active"><?php echo htmlspecialchars($room['nama_room']); ?></li>
                </ol>
            </nav>

            <div class="row g-4">
                <!-- Kolom Gambar -->
                <div class="col-lg-8">
                    <!-- Gambar Utama -->
                    <div class="mb-4">
                        <img src="uploads/rooms/<?php echo htmlspecialchars($room['main_image']); ?>" 
                             alt="<?php echo htmlspecialchars($room['nama_room']); ?>" 
                             class="main-image"
                             id="mainImage">
                    </div>

                    <!-- Gallery Gambar Tambahan -->
                    <?php
                    // kode ini untuk tampilkan gallery jika ada gambar tambahan
                    if (count($images) > 0) {
                    ?>
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Galeri Kamar</h5>
                        <div class="row g-3">
                            <?php
                            // kode ini untuk loop menampilkan setiap gambar tambahan
                            foreach ($images as $image) {
                            ?>
                            <div class="col-6 col-md-4">
                                <img src="uploads/rooms/<?php echo htmlspecialchars($image['image_path']); ?>" 
                                     alt="Gambar Kamar" 
                                     class="gallery-image"
                                     onclick="document.getElementById('mainImage').src = this.src;">
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                    }
                    ?>

                    <!-- Deskripsi Lengkap -->
                    <div class="section-card mb-4">
                        <h5 class="fw-bold mb-3">Deskripsi Kamar</h5>
                        <p class="text-muted">
                            <?php echo htmlspecialchars($room['deskripsi']); ?>
                        </p>
                    </div>
                </div>

                <!-- Kolom Info & Booking -->
                <div class="col-lg-4">
                    <!-- Detail Info -->
                    <div class="detail-info">
                        <!-- Nama Kamar -->
                        <h2 class="fw-bold mb-3">
                            <?php echo htmlspecialchars($room['nama_room']); ?>
                        </h2>

                        <!-- Harga -->
                        <div class="info-row">
                            <p class="mb-0">
                                💰 <strong>Harga per Malam</strong>
                            </p>
                            <p class="room-price mb-0">
                                <?php echo $harga_rupiah; ?> / malam
                            </p>
                        </div>

                        <!-- Kapasitas -->
                        <div class="info-row">
                            <p class="mb-0">
                                👥 <strong>Kapasitas Tamu</strong>
                            </p>
                            <p class="text-muted">
                                <?php echo htmlspecialchars($room['kapasitas']); ?> orang
                            </p>
                        </div>

                        <!-- Status -->
                        <div class="info-row">
                            <p class="mb-0">
                                ✓ <strong>Status Kamar</strong>
                            </p>
                            <?php
                            // kode ini untuk tampilkan badge status dengan warna berbeda
                            if ($room['status'] === 'available') {
                                echo '<span class="badge badge-status text-white" style="background:#5f7a3a;">Tersedia</span>';
                            } else {
                                echo '<span class="badge bg-secondary badge-status">Tidak Tersedia</span>';
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-4">
                        <?php
                        // kode ini untuk tampilkan tombol booking jika kamar tersedia
                        if ($room['status'] === 'available') {
                        ?>
                        <a href="booking.php?room_id=<?php echo htmlspecialchars($room['id_room']); ?>" class="btn btn-main booking-btn w-100 mb-2">
                            Booking Sekarang
                        </a>
                        <?php
                        } else {
                        ?>
                        <button class="btn btn-secondary w-100 mb-2" disabled>
                            Kamar Tidak Tersedia
                        </button>
                        <?php
                        }
                        ?>

                        <a href="rooms.php" class="btn btn-outline-secondary w-100">
                            Kembali
                        </a>
                    </div>

                    <!-- Additional Info Box -->
                    <div class="section-card mt-4">
                        <p class="mb-1">
                            <strong>Informasi Penting:</strong>
                        </p>
                        <p class="mb-0">
                            Untuk pertanyaan lebih lanjut, silakan menghubungi kami melalui contact yang tersedia.
                        </p>
                    </div>
                </div>
            </div>

        <?php
        }
        ?>
    </div>

    <!-- Footer -->
    <footer class="py-4 footer-custom text-center mt-5">
        <p class="mb-0">&copy; 2026 Shanti Asih Homestay. All Rights Reserved.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>