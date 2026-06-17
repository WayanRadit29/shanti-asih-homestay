<?php
include '../../auth/check_admin.php';
include '../../config/koneksi.php';

$query = "SELECT * FROM users ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

$users = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Daftar User</h2>

        <a href="../dashboard/index.php" class="btn btn-secondary">
            Kembali ke Dashboard
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <?php if(count($users) > 0): ?>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-success">
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No HP</th>
                                <th>Role</th>
                                <th>Tanggal Daftar</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php foreach($users as $user): ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars($user['id']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($user['nama']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($user['email']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($user['no_hp']) ?>
                                </td>

                                <td>

                                    <?php if($user['role'] == 'admin'): ?>

                                        <span class="badge bg-danger">
                                            Admin
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-primary">
                                            User
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>
                                    <?= htmlspecialchars($user['created_at']) ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php else: ?>

                <div class="alert alert-info">
                    Belum ada user terdaftar.
                </div>

            <?php endif; ?>

        </div>
    </div>

</div>

</body>
</html>