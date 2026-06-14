<?php
// Include check_admin.php untuk memastikan hanya admin yang bisa akses
include '../../auth/check_admin.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Shanti Asih Homestay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 20px;
            color: white !important;
        }

        .dashboard-container {
            margin-top: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .dashboard-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 40px;
            text-align: center;
        }

        .dashboard-title {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin-bottom: 30px;
        }

        .info-item {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: left;
        }

        .info-label {
            font-size: 14px;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 20px;
            color: #333;
            font-weight: 700;
        }

        .btn-logout {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 600;
            margin-top: 20px;
            width: 100%;
            transition: transform 0.2s;
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-logout:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <span class="navbar-brand">🏠 Shanti Asih Homestay - Admin</span>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="dashboard-container">
        <div class="dashboard-card">
            <h1 class="dashboard-title">📊 Dashboard Admin</h1>

            <!-- Nama User -->
            <div class="info-item">
                <div class="info-label">Nama Pengguna</div>
                <div class="info-value"><?php echo htmlspecialchars($_SESSION['nama']); ?></div>
            </div>

            <!-- Role User -->
            <div class="info-item">
                <div class="info-label">Role</div>
                <div class="info-value"><?php echo htmlspecialchars($_SESSION['role']); ?></div>
            </div>

            <!-- User ID -->
            <div class="info-item">
                <div class="info-label">User ID</div>
                <div class="info-value"><?php echo htmlspecialchars($_SESSION['user_id']); ?></div>
            </div>

            <!-- Logout Button -->
            <a href="../../logout.php" class="btn btn-primary btn-logout">
                🚪 Logout
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
