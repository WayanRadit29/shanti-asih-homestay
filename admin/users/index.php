<?php
include '../../auth/check_admin.php';
include '../../config/koneksi.php';

$query = "SELECT * FROM users ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

$users = mysqli_fetch_all($result, MYSQLI_ASSOC);
$total_users = count($users);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management User - Admin Shanti Asih Homestay</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-green: #5f7a3a;
            --primary-green-dark: #4f6530;
            --warm-brown: #8b6f47;
            --cream-bg: #f5f1e8;
            --text-dark: #2f2f2f;
            --muted-text: #6c757d;
        }

        body {
            background-color: var(--cream-bg);
            color: var(--text-dark);
            min-height: 100vh;
        }

        .navbar {
            box-shadow: 0 2px 12px rgba(47, 47, 47, 0.1);
        }

        .navbar-brand {
            color: var(--primary-green) !important;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--warm-brown) 100%);
            color: #ffffff;
            padding: 34px 0;
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-weight: 800;
        }

        .content-card {
            background-color: #ffffff;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 10px 24px rgba(47, 47, 47, 0.08);
        }

        .section-title {
            color: var(--text-dark);
            padding-bottom: 12px;
            border-bottom: 3px solid var(--primary-green);
        }

        .btn-main {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
            color: #ffffff;
            border-radius: 999px;
            font-weight: 600;
        }

        .btn-main:hover {
            background-color: var(--primary-green-dark);
            border-color: var(--primary-green-dark);
            color: #ffffff;
        }

        .btn-outline-main {
            border: 1px solid var(--primary-green);
            color: var(--primary-green);
            border-radius: 999px;
            font-weight: 600;
        }

        .btn-outline-main:hover {
            background-color: var(--primary-green);
            color: #ffffff;
        }

        .table thead th {
            background-color: #e8eadf;
            color: var(--text-dark);
            white-space: nowrap;
        }

        .table tbody td {
            vertical-align: middle;
            white-space: nowrap;
        }

        .badge {
            border-radius: 999px;
            padding: 8px 10px;
            font-weight: 600;
        }

        .badge-admin {
            background-color: #8b6f47;
            color: #ffffff;
        }

        .badge-user {
            background-color: #5f7a3a;
            color: #ffffff;
        }

        .empty-message {
            background-color: #f8f5ef;
            border-left: 4px solid var(--primary-green);
            border-radius: 12px;
            padding: 18px;
            color: var(--text-dark);
        }

        @media (max-width: 768px) {
            .page-header {
                padding: 24px 0;
            }

            .content-card {
                padding: 18px;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-white">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="../dashboard/index.php">
                Admin - Shanti Asih
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../dashboard/index.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../rooms/index.php">Kamar</a></li>
                    <li class="nav-item"><a class="nav-link" href="../bookings/index.php">Booking</a></li>
                    <li class="nav-item"><a class="nav-link active" href="../users/index.php">User</a></li>
                    <li class="nav-item"><a class="nav-link" href="../../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="page-header">
        <div class="container-fluid">
            <h1 class="mb-1">Manajemen User</h1>
            <p class="mb-0 text-white-50">Pantau akun admin dan user yang terdaftar di sistem.</p>
        </div>
    </section>

    <main class="container-fluid pb-5">
        <div class="mb-3">
            <a href="../dashboard/index.php" class="btn btn-outline-main">
                &larr; Kembali ke Dashboard
            </a>
        </div>

        <div class="content-card">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                <h5 class="fw-bold section-title mb-0">
                    Daftar User
                </h5>

                <span class="badge bg-light text-dark border">
                    Total: <?= htmlspecialchars($total_users); ?> akun
                </span>
            </div>

            <?php if(count($users) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead>
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
                                        <strong><?= htmlspecialchars($user['id']) ?></strong>
                                    </td>

                                    <td class="fw-semibold">
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
                                            <span class="badge badge-admin">
                                                Admin
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-user">
                                                User
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(date('d/m/Y H:i', strtotime($user['created_at']))) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-message">
                    Belum ada user terdaftar.
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>