<?php
include "config/koneksi.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nama = htmlspecialchars($_POST['nama']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $no_hp = htmlspecialchars($_POST['no_hp']);

    // validasi password
    if ($password !== $confirm_password) {
        $message = "Konfirmasi password tidak sama!";
    }

    elseif (strlen($password) < 8) {
        $message = "Password minimal 8 karakter!";
    }

    else {

        // cek email sudah ada atau belum
        $check_email = mysqli_query(
            $conn,
            "SELECT * FROM users WHERE email='$email'"
        );

        if (mysqli_num_rows($check_email) > 0) {
            $message = "Email sudah digunakan!";
        }

        else {

            // hash password
            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $query = "
                INSERT INTO users
                (
                    nama,
                    email,
                    password,
                    no_hp,
                    role
                )
                VALUES
                (
                    '$nama',
                    '$email',
                    '$hashed_password',
                    '$no_hp',
                    'user'
                )
            ";

            if (mysqli_query($conn, $query)) {
                $message = "Registrasi berhasil!";
            } else {
                $message = "Registrasi gagal!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-body p-4">

                    <h2 class="text-center mb-4">
                        Registrasi Akun
                    </h2>

                    <?php if (!empty($message)) : ?>

                        <div class="alert alert-info">
                            <?= $message ?>
                        </div>

                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label>Nama Lengkap</label>
                            <input
                                type="text"
                                name="nama"
                                class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label>Nomor HP</label>
                            <input
                                type="text"
                                name="no_hp"
                                class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label>Password</label>
                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label>Konfirmasi Password</label>
                            <input
                                type="password"
                                name="confirm_password"
                                class="form-control"
                                required>
                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary w-100">
                            Register
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>