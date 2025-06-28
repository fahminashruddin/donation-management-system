<?php
include 'koneksi.php';

if (isset($_GET['id_donasi'])) {
    $id_donasi = trim($_GET['id_donasi']);

    // Hapus data donasi
    $query = "DELETE FROM donasi WHERE id_donasi = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_donasi);

    if ($stmt->execute()) {
        // Redirect ke halaman donasi dengan status sukses
        header("Location: ../donasi.php?status=success&message=Donasi berhasil dihapus.");
        exit;
    } else {
        // Redirect ke halaman donasi dengan status error
        header("Location: ../donasi.php?status=error&message=Gagal menghapus donasi: " . urlencode($stmt->error));
        exit;
    }

    $stmt->close();
}

$conn->close();
?>
