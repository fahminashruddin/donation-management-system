<?php
// Menyertakan koneksi ke database
include 'koneksi.php';

// Memeriksa apakah request menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama_donatur = trim($_POST['nama_donatur']);
    $email_donatur = trim($_POST['email_donatur']);
    $no_telp_donatur = trim($_POST['no_telp_donatur']);
    $alamat = trim($_POST['alamat']);

    // Validasi input
    $errors = [];
    if (empty($nama_donatur)) $errors[] = "Nama donatur harus diisi.";
    if (empty($email_donatur)) $errors[] = "Email donatur harus diisi.";
    if (!filter_var($email_donatur, FILTER_VALIDATE_EMAIL)) $errors[] = "Format email tidak valid.";
    if (empty($no_telp_donatur)) {
        $errors[] = "Nomor telepon harus diisi.";
    } elseif (!preg_match('/^[0-9]{10,13}$/', $no_telp_donatur)) {
        $errors[] = "Nomor telepon harus berupa angka dengan panjang 10-13 digit.";
    }
    if (empty($alamat)) $errors[] = "Alamat donatur harus diisi.";

    // Jika ada error, redirect dengan pesan error
    if (!empty($errors)) {
        $error_message = implode(", ", $errors);
        header("Location: ../donatur.php?status=error&message=" . urlencode($error_message));
        exit;
    }

    // Periksa apakah email donatur sudah terdaftar
    $query_check = "SELECT * FROM donatur WHERE email_donatur = ?";
    $stmt_check = $conn->prepare($query_check);
    $stmt_check->bind_param("s", $email_donatur);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        header("Location: ../donatur.php?status=error&message=Email donatur sudah terdaftar.");
        exit;
    }

    // Jika validasi berhasil, masukkan data ke dalam tabel donatur
    $query = "INSERT INTO donatur (nama_donatur, email_donatur, no_telp_donatur, alamat) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $nama_donatur, $email_donatur, $no_telp_donatur, $alamat);

    if ($stmt->execute()) {
        // Redirect ke halaman donatur dengan status sukses
        header("Location: ../donatur.php?status=success&message=Donatur berhasil ditambahkan.");
        exit;
    } else {
        // Redirect ke halaman donatur dengan status error
        header("Location: ../donatur.php?status=error&message=Gagal menambahkan donatur: " . urlencode($stmt->error));
        exit;
    }

    // Tutup statement
    $stmt->close();
}

// Tutup koneksi ke database
$conn->close();
?>
