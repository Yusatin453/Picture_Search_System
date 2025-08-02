<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: page1.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload Gambar</title>
    </head>
<body>
    <h2>Upload Gambar Baru</h2>
    <form action="proses_upload.php" method="post" enctype="multipart/form-data">
        <label>Pilih Gambar:</label>
        <input type="file" name="gambar" required>
        <br>
        <label>Judul Gambar:</label>
        <input type="text" name="judul" required>
        <br>
        <button type="submit">Upload</button>
    </form>
</body>
</html>