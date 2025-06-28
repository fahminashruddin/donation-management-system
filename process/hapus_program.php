<?php
include 'koneksi.php';

if (isset($_GET['id_program'])) {
    $id_program = trim($_GET['id_program']);

    // Hapus data program
    $query = "DELETE FROM program_donasi WHERE id_program = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_program);

    if ($stmt->execute()) {
        // Redirect ke halaman program dengan status sukses
        header("Location: ../program.php?status=success&message=Program berhasil dihapus.");
        exit;
    } else {
        // Redirect ke halaman program dengan status error
        header("Location: ../program.php?status=error&message=Gagal menghapus program: " . urlencode($stmt->error));
        exit;
    }

    $stmt->close();
} else {
    // Redirect ke halaman program jika ID tidak ditemukan
    header("Location: ../program.php?status=error&message=ID program tidak ditemukan.");
    exit;
}

$conn->close();
