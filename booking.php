<?php
// kode ini untuk memulai session
session_start();

// kode ini untuk menghubungkan database
include "config/koneksi.php";

// kode ini untuk validasi user harus login terlebih dahulu
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// kode ini untuk inisialisasi variabel
$room = null;
$error = null;
$success = null;

// kode ini untuk validasi room_id dari URL harus angka
if (!isset($_GET['room_id']) || !is_numeric($_GET['room_id'])) {
    $error = "Kamar tidak ditemukan.";
} else {
    $room_id = (int)$_GET['room_id'];

    // kode ini untuk query data kamar berdasarkan room_id menggunakan prepared statement
    $stmt = mysqli_prepare($conn, "SELECT * FROM rooms WHERE id_room = ?");
    mysqli_stmt_bind_param($stmt, "i", $room_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $room = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    // kode ini untuk cek jika kamar tidak ditemukan
    if (!$room) {
        $error = "Kamar tidak ditemukan.";
    }
}

// kode ini untuk handle submit form booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $room) {
    // kode ini untuk mengambil data dari form
    $check_in = $_POST['check_in'] ?? '';
    $check_out = $_POST['check_out'] ?? '';
    $jumlah_tamu = (int)($_POST['jumlah_tamu'] ?? 0);
    $catatan = trim($_POST['catatan'] ?? '');

    // kode ini untuk validasi field penting harus diisi
    if (empty($check_in) || empty($check_out) || $jumlah_tamu === 0) {
        $error = "Semua field harus diisi.";
    }
    // kode ini untuk validasi check_out harus lebih besar dari check_in
    elseif (strtotime($check_out) <= strtotime($check_in)) {
        $error = "Tanggal check-out harus lebih besar dari check-in.";
    }
    // kode ini untuk validasi jumlah tamu tidak boleh melebihi kapasitas kamar
    elseif ($jumlah_tamu > $room['kapasitas']) {
        $error = "Jumlah tamu tidak boleh melebihi kapasitas kamar (" . $room['kapasitas'] . " orang).";
    }
    else {
        // kode ini untuk cek apakah kamar sudah dibooking pada rentang tanggal yang sama menggunakan prepared statement
        $stmt_check = mysqli_prepare($conn, "SELECT COUNT(*) as total FROM bookings WHERE room_id = ? AND status IN ('pending', 'confirmed') AND (? < check_out AND ? > check_in)");
        mysqli_stmt_bind_param($stmt_check, "iss", $room_id, $check_in, $check_out);

        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);
        $count_conflict = mysqli_fetch_assoc($result_check);
        mysqli_stmt_close($stmt_check);

        // kode ini untuk cek jika ada bentrok tanggal booking
        if ($count_conflict['total'] > 0) {
            $error = "Kamar sudah dibooking pada rentang tanggal tersebut.";
        }
        else {
            // kode ini untuk hitung jumlah malam
            $date_checkin = new DateTime($check_in);
            $date_checkout = new DateTime($check_out);
            $jumlah_malam = $date_checkout->diff($date_checkin)->days;

            // kode ini untuk hitung total harga
            $total_harga = $jumlah_malam * $room['harga'];

            // kode ini untuk simpan data booking ke database menggunakan prepared statement
            $stmt_insert = mysqli_prepare($conn, "INSERT INTO bookings (user_id, room_id, check_in, check_out, jumlah_tamu, total_harga, status, catatan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            // kode ini untuk bind parameter
            $status = 'pending';
            mysqli_stmt_bind_param($stmt_insert, "iissidss", 
                $_SESSION['user_id'],
                $room_id,
                $check_in,
                $check_out,
                $jumlah_tamu,
                $total_harga,
                $status,
                $catatan
            );

            // kode ini untuk eksekusi query
            if (mysqli_stmt_execute($stmt_insert)) {
                $success = "Booking berhasil dibuat! Silakan cek dashboard untuk detail booking.";
                // kode ini untuk redirect ke dashboard setelah 2 detik
                header("Refresh: 2; url=user/dashboard.php");
            } else {
                $error = "Terjadi kesalahan saat menyimpan booking: " . mysqli_error($conn);
            }

            mysqli_stmt_close($stmt_insert);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Kamar - Shanti Asih Homestay</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        body {
            padding-top: 80px;
            background-color: #f8f6f1;
        }

        .booking-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .room-info-card,
        .booking-form {
            background: #ffffff;
            border-radius: 18px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: none;
        }

        .room-info-card {
            margin-bottom: 30px;
        }

        .form-section-title {
            font-weight: 700;
            margin-top: 25px;
            margin-bottom: 18px;
            padding-bottom: 10px;
            border-bottom: 2px solid #6b8e23;
            color: #556b2f;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 0;
            border-bottom: 1px solid #ececec;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #555;
        }

        .info-value {
            font-weight: 600;
        }

        .price-highlight {
            color: #6b8e23;
            font-size: 1.3rem;
            font-weight: 800;
        }

        .booking-header {
            background: linear-gradient(
                rgba(0,0,0,0.45),
                rgba(0,0,0,0.45)
            ),
            url('assets/images/homestay-hero.jpg');
            background-size: cover;
            background-position: center;
            border-radius: 20px;
            padding: 60px;
            color: white;
            margin-bottom: 30px;
        }

        .booking-header h1 {
            font-weight: 800;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            padding: 12px;
        }

        .form-control:focus {
            border-color: #6b8e23;
            box-shadow: 0 0 0 0.15rem rgba(107,142,35,0.2);
        }

        .btn-main {
            padding: 12px 24px;
            font-weight: 700;
        }
        .btn-outline-main {
                    border: 2px solid #6b8e23;
                    color: #6b8e23;
                    background-color: transparent;
                    border-radius: 30px;
                    padding: 12px 24px;
                    font-weight: 700;
                }

        .btn-outline-main:hover {
            background-color: #6b8e23;
            color: white;
                }

        .btn-back {
            margin-bottom: 20px;
        }

        .alert {
            border-radius: 14px;
        }

        .booking-summary {
            background: #f5f1e8;
            border-radius: 14px;
            padding: 20px;
            margin-top: 20px;
        }
        
    </style>
</head>
<body>
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="index.php">Beranda</a>
            </li>

            <li class="breadcrumb-item">
                <a href="rooms.php">Kamar</a>
            </li>

            <li class="breadcrumb-item">
                <a href="detail.php?id=<?php echo $room['id_room']; ?>">
                    <?php echo htmlspecialchars($room['nama_room']); ?>
                </a>
            </li>

            <li class="breadcrumb-item active">
                Booking
            </li>
        </ol>
    </nav>

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
                    <li class="nav-item"><a class="nav-link" href="rooms.php">Kamar</a></li>
                    <li class="nav-item"><a class="nav-link" href="user/dashboard.php">Dashboard Saya</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container booking-container py-5">

        <div class="booking-header text-center">
            <h1>Booking Kamar</h1>
            <p class="mb-0">
                Lengkapi informasi pemesanan Anda untuk menginap di Shanti Asih Homestay
            </p>
        </div>
        <!-- Tombol kembali -->
        <div class="btn-back">
            <a href="rooms.php" class="btn btn-outline-secondary">
                &larr; Kembali ke Daftar Kamar
            </a>
        </div>

        <!-- Alert Error -->
        <?php if ($error && !$success): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Alert Success -->
        <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Sukses!</strong> <?php echo htmlspecialchars($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php
        // kode ini untuk tampilkan form booking hanya jika kamar ditemukan dan tidak ada error
        if ($room && !$error):
        ?>

            <!-- Info Kamar -->
            <div class="room-info-card">
                <h4 class="fw-bold mb-4">Informasi Kamar</h4>

                <div class="info-row">
                    <span class="info-label">Nama Kamar</span>
                    <span class="info-value"><?php echo htmlspecialchars($room['nama_room']); ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Harga per Malam</span>
                    <span class="price-highlight">Rp<?php echo number_format($room['harga'], 0, ',', '.'); ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Kapasitas Kamar</span>
                    <span class="info-value"><?php echo htmlspecialchars($room['kapasitas']); ?> orang</span>
                </div>
            </div>

            <div class="booking-summary">
                <h6 class="fw-bold mb-3">Ringkasan Kamar</h6>

                <ul class="mb-0">
                    <li>Kapasitas maksimal <?= htmlspecialchars($room['kapasitas']); ?> tamu</li>
                    <li>Harga dihitung berdasarkan jumlah malam menginap</li>
                    <li>Booking harus dikonfirmasi admin terlebih dahulu</li>
                    <li>Tanggal yang bentrok otomatis ditolak sistem</li>
                </ul>
            </div>

            <!-- Form Booking -->
            <div class="booking-form">
                <h4 class="fw-bold mb-4">
                    Lengkapi Data Pemesanan
                </h4>

            <p class="text-muted mb-4">
                Pastikan tanggal menginap dan jumlah tamu sudah sesuai sebelum melakukan booking.
            </p>

                <form method="POST" action="">
                    <!-- Tanggal Check-in dan Check-out -->
                    <div class="form-section-title">Tanggal Menginap</div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="check_in" class="form-label">Check-in <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="check_in" name="check_in" 
                                   value="<?php echo isset($_POST['check_in']) ? htmlspecialchars($_POST['check_in']) : ''; ?>" 
                                   min="<?php echo date('Y-m-d'); ?>"
                                   required>
                        </div>
                        <div class="col-md-6">
                            <label for="check_out" class="form-label">Check-out <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="check_out" name="check_out" 
                                   value="<?php echo isset($_POST['check_out']) ? htmlspecialchars($_POST['check_out']) : ''; ?>" 
                                   min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                                   required>
                        </div>
                    </div>

                    <!-- Jumlah Tamu -->
                    <div class="form-section-title">Detail Menginap</div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="jumlah_tamu" class="form-label">Jumlah Tamu <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="jumlah_tamu" name="jumlah_tamu" 
                                   value="<?php echo isset($_POST['jumlah_tamu']) ? htmlspecialchars($_POST['jumlah_tamu']) : '1'; ?>" 
                                   min="1" max="<?php echo htmlspecialchars($room['kapasitas']); ?>"
                                   required>
                            <small class="text-muted">Maksimal: <?php echo htmlspecialchars($room['kapasitas']); ?> orang</small>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="form-section-title">Catatan Tambahan (Opsional)</div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="4" 
                                      placeholder="Tuliskan catatan atau permintaan khusus Anda..."></textarea>
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <a href="detail.php?id=<?php echo $room['id_room']; ?>"
                        class="btn btn-outline-main btn-lg px-4">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-main btn-lg px-5">Lakukan Pemesanan</button>
                    </div>
                </form>
            </div>

        <?php
        else:
            // kode ini untuk tampilkan pesan jika kamar tidak ditemukan
        ?>

            <div class="alert alert-danger">
                <h5>Kamar Tidak Ditemukan</h5>
                <p><?php echo htmlspecialchars($error ?? "Silakan pilih kamar terlebih dahulu."); ?></p>
            </div>

            <a href="rooms.php" class="btn btn-main">Kembali ke Daftar Kamar</a>

        <?php
        endif;
        ?>

    </div>

    <!-- Footer -->
    <footer class="py-4 footer-custom text-center mt-5">
        <p class="mb-0">&copy; 2026 Shanti Asih Homestay. All Rights Reserved.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script untuk validasi client-side -->
    <script>
        // kode ini untuk validasi form sebelum submit
        document.querySelector('form')?.addEventListener('submit', function(e) {
            const checkIn = new Date(document.getElementById('check_in').value);
            const checkOut = new Date(document.getElementById('check_out').value);
            const jumlahTamu = parseInt(document.getElementById('jumlah_tamu').value);
            const kapasitas = <?php echo $room['kapasitas'] ?? 0; ?>;

            if (checkOut <= checkIn) {
                e.preventDefault();
                alert('Tanggal check-out harus lebih besar dari check-in!');
                return false;
            }

            if (jumlahTamu > kapasitas) {
                e.preventDefault();
                alert('Jumlah tamu tidak boleh melebihi kapasitas kamar!');
                return false;
            }
        });
    </script>

</body>
</html>