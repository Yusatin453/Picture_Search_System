<?php
session_start();
include 'koneksi.php'; // Sesuaikan path jika perlu

$email = $_POST['email'];
$password = $_POST['password'];

// Ambil data user dari database
$stmt = $koneksi->prepare("SELECT id, nama, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    // Verifikasi password
    if (password_verify($password, $user['password'])) {
        // Jika berhasil, simpan info ke session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nama'] = $user['nama'];
        $_SESSION['logged_in'] = true;
        
        // Arahkan ke halaman utama
        header("Location: pagefirst.php");
        exit();
    }
}

// Jika gagal, kembali ke login dengan pesan error
header("Location: page1.php?error=login_gagal");
exit();
?>