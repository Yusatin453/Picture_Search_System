<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $judul = $_POST['judul'];

    // Info file gambar
    $nama_file = $_FILES['gambar']['name'];
    $tmp_file = $_FILES['gambar']['tmp_name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($nama_file);

    // Pindahkan file yang diunggah ke folder 'uploads'
    if (move_uploaded_file($tmp_file, $target_file)) {
        
        // Simpan path dan info ke database
        $stmt = $koneksi->prepare("INSERT INTO images (user_id, judul_gambar, path_file) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $judul, $target_file);
        
        if ($stmt->execute()) {
            echo "Gambar berhasil di-upload.";
            // Arahkan kembali ke halaman utama
            header("Location: pagefirst.php");
            exit();
        } else {
            echo "Gagal menyimpan info ke database.";
        }
    } else {
        echo "Gagal meng-upload file.";
    }
}
?>