<?php
// kode ini untuk memulai session
session_start();

// kode ini untuk menghubungkan database
include '../config/koneksi.php';

// kode ini untuk proteksi - user harus login terlebih dahulu
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// kode ini untuk validasi id_booking dari URL harus integer
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Akses ditolak atau data booking tidak ditemukan.");
}

$id_booking = (int)$_GET['id'];

// kode ini untuk ambil data booking dengan JOIN ke tabel users dan rooms menggunakan prepared statement
$query = "SELECT 
            b.id_booking,
            b.user_id,
            u.nama,
            u.email,
            u.no_hp,
            r.nama_room,
            b.check_in,
            b.check_out,
            b.jumlah_tamu,
            b.total_harga,
            b.status,
            b.catatan,
            b.created_at
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN rooms r ON b.room_id = r.id_room
        WHERE b.id_booking = ?";

// kode ini untuk prepared statement
$stmt = mysqli_prepare($conn, $query);

if (!$stmt) {
    die("Akses ditolak atau data booking tidak ditemukan.");
}

mysqli_stmt_bind_param($stmt, "i", $id_booking);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// kode ini untuk cek apakah booking ditemukan
if (mysqli_num_rows($result) === 0) {
    mysqli_stmt_close($stmt);
    die("Akses ditolak atau data booking tidak ditemukan.");
}

$booking = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// kode ini untuk validasi hak akses - user hanya bisa mencetak booking miliknya sendiri, admin bisa semua
if ($_SESSION['role'] === 'user' && $booking['user_id'] !== $_SESSION['user_id']) {
    die("Akses ditolak atau data booking tidak ditemukan.");
}

// kode ini jika akses valid, lanjut ke generation PDF
require_once __DIR__ . "/../vendor/autoload.php";

use Dompdf\Dompdf;

// Format tanggal
$check_in = date('d-m-Y', strtotime($booking['check_in']));
$check_out = date('d-m-Y', strtotime($booking['check_out']));
$created_at = date('d-m-Y H:i', strtotime($booking['created_at']));

// Hitung durasi menginap
$date1 = new DateTime($booking['check_in']);
$date2 = new DateTime($booking['check_out']);
$interval = $date1->diff($date2);
$durasi = $interval->days;

// Data kontak homestay
$homestay_address = "Jalan Gotama Selatan No. 25, Ubud, Gianyar, Bali";
$homestay_phone = "+62 819 376 226 96";
$homestay_email = "info@shantiasihhomestay.com";


