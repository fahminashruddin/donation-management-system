<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_donatur'];
    $nama = trim($_POST['nama_donatur']);
    $email = trim($_POST['email_donatur']);
    $telp = trim($_POST['no_telp_donatur']);
    $alamat = trim($_POST['alamat']);

    // Validasi input
    $errors = [];
    if (empty($nama)) $errors[] = "Nama donatur harus diisi.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email donatur tidak valid.";
    if (empty($telp) || !preg_match('/^[0-9]{10,13}$/', $telp)) $errors[] = "Nomor telepon harus berupa angka 10-13 digit.";
    if (empty($alamat)) $errors[] = "Alamat donatur harus diisi.";

    // Jika ada error, redirect dengan pesan error
    if (!empty($errors)) {
        $error_message = implode(", ", $errors);
        header("Location: ../donatur.php?status=error&message=" . urlencode($error_message));
        exit;
    }

    // Periksa apakah email sudah digunakan oleh donatur lain
    $query_check = "SELECT id_donatur FROM donatur WHERE email_donatur = ? AND id_donatur != ?";
    $stmt_check = $conn->prepare($query_check);
    $stmt_check->bind_param("si", $email, $id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        header("Location: ../donatur.php?status=error&message=" . urlencode("Email sudah digunakan oleh donatur lain."));
        exit;
    }

    // Update data donatur
    $query = "UPDATE donatur SET nama_donatur = ?, email_donatur = ?, no_telp_donatur = ?, alamat = ? WHERE id_donatur = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $nama, $email, $telp, $alamat, $id);

    if ($stmt->execute()) {
        // Redirect dengan status success
        header("Location: ../donatur.php?status=success&message=" . urlencode("Data donatur berhasil diperbarui."));
        exit;
    } else {
        // Redirect dengan status error
        header("Location: ../donatur.php?status=error&message=" . urlencode("Gagal memperbarui data donatur."));
        exit;
    }

    $stmt->close();
} else {
    // Redirect jika akses tidak diizinkan
    header("Location: ../donatur.php?status=error&message=" . urlencode("Akses tidak diizinkan."));
    exit;
}

$conn->close();
?>
