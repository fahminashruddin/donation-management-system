<?php
include 'koneksi.php';

if (isset($_GET['id_penerima']) && !empty($_GET['id_penerima'])) {
    $id_penerima = intval($_GET['id_penerima']);

    $query = "DELETE FROM penerima WHERE id_penerima = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $id_penerima);
        if ($stmt->execute()) {
            header("Location: ../penerima.php?status=success&message=Data penerima berhasil dihapus.");
        } else {
            header("Location: ../penerima.php?status=error&message=Gagal menghapus data penerima: " . urlencode($stmt->error));
        }
        $stmt->close();
    } else {
        header("Location: ../penerima.php?status=error&message=Gagal memproses penghapusan data penerima.");
    }
} else {
    header("Location: ../penerima.php?status=error&message=ID penerima tidak ditemukan atau tidak valid.");
}
$conn->close();
?>
