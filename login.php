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

                    session_regenerate_id(true); // Regenerate session ID untuk keamanan
                    // Password benar, set session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['nama'] = $user['nama'];
                    $_SESSION['role'] = $user['role'];

                    // Redirect berdasarkan role
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
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 15px;
        }

        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .login-header h1 {
            font-size: 28px;
            margin: 0;
            font-weight: 700;
        }

        .login-header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .login-body {
            padding: 40px 30px;
        }

        .form-floating .form-control {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 15px;
            height: auto;
            font-size: 16px;
        }

        .form-floating .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .form-floating > label {
            padding: 12px 15px;
            color: #6c757d;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            margin-top: 20px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert {
            border: none;
            border-radius: 8px;
            margin-bottom: 25px;
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

        .form-group {
            margin-bottom: 20px;
        }

        .input-group-text {
            background: transparent;
            border: 2px solid #e9ecef;
            border-right: none;
        }

        .input-group .form-control {
            border-left: none;
        }

        .input-group .form-control:focus {
            border-color: #667eea;
        }

        .input-group .form-control:focus + .input-group-text {
            border-color: #667eea;
        }

        .login-footer {
            text-align: center;
            padding: 20px 30px;
            background-color: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }

        .login-footer p {
            margin: 0;
            font-size: 14px;
            color: #6c757d;
        }

        .login-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .login-footer a:hover {
            color: #764ba2;
        }

        .password-toggle {
            cursor: pointer;
            color: #667eea;
            user-select: none;
        }

        .password-toggle:hover {
            color: #764ba2;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Header -->
            <div class="login-header">
                <h1>🏠 Shanti Asih</h1>
                <p>Homestay Login</p>
            </div>

            <!-- Body -->
            <div class="login-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" novalidate>
                    <!-- Email Input -->
                    <div class="form-floating form-group">
                        <input 
                            type="email" 
                            class="form-control" 
                            id="email" 
                            name="email" 
                            placeholder="Masukkan email Anda" 
                            value="<?php echo htmlspecialchars($email); ?>"
                            required>
                        <label for="email">📧 Email Address</label>
                    </div>

                    <!-- Password Input -->
                    <div class="form-floating form-group">
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password" 
                            name="password" 
                            placeholder="Masukkan password Anda" 
                            required>
                        <label for="password">🔐 Password</label>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="btn btn-primary btn-login">
                        🔓 Login
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="login-footer">
                <p>
                    Belum punya akun? <a href="register.php">Daftar di sini</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Optional: Toggle password visibility
        const passwordInput = document.getElementById('password');
        const togglePasswordBtn = document.createElement('button');
        togglePasswordBtn.type = 'button';
        togglePasswordBtn.className = 'btn btn-sm password-toggle';
        togglePasswordBtn.textContent = '👁️';
        
        // Uncomment untuk enable toggle password
        // passwordInput.parentElement.appendChild(togglePasswordBtn);
        // togglePasswordBtn.addEventListener('click', function(e) {
        //     e.preventDefault();
        //     const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        //     passwordInput.setAttribute('type', type);
        //     this.textContent = type === 'password' ? '👁️' : '🙈';
        // });

        // Auto-dismiss alert after 5 seconds
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
