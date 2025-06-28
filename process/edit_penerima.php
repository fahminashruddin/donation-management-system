<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_penerima'];
    $nama = $_POST['nama_penerima'];
    $alamat = $_POST['alamat'];

    $query = "UPDATE penerima SET nama_penerima = ?, alamat = ? WHERE id_penerima = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssi', $nama, $alamat, $id);

    if ($stmt->execute()) {
        header("Location: ../penerima.php?status=success&message=Data penerima berhasil diperbarui.");
    } else {
        header("Location: ../penerima.php?status=error&message=Gagal memperbarui data penerima.");
    }

    $stmt->close();
} else {
    header("Location: ../penerima.php?status=error&message=Akses tidak diizinkan.");
}
$conn->close();
?>
