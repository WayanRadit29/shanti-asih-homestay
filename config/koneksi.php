<?php

$host = "sql108.infinityfree.com";
$user = "if0_42205597";
$password = "IjQQBQRnpbyN6SS";
$database = "if0_42205597_shanti_asih";

$conn = mysqli_connect(
    $host,
    $user,
    $password,
    $database
);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}