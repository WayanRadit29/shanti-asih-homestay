<?php
include '../../auth/check_admin.php';
include "../../config/koneksi.php";

// kode ini untuk validasi id kamar
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = mysqli_prepare($conn, "SELECT * FROM rooms WHERE id_room = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$room = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$room) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kamar - Shanti Asih Homestay</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-green: #5f7a3a;
            --primary-green-dark: #4f6530;
            --warm-brown: #8b6f47;
            --cream-bg: #f5f1e8;
        }

        body {
            background-color: #f8f9fa;
        }

        .page-header {
            background: linear-gradient(
                135deg,
                var(--primary-green),
                var(--warm-brown)
            );
            color: white;
            padding: 50px 0;
            margin-bottom: 40px;
        }

        .page-header h1 {
            font-weight: 800;
            margin-bottom: 8px;
        }

        .page-header p {
            margin-bottom: 0;
            opacity: 0.9;
        }

        .form-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .form-card-header {
            background-color: var(--cream-bg);
            padding: 20px 30px;
            border-bottom: 1px solid #e9ecef;
        }

        .form-card-body {
            padding: 30px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            padding: 12px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.15rem rgba(95,122,58,.2);
        }

        .btn-update {
            background-color: var(--primary-green);
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 999px;
            padding: 12px 24px;
        }

        .btn-update:hover {
            background-color: var(--primary-green-dark);
            color: white;
        }

        .btn-back {
            border-radius: 999px;
            padding: 12px 24px;
        }

        .current-image,
        .preview-image {
            width: 100%;
            max-width: 320px;
            height: 220px;
            object-fit: cover;
            border-radius: 14px;
            border: 1px solid #ddd;
        }

        .preview-container {
            display: none;
            margin-top: 15px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #666;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
</head>

<body>

<section class="page-header">
    <div class="container">
        <h1>Edit Kamar</h1>
        <p>Perbarui informasi kamar Shanti Asih Homestay.</p>
    </div>
</section>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card form-card">
                <div class="form-card-header">
                    <h4 class="fw-bold mb-0">
                        Informasi Kamar
                    </h4>
                </div>

                <div class="form-card-body">
                    <form action="update.php" method="POST" enctype="multipart/form-data">

                        <input
                            type="hidden"
                            name="id_room"
                            value="<?= htmlspecialchars($room['id_room']); ?>">

                        <input
                            type="hidden"
                            name="old_image"
                            value="<?= htmlspecialchars($room['main_image']); ?>">

                        <div class="mb-3">
                            <label class="form-label">
                                Nama Kamar
                            </label>

                            <input
                                type="text"
                                name="nama_room"
                                class="form-control"
                                value="<?= htmlspecialchars($room['nama_room']); ?>"
                                required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Harga per Malam
                                </label>

                                <input
                                    type="number"
                                    name="harga"
                                    class="form-control"
                                    value="<?= htmlspecialchars($room['harga']); ?>"
                                    required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Kapasitas
                                </label>

                                <input
                                    type="number"
                                    name="kapasitas"
                                    class="form-control"
                                    value="<?= htmlspecialchars($room['kapasitas']); ?>"
                                    required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Deskripsi Kamar
                            </label>

                            <textarea
                                name="deskripsi"
                                rows="5"
                                class="form-control"><?= htmlspecialchars($room['deskripsi']); ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">
                                Status Kamar
                            </label>

                            <select name="status" class="form-select">
                                <option
                                    value="available"
                                    <?= $room['status'] == 'available' ? 'selected' : ''; ?>>
                                    Available
                                </option>

                                <option
                                    value="unavailable"
                                    <?= $room['status'] == 'unavailable' ? 'selected' : ''; ?>>
                                    Unavailable
                                </option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <div class="section-title">
                                Gambar Saat Ini
                            </div>

                            <?php if (!empty($room['main_image'])) : ?>
                                <img
                                    src="../../uploads/rooms/<?= htmlspecialchars($room['main_image']); ?>"
                                    class="current-image"
                                    alt="Gambar Kamar">
                            <?php else : ?>
                                <div class="text-muted">
                                    Tidak ada gambar tersedia.
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">
                                Ganti Gambar Kamar
                            </label>

                            <input
                                type="file"
                                name="main_image"
                                id="imageInput"
                                class="form-control"
                                accept="image/*">

                            <small class="text-muted">
                                Kosongkan jika tidak ingin mengganti gambar.
                            </small>

                            <div class="preview-container" id="previewContainer">
                                <div class="section-title mt-3">
                                    Preview Gambar Baru
                                </div>

                                <img
                                    id="previewImage"
                                    class="preview-image"
                                    alt="Preview Gambar Baru">
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button
                                type="submit"
                                class="btn btn-update">
                                Update Kamar
                            </button>

                            <a
                                href="index.php"
                                class="btn btn-outline-secondary btn-back">
                                ← Kembali
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document
    .getElementById('imageInput')
    .addEventListener('change', function(event) {
        const file = event.target.files[0];

        if (!file) return;

        const reader = new FileReader();

        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
            document.getElementById('previewContainer').style.display = 'block';
        };

        reader.readAsDataURL(file);
    });
</script>

</body>
</html>