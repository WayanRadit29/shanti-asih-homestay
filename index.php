<?php
// kode ini untuk memulai session agar navbar bisa menyesuaikan status login
session_start();

// kode ini untuk menghubungkan database
include "config/koneksi.php";

// kode ini untuk mengambil data kamar yang tersedia, maksimal 3 kamar
$query = "SELECT * FROM rooms WHERE status = 'available' ORDER BY created_at DESC LIMIT 3";
$result = mysqli_query($conn, $query);
$rooms = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shanti Asih Homestay</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg bg-white shadow-sm fixed-top">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center fw-bold" href="index.php">
        <img src="assets/images/logo.png" alt="Logo" class="logo-img me-2">
        <span>Shanti Asih</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li class="nav-item">
            <a class="nav-link active" href="index.php">Beranda</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="rooms.php">Kamar</a>
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
        
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero-section d-flex align-items-center">
    <div class="container text-center text-white hero-content">
      <h1 class="display-4 fw-bold hero-title">Shanti Asih Homestay</h1>
      <p class="lead hero-subtitle">Pengalaman menginap tenang dan nyaman di jantung Ubud</p>
      <a href="rooms.php" class="btn btn-main btn-lg mt-3">Lihat Kamar</a>
    </div>
  </section>

  <!-- About Section -->
  <section class="py-5">
    <div class="container">
      <div class="row align-items-center g-4">
        <div class="col-md-6">
          <h2 class="fw-bold">Selamat Datang di Shanti Asih Homestay</h2>
          <p>
            Shanti Asih Homestay adalah homestay keluarga yang berlokasi di Ubud, Bali.
            Website ini membantu calon tamu melihat informasi kamar, fasilitas, dan melakukan
            simulasi booking secara online.
          </p>
          <p>
            Kami menyediakan suasana menginap yang tenang, nyaman, dan dekat dengan nuansa
            budaya serta alam khas Ubud.
          </p>
        </div>

        <div class="col-md-6">
          <div class="info-card p-4 rounded shadow-sm">
            <h5 class="fw-bold">Informasi Singkat</h5>
            <p class="mb-1"><strong>Lokasi:</strong> Jalan Gotama Selatan No. 25, Ubud, Bali</p>
            <p class="mb-1"><strong>Jumlah Kamar:</strong> 4 kamar</p>
            <p class="mb-1"><strong>Tipe:</strong> Standard Room</p>
            <p class="mb-0"><strong>Harga:</strong> Rp350.000 / malam</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Facilities Section -->
  <section class="py-5 bg-light">
    <div class="container">
      <div class="text-center mb-4">
        <h2 class="fw-bold">Fasilitas Homestay</h2>
        <p class="text-muted">Fasilitas utama yang tersedia untuk kenyamanan tamu.</p>
      </div>

      <div class="row g-4">
        <div class="col-md-6">
          <div class="facility-card bg-white rounded shadow-sm h-100">
            <img src="assets/images/pool.jpg" class="img-fluid facility-large-img" alt="Kolam Renang">
            <div class="p-4">
              <h5 class="fw-bold">Kolam Renang</h5>
              <p class="mb-0">
                Area santai untuk tamu menikmati suasana Ubud dengan lingkungan yang tenang dan nyaman.
              </p>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="facility-card bg-white rounded shadow-sm h-100">
            <img src="assets/images/yoga.jpg" class="img-fluid facility-large-img" alt="Yoga Sala">
            <div class="p-4">
              <h5 class="fw-bold">Yoga Sala</h5>
              <p class="mb-0">
                Tempat khusus untuk yoga, meditasi, atau olahraga ringan selama menginap.
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-3 mt-4 text-center">
        <div class="col-md-3 col-6">
          <div class="small-facility-box p-3 rounded bg-white shadow-sm">WiFi</div>
        </div>

        <div class="col-md-3 col-6">
          <div class="small-facility-box p-3 rounded bg-white shadow-sm">AC</div>
        </div>

        <div class="col-md-3 col-6">
          <div class="small-facility-box p-3 rounded bg-white shadow-sm">Kamar Mandi Dalam</div>
        </div>

        <div class="col-md-3 col-6">
          <div class="small-facility-box p-3 rounded bg-white shadow-sm">Standard Room</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Pilihan Kamar / Featured Rooms Section -->
  <section class="py-5">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold">Pilihan Kamar Kami</h2>
        <p class="text-muted">Lihat kamar-kamar terbaik kami yang tersedia untuk Anda.</p>
      </div>

      <?php
      // kode ini untuk cek apakah ada kamar yang tersedia
      if (count($rooms) > 0) {
      ?>
      <div class="row g-4">
        <?php
        // kode ini untuk loop menampilkan setiap kamar
        foreach ($rooms as $room) {
          // kode ini untuk membuat harga dalam format Rupiah
          $harga_rupiah = "Rp" . number_format($room['harga'], 0, ',', '.');
          
          // kode ini untuk membuat potongan deskripsi singkat (50 karakter)
          $deskripsi_singkat = strlen($room['deskripsi']) > 50 
            ? substr($room['deskripsi'], 0, 50) . '...' 
            : $room['deskripsi'];
        ?>
        <div class="col-md-4">
          <div class="card h-100 shadow-sm border-0">
            <!-- kode ini untuk menampilkan gambar kamar -->
            <img src="uploads/rooms/<?php echo htmlspecialchars($room['main_image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($room['nama_room']); ?>" style="height: 200px; object-fit: cover;">
            
            <div class="card-body">
              <!-- kode ini untuk menampilkan nama kamar -->
              <h5 class="card-title fw-bold"><?php echo htmlspecialchars($room['nama_room']); ?></h5>
              
              <!-- kode ini untuk menampilkan harga dalam format Rupiah -->
              <p class="card-text text-primary fw-bold"><?php echo $harga_rupiah; ?> / malam</p>
              
              <!-- kode ini untuk menampilkan kapasitas kamar -->
              <p class="card-text text-muted small">
                <i class="bi bi-people"></i> Kapasitas: <?php echo htmlspecialchars($room['kapasitas']); ?> orang
              </p>
              
              <!-- kode ini untuk menampilkan potongan deskripsi -->
              <p class="card-text small"><?php echo htmlspecialchars($deskripsi_singkat); ?></p>
            </div>
            
            <div class="card-footer bg-white border-0">
              <!-- kode ini untuk tombol detail kamar yang mengarah ke halaman detail -->
              <a href="detail.php?id=<?php echo htmlspecialchars($room['id_room']); ?>" class="btn btn-main btn-sm w-100">
                Detail Kamar
              </a>
            </div>
          </div>
        </div>
        <?php
        }
        ?>
      </div>
      <?php
      } else {
        // kode ini untuk tampilkan pesan jika tidak ada kamar tersedia
      ?>
      <div class="alert alert-info text-center">
        <p class="mb-0">Belum ada kamar tersedia.</p>
      </div>
      <?php
      }
      ?>
    </div>
  </section>

  <!-- Location Section -->
  <section class="py-5 bg-light">
    <div class="container">
      <div class="row align-items-center g-4">
        <div class="col-md-6">
          <h2 class="fw-bold">Lokasi Kami</h2>
          <p>
            Shanti Asih Homestay berlokasi di Jalan Gotama Selatan No. 25,
            Desa Padang Tegal, Kecamatan Ubud, Kabupaten Gianyar, Bali, Indonesia.
          </p>
          <p>
            Lokasi ini cocok untuk tamu yang ingin menikmati suasana Ubud yang tenang,
            dekat dengan budaya lokal, dan nyaman untuk beristirahat.
          </p>
        </div>

        <div class="col-md-6">
          <div class="location-box p-4 rounded shadow-sm">
            <h5 class="fw-bold">Alamat</h5>
            <p class="mb-2">
              Jalan Gotama Selatan No. 25, Desa Padang Tegal, Kecamatan Ubud,
              Kabupaten Gianyar, Provinsi Bali, Indonesia.
            </p>
            <a href="https://www.google.com/maps/place/Shanty+asih+homestay/@-8.5115536,115.2638381,17z/data=!3m1!4b1!4m6!3m5!1s0x2dd23d003fb1e1c1:0xa6a39ba1c5813c9a!8m2!3d-8.5115536!4d115.2638381!16s%2Fg%2F11y5n9hhfb?entry=ttu&g_ep=EgoyMDI2MDQyMi4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="btn btn-main">
              Lihat di Google Maps
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>


    <!-- FAQ Section -->
  <section class="py-5">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold">Frequently Asked Questions</h2>
        <p class="text-muted">Pertanyaan yang sering diajukan oleh tamu Shanti Asih Homestay.</p>
      </div>

      <div class="accordion" id="faqAccordion">

        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
              Jam check-in dan check-out?
            </button>
          </h2>
          <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Check-in mulai pukul 14.00 WITA dan check-out maksimal pukul 12.00 WITA.
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
              Apakah tersedia WiFi?
            </button>
          </h2>
          <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Ya, seluruh area homestay dilengkapi akses WiFi gratis untuk tamu.
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
              Apakah tersedia area parkir?
            </button>
          </h2>
          <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Ya, tersedia area parkir yang dapat digunakan oleh tamu selama menginap.
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
              Bagaimana cara melakukan booking?
            </button>
          </h2>
          <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Pilih kamar yang tersedia, buka detail kamar, isi formulir booking, lalu tunggu konfirmasi dari admin.
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- Contact Section -->
  <section class="py-5 bg-light">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold">Hubungi Kami</h2>
        <p class="text-muted">Kami siap membantu kebutuhan menginap Anda.</p>
      </div>

      <div class="row g-4">

        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
              <h5 class="fw-bold">Alamat</h5>
              <p>
                Jalan Gotama Selatan No.25<br>
                Ubud, Gianyar, Bali
              </p>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
              <h5 class="fw-bold">WhatsApp</h5>
              <p>+62 819 376 226 96</p>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
              <h5 class="fw-bold">Email</h5>
              <p>info@shantiasihhomestay.com</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="py-5 text-center">
    <div class="container">
      <h2 class="fw-bold">Ingin Menginap di Ubud?</h2>
      <p class="mb-4">Lihat pilihan kamar dan lakukan simulasi booking sekarang.</p>
      <a href="rooms.php" class="btn btn-main btn-lg">Booking Sekarang</a>
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