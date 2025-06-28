<?php
include 'koneksi.php'; // Include koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validasi input ID
    if (isset($_GET['id_petugas']) && !empty($_GET['id_petugas'])) {
        $id_petugas = intval($_GET['id_petugas']); // Pastikan ID adalah angka

        // Query untuk menghapus data
        $query = "DELETE FROM petugas WHERE id_petugas = ?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("i", $id_petugas);

            // Eksekusi query
            if ($stmt->execute()) {
                // Redirect dengan pesan sukses
                header("Location: ../petugas.php?status=success&message=Data petugas berhasil dihapus.");
                exit;
            } else {
                // Redirect dengan pesan error
                header("Location: ../petugas.php?status=error&message=Gagal menghapus data petugas.");
                exit;
            }

            // Tutup statement
            $stmt->close();
        } else {
            // Redirect jika query gagal diproses
            header("Location: ../petugas.php?status=error&message=Gagal memproses penghapusan data petugas.");
            exit;
        }
    } else {
        // Redirect jika ID tidak valid
        header("Location: ../petugas.php?status=error&message=ID petugas tidak ditemukan atau tidak valid.");
        exit;
    }
} else {
    // Redirect jika metode request bukan GET
    header("Location: ../petugas.php?status=error&message=Akses tidak diizinkan.");
    exit;
}

// Tutup koneksi database
$conn->close();
?>
