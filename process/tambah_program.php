<?php
include 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama_program = isset($_POST['nama_program']) ? trim($_POST['nama_program']) : '';
    $target_dana = isset($_POST['target_dana']) ? (int)$_POST['target_dana'] : 0;
    $alamat = isset($_POST['alamat']) ? trim($_POST['alamat']) : '';
    $deskripsi = isset($_POST['deskripsi']) ? trim($_POST['deskripsi']) : '';
    $tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : date('Y-m-d');
    $id_petugas = isset($_SESSION['id_petugas']) ? (int)$_SESSION['id_petugas'] : 0;

    // Ambil penerima, bisa NULL jika tidak dipilih
    $id_penerima = isset($_POST['id_penerima']) && !empty($_POST['id_penerima']) ? (int)$_POST['id_penerima'] : null;

    // Validasi input
    $errors = [];
    if (empty($nama_program)) $errors[] = "Nama program harus diisi.";
    if ($target_dana <= 0) $errors[] = "Target dana harus lebih besar dari 0.";
    if (empty($alamat)) $errors[] = "Alamat harus diisi.";
    if (empty($deskripsi)) $errors[] = "Deskripsi harus diisi.";

    // Jika ada error, redirect dengan pesan error
    if (!empty($errors)) {
        $error_message = implode(", ", $errors);
        header("Location: ../program.php?status=error&message=" . urlencode($error_message));
        exit;
    }

    // Set nilai default status
    $status = 'Belum Tercapai';

    // Query untuk menambahkan data
    $query = "INSERT INTO program_donasi (nama_program, target_dana, alamat, deskripsi, tanggal, id_petugas, id_penerima) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sisssii", $nama_program, $target_dana, $alamat, $deskripsi, $tanggal, $id_petugas, $id_penerima);

    if ($stmt->execute()) {
        // Redirect jika berhasil
        header("Location: ../program.php?status=success&message=Program berhasil ditambahkan.");
        exit;
    } else {
        // Redirect jika gagal
        header("Location: ../program.php?status=error&message=Gagal menambahkan program: " . $stmt->error);
        exit;
    }

    $stmt->close();
}

$conn->close();
?>