// HTML Invoice
$html = "
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 12px;
        }
        .invoice-number {
            text-align: right;
            margin-bottom: 20px;
            font-size: 12px;
        }
        .invoice-number strong {
            color: #2c3e50;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            background-color: #2c3e50;
            color: white;
            padding: 10px 15px;
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 12px;
        }
        .section-content {
            padding: 0 15px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #ecf0f1;
            font-size: 13px;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: bold;
            color: #2c3e50;
            width: 35%;
        }
        .info-value {
            width: 65%;
            text-align: right;
        }
        .two-columns {
            display: flex;
            gap: 30px;
            margin-bottom: 20px;
        }
        .column {
            flex: 1;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 13px;
        }
        .table th {
            background-color: #34495e;
            color: white;
            padding: 10px;
            text-align: left;
            border: 1px solid #2c3e50;
        }
        .table td {
            padding: 10px;
            border: 1px solid #ecf0f1;
        }
        .table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .total-section {
            margin-top: 20px;
            padding: 15px;
            background-color: #ecf0f1;
            border-left: 4px solid #2c3e50;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
        }
        .total-row.grand-total {
            border-top: 2px solid #2c3e50;
            border-bottom: 2px solid #2c3e50;
            padding-top: 12px;
            padding-bottom: 12px;
            margin-top: 10px;
            font-weight: bold;
            font-size: 16px;
            color: #2c3e50;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
        }
        .status-pending {
            background-color: #f39c12;
            color: white;
        }
        .status-confirmed {
            background-color: #27ae60;
            color: white;
        }
        .status-cancelled {
            background-color: #e74c3c;
            color: white;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ecf0f1;
            text-align: center;
            font-size: 11px;
            color: #7f8c8d;
        }
        .catatan-box {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 4px;
            padding: 12px;
            margin-top: 12px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class='container'>
        <!-- Header -->
        <div class='header'>
            <h1>Bukti Booking Shanti Asih Homestay</h1>
            <p>" . htmlspecialchars($homestay_address) . " | Telp/WA: " . htmlspecialchars($homestay_phone) . "</p>
            <p>Email: " . htmlspecialchars($homestay_email) . "</p>
        </div>

        <!-- Invoice Number -->
        <div class='invoice-number'>
            <strong>No. Booking:</strong> " . htmlspecialchars($booking['id_booking']) . " | 
            <strong>Tanggal:</strong> " . $created_at . "
        </div>

        <!-- Informasi Pemesan -->
        <div class='section'>
            <div class='section-title'>Informasi Pemesan</div>
            <div class='section-content'>
                <div class='info-row'>
                    <div class='info-label'>Nama</div>
                    <div class='info-value'>" . htmlspecialchars($booking['nama']) . "</div>
                </div>
                <div class='info-row'>
                    <div class='info-label'>Email</div>
                    <div class='info-value'>" . htmlspecialchars($booking['email']) . "</div>
                </div>
                <div class='info-row'>
                    <div class='info-label'>No. Telepon</div>
                    <div class='info-value'>" . htmlspecialchars($booking['no_hp']) . "</div>
                </div>
            </div>
        </div>

        <!-- Informasi Booking -->
        <div class='section'>
            <div class='section-title'>Informasi Booking</div>
            <div class='section-content'>
                <div class='info-row'>
                    <div class='info-label'>Nama Kamar</div>
                    <div class='info-value'>" . htmlspecialchars($booking['nama_room']) . "</div>
                </div>
                <div class='info-row'>
                    <div class='info-label'>Check-in</div>
                    <div class='info-value'>" . $check_in . "</div>
                </div>
                <div class='info-row'>
                    <div class='info-label'>Check-out</div>
                    <div class='info-value'>" . $check_out . "</div>
                </div>
                <div class='info-row'>
                    <div class='info-label'>Durasi Menginap</div>
                    <div class='info-value'>" . $durasi . " malam</div>
                </div>
                <div class='info-row'>
                    <div class='info-label'>Jumlah Tamu</div>
                    <div class='info-value'>" . htmlspecialchars($booking['jumlah_tamu']) . " orang</div>
                </div>
            </div>
        </div>

        <!-- Detail Pembayaran -->
        <div class='section'>
            <div class='section-title'>Detail Pembayaran</div>
            <div class='section-content'>
                <table class='table'>
                    <tr>
                        <th style='width: 60%'>Deskripsi</th>
                        <th style='text-align: right;'>Jumlah</th>
                    </tr>
                    <tr>
                        <td>" . htmlspecialchars($booking['nama_room']) . " x " . $durasi . " malam</td>
                        <td style='text-align: right;'>Rp " . number_format($booking['total_harga'], 0, ',', '.') . "</td>
                    </tr>
                </table>

                <div class='total-section'>
                    <div class='total-row grand-total'>
                        <span>Total Pembayaran:</span>
                        <span>Rp " . number_format($booking['total_harga'], 0, ',', '.') . "</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Booking -->
        <div class='section'>
            <div class='section-title'>Status Booking</div>
            <div class='section-content' style='padding: 15px;'>
                <span class='status-badge status-" . strtolower($booking['status']) . "'>" . ucfirst($booking['status']) . "</span>";

if ($booking['catatan']) {
    $html .= "
                <div class='catatan-box'>
                    <strong>Catatan:</strong><br>
                    " . htmlspecialchars($booking['catatan']) . "
                </div>";
}

$html .= "
            </div>
        </div>

        <!-- Footer -->
        <div class='footer'>
            <p>Bukti booking ini telah dibuat pada " . date('d-m-Y H:i:s') . "</p>
            <p>Terima kasih telah memilih Shanti Asih Homestay.</p>
            <p>Untuk pertanyaan lebih lanjut, hubungi Telp/WA: " . htmlspecialchars($homestay_phone) . " atau Email: " . htmlspecialchars($homestay_email) . "</p>
        </div>
    </div>
</body>
</html>
";

// Inisialisasi DOMPDF
$dompdf = new Dompdf();

// Load HTML
$dompdf->loadHtml($html);

// Set paper size (A4 portrait)
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Stream PDF ke browser
$filename = 'bukti-booking-' . $booking['id_booking'] . '.pdf';
$dompdf->stream($filename, array('Attachment' => false));

$conn->close();
?>
