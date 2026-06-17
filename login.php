<?php
session_start();

// Jika user sudah login, redirect ke dashboard sesuai role
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/dashboard/index.php");
    } else {
        header("Location: user/dashboard.php");
    }
    exit();
}

// Include database connection
include 'config/koneksi.php';

$error = '';
$email = '';

// Proses login jika form di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validasi input tidak kosong
    if (empty($email) || empty($password)) {
        $error = 'Email dan password tidak boleh kosong!';
    } else {
        // Query untuk mencari user berdasarkan email
        $query = "SELECT id, nama, email, password, role FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();

                // Verifikasi password menggunakan password_verify
                if (password_verify($password, $user['password'])) {
                    session_regenerate_id(true);

                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['nama'] = $user['nama'];
                    $_SESSION['role'] = $user['role'];

                    if ($user['role'] === 'admin') {
                        header("Location: admin/dashboard/index.php");
                    } else {
                        header("Location: user/dashboard.php");
                    }
                    exit();
                } else {
                    $error = 'Email atau password salah!';
                }
            } else {
                $error = 'Email atau password salah!';
            }

            $stmt->close();
        } else {
            $error = 'Terjadi kesalahan pada server!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Shanti Asih Homestay</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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
                linear-gradient(rgba(0, 0, 0, 0.48), rgba(0, 0, 0, 0.48)),
                url("assets/images/homestay-hero.jpg");
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            padding: 24px;
        }

        .login-container {
            width: 100%;
            max-width: 460px;
        }

        .login-card {
            background: #ffffff;
            border-radius: 22px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.22);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--warm-brown) 100%);
            color: white;
            padding: 34px 30px;
            text-align: center;
        }

        .login-header h1 {
            font-size: 28px;
            margin: 0;
            font-weight: 800;
        }

        .login-header p {
            margin: 8px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .login-body {
            padding: 38px 32px;
        }

        .form-floating {
            margin-bottom: 18px;
        }

        .form-floating .form-control {
            border: 1.5px solid #e3e0d8;
            border-radius: 14px;
            height: 58px;
            font-size: 15px;
        }

        .form-floating .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.18rem rgba(95, 122, 58, 0.18);
        }

        .form-floating > label {
            color: var(--muted-text);
        }

        .btn-login {
            background: var(--primary-green);
            border: none;
            border-radius: 999px;
            padding: 13px;
            font-size: 16px;
            font-weight: 700;
            width: 100%;
            margin-top: 10px;
            color: white;
            transition: 0.25s;
        }

        .btn-login:hover {
            background: var(--primary-green-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(95, 122, 58, 0.32);
            color: white;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert {
            border: none;
            border-radius: 14px;
            margin-bottom: 24px;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .login-footer {
            text-align: center;
            padding: 22px 30px;
            background-color: var(--cream-bg);
            border-top: 1px solid #e3e0d8;
        }

        .login-footer p {
            margin: 0;
            font-size: 14px;
            color: var(--muted-text);
        }

        .login-footer a {
            color: var(--primary-green);
            text-decoration: none;
            font-weight: 700;
        }

        .login-footer a:hover {
            color: var(--primary-green-dark);
        }

        .back-home {
            text-align: center;
            margin-top: 18px;
        }

        .back-home a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 600;
        }

        .back-home a:hover {
            text-decoration: underline;
        }

        @media (max-width: 576px) {
            .login-body {
                padding: 30px 24px;
            }

            .login-header {
                padding: 30px 24px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>Shanti Asih</h1>
                <p>Masuk ke akun Anda untuk melakukan booking homestay.</p>
            </div>

            <div class="login-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" novalidate>
                    <div class="form-floating">
                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            placeholder="Masukkan email Anda"
                            value="<?php echo htmlspecialchars($email); ?>"
                            required
                        >
                        <label for="email">Email Address</label>
                    </div>

                    <div class="form-floating">
                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            placeholder="Masukkan password Anda"
                            required
                        >
                        <label for="password">Password</label>
                    </div>

                    <button type="submit" class="btn btn-login">
                        Login
                    </button>
                </form>
            </div>

            <div class="login-footer">
                <p>
                    Belum punya akun? <a href="register.php">Daftar di sini</a>
                </p>
            </div>
        </div>

        <div class="back-home">
            <a href="index.php">← Kembali ke Beranda</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const alerts = document.querySelectorAll('.alert');

        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    </script>
</body>
</html>