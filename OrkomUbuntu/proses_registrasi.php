<?php
include 'koneksi.php'; // Hubungkan ke database

// Ambil data dari form
$nama = $_POST['nama'];
$email = $_POST['email'];
$password_plain = $_POST['password'];

// Validasi dasar (bisa Anda kembangkan lebih lanjut)
if (empty($nama) || empty($email) || empty($password_plain)) {
    die("Harap isi semua kolom.");
}

// Enkripsi password menggunakan metode yang aman
$password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

// Gunakan prepared statement untuk menyimpan data agar aman dari SQL Injection
$stmt = $koneksi->prepare("INSERT INTO users (nama, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nama, $email, $password_hashed);

// Eksekusi query
if ($stmt->execute()) {
    // Jika berhasil, arahkan ke halaman login
    header("Location: page1.php?status=sukses_registrasi");
    exit();
} else {
    // Jika gagal (misalnya email sudah ada), tampilkan error
    echo "Error: " . $stmt->error;
}

$stmt->close();
$koneksi->close();
?>