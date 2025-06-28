<?php
include 'koneksi.php';

// Periksa apakah parameter 'id_donatur' diterima melalui metode GET
if (isset($_GET['id_donatur'])) {
    $id = $_GET['id_donatur'];

    // Validasi apakah ID donatur ada di database
    $cek_query = "SELECT * FROM donatur WHERE id_donatur = ?";
    $stmt = $conn->prepare($cek_query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $cek_result = $stmt->get_result();

    if ($cek_result->num_rows === 0) {
        // Jika ID donatur tidak ditemukan, redirect ke halaman dengan pesan error
        header("Location: ../donatur.php?status=error&message=Donatur tidak ditemukan.");
        exit;
    }

    // Jika validasi berhasil, lakukan penghapusan data
    $query = "DELETE FROM donatur WHERE id_donatur = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect ke halaman donatur dengan pesan sukses
        header("Location: ../donatur.php?status=success&message=Donatur berhasil dihapus.");
    } else {
        // Redirect ke halaman donatur dengan pesan error
        header("Location: ../donatur.php?status=error&message=Gagal menghapus donatur.");
    }

    // Tutup statement
    $stmt->close();
} else {
    // Jika parameter 'id_donatur' tidak ditemukan, redirect ke halaman donatur dengan pesan error
    header("Location: ../donatur.php?status=error&message=ID donatur tidak valid.");
}

// Tutup koneksi ke database
$conn->close();
?>
