<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_penerima = mysqli_real_escape_string($conn, $_POST['nama_penerima']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    if (empty($nama_penerima) || empty($alamat)) {
        header("Location: ../penerima.php?status=error&message=Semua bidang wajib diisi.");
        exit;
    }

    $query = "INSERT INTO penerima (nama_penerima, alamat) VALUES ('$nama_penerima', '$alamat')";
    if (mysqli_query($conn, $query)) {
        header("Location: ../penerima.php?status=success&message=Data penerima berhasil ditambahkan.");
    } else {
        header("Location: ../penerima.php?status=error&message=Gagal menambahkan data penerima: " . urlencode(mysqli_error($conn)));
    }
}
?>
