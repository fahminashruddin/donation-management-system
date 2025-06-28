<?php
include 'koneksi.php';

// Periksa apakah metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id_petugas = mysqli_real_escape_string($conn, $_POST['id_petugas']);
    $email_admin = mysqli_real_escape_string($conn, $_POST['email_admin']);
    $nama_petugas = mysqli_real_escape_string($conn, $_POST['nama_petugas']);
    $no_telp_admin = mysqli_real_escape_string($conn, $_POST['no_telp_admin']);
    $password = isset($_POST['password']) && !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    // Validasi apakah data wajib telah diisi
    if (empty($id_petugas) || empty($email_admin) || empty($nama_petugas) || empty($no_telp_admin)) {
        header("Location: ../petugas.php?status=error&message=Data tidak lengkap!");
        exit;
    }

    // Update data
    if ($password) {
        // Jika password diisi, update dengan password
        $query = "UPDATE petugas SET email_admin = ?, nama_petugas = ?, no_telp_admin = ?, password = ? WHERE id_petugas = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssssi', $email_admin, $nama_petugas, $no_telp_admin, $password, $id_petugas);
    } else {
        // Jika password tidak diisi, update tanpa mengubah password
        $query = "UPDATE petugas SET email_admin = ?, nama_petugas = ?, no_telp_admin = ? WHERE id_petugas = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssi', $email_admin, $nama_petugas, $no_telp_admin, $id_petugas);
    }

    // Eksekusi query dan cek hasil
    if ($stmt->execute()) {
        // Redirect dengan pesan sukses
        header("Location: ../petugas.php?status=success&message=Data petugas berhasil diperbarui.");
    } else {
        // Redirect dengan pesan error
        header("Location: ../petugas.php?status=error&message=Gagal memperbarui data petugas.");
    }

    // Tutup statement
    $stmt->close();
} else {
    // Redirect jika metode request bukan POST
    header("Location: ../petugas.php?status=error&message=Akses tidak diizinkan!");
}

// Tutup koneksi
mysqli_close($conn);
?>
