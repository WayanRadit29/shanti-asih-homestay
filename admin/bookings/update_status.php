<?php
// kode ini untuk proteksi hanya admin yang bisa akses halaman ini
include "../../auth/check_admin.php";

// kode ini untuk menghubungkan database
include "../../config/koneksi.php";

// kode ini untuk inisialisasi variabel
$error = null;
$success = null;

// kode ini untuk handle POST dari form update status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // kode ini untuk ambil data dari form
    $id_booking = (int)($_POST['id_booking'] ?? 0);
    $status = trim($_POST['status'] ?? '');

    // kode ini untuk validasi id_booking harus ada
    if ($id_booking === 0) {
        $error = "ID Booking tidak valid.";
    }
    // kode ini untuk validasi status hanya boleh nilai tertentu
    elseif (!in_array($status, ['pending', 'confirmed', 'cancelled', 'completed'])) {
        $error = "Status tidak valid.";
    }
    else {
        // kode ini untuk update status booking menggunakan prepared statement
        $stmt = mysqli_prepare($conn, "UPDATE bookings SET status = ? WHERE id_booking = ?");
        mysqli_stmt_bind_param($stmt, "si", $status, $id_booking);

        // kode ini untuk eksekusi query
        if (mysqli_stmt_execute($stmt)) {
            $success = "Status booking berhasil diperbarui.";
            // kode ini untuk redirect ke index.php dengan parameter sukses
            header("Location: index.php?message=success");
            exit;
        } else {
            $error = "Terjadi kesalahan saat mengupdate status: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    }
}

// kode ini jika ada error, redirect kembali dengan pesan error
if ($error) {
    header("Location: index.php?message=error&text=" . urlencode($error));
    exit;
}
?>
