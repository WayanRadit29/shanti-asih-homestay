<?php
include "config/koneksi.php";

$message = "";
$message_type = "info";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nama = htmlspecialchars(trim($_POST['nama']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $no_hp = htmlspecialchars(trim($_POST['no_hp']));

    if ($password !== $confirm_password) {
        $message = "Konfirmasi password tidak sama!";
        $message_type = "danger";
    } elseif (strlen($password) < 8) {
        $message = "Password minimal 8 karakter!";
        $message_type = "danger";
    } else {

        $stmt_check = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt_check, "s", $email);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);

        if (mysqli_num_rows($result_check) > 0) {
            $message = "Email sudah digunakan!";
            $message_type = "danger";
        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = "user";

            $stmt_insert = mysqli_prepare(
                $conn,
                "INSERT INTO users (nama, email, password, no_hp, role) VALUES (?, ?, ?, ?, ?)"
            );

            mysqli_stmt_bind_param(
                $stmt_insert,
                "sssss",
                $nama,
                $email,
                $hashed_password,
                $no_hp,
                $role
            );

            if (mysqli_stmt_execute($stmt_insert)) {
                $message = "Registrasi berhasil! Silakan login untuk melanjutkan.";
                $message_type = "success";
            } else {
                $message = "Registrasi gagal!";
                $message_type = "danger";
            }

            mysqli_stmt_close($stmt_insert);
        }

        mysqli_stmt_close($stmt_check);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - Shanti Asih Homestay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
            background:
                linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.45)),
                url("assets/images/homestay-hero.jpg");
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .register-container {
            width: 100%;
            max-width: 560px;
        }

        .register-card {
            background: #ffffff;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 20px 45px rgba(0,0,0,0.22);
        }

        .register-header {
            background: linear-gradient(135deg, var(--primary-green), var(--warm-brown));
            color: white;
            text-align: center;
            padding: 34px 30px;
        }

        .register-header h2 {
            margin-bottom: 8px;
            font-weight: 800;
        }

        .register-header p {
            margin-bottom: 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .register-body {
            padding: 35px;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
        }

        .form-control {
            border-radius: 12px;
            padding: 12px;
            border: 1.5px solid #ddd;
        }

        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.15rem rgba(95,122,58,.2);
        }

        .btn-register {
            background: var(--primary-green);
            border: none;
            color: white;
            font-weight: 700;
            padding: 12px;
            border-radius: 999px;
            transition: 0.25s;
        }

        .btn-register:hover {
            background: var(--primary-green-dark);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(95,122,58,0.32);
        }

        .register-footer {
            background: var(--cream-bg);
            text-align: center;
            padding: 20px;
            border-top: 1px solid #eee;
            color: var(--muted-text);
            font-size: 14px;
        }

        .register-footer a {
            color: var(--primary-green);
            text-decoration: none;
            font-weight: 700;
        }

        .register-footer a:hover {
            color: var(--primary-green-dark);
        }

        .back-home {
            text-align: center;
            margin-top: 18px;
        }

        .back-home a {
            color: white;
            text-decoration: none;
            font-weight: 600;
        }

        .back-home a:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 22px;
        }

        @media (max-width: 576px) {
            .register-body {
                padding: 28px 22px;
            }

            .register-header {
                padding: 30px 22px;
            }
        }
    </style>
</head>

<body>

    <div class="register-container">

        <div class="register-card">

            <div class="register-header">
                <h2>Shanti Asih</h2>
                <p>Buat akun untuk mulai melakukan booking homestay.</p>
            </div>

            <div class="register-body">

                <?php if (!empty($message)) : ?>
                    <div class="alert alert-<?= htmlspecialchars($message_type); ?>">
                        <?= htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <form method="POST">

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input
                            type="text"
                            name="nama"
                            class="form-control"
                            value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : ''; ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nomor HP</label>
                        <input
                            type="text"
                            name="no_hp"
                            class="form-control"
                            value="<?= isset($_POST['no_hp']) ? htmlspecialchars($_POST['no_hp']) : ''; ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Konfirmasi Password</label>
                        <input
                            type="password"
                            name="confirm_password"
                            class="form-control"
                            required>
                    </div>

                    <button
                        type="submit"
                        class="btn btn-register w-100">
                        Buat Akun
                    </button>

                </form>

            </div>

            <div class="register-footer">
                Sudah punya akun?
                <a href="login.php">Login di sini</a>
            </div>

        </div>

        <div class="back-home">
            <a href="index.php">← Kembali ke Beranda</a>
        </div>

    </div>

</body>
</html>