<?php
include 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id_program = isset($_POST['id_program']) ? $_POST['id_program'] : '';
    $nama_program = isset($_POST['nama_program']) ? trim($_POST['nama_program']) : '';
    $target_dana = isset($_POST['target_dana']) ? (int)$_POST['target_dana'] : 0;
    $alamat = isset($_POST['alamat']) ? trim($_POST['alamat']) : '';
    $tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : date('Y-m-d');
    $deskripsi = isset($_POST['deskripsi']) ? trim($_POST['deskripsi']) : '';

    // Validasi input
    $errors = [];
    if (empty($id_program)) $errors[] = "ID Program tidak valid.";
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

    // Query untuk memperbarui data program
    $query = "UPDATE program_donasi 
              SET nama_program = ?, 
                  target_dana = ?, 
                  alamat = ?, 
                  tanggal = ?, 
                  deskripsi = ?
              WHERE id_program = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        'sisssi', 
        $nama_program, 
        $target_dana, 
        $alamat, 
        $tanggal, 
        $deskripsi, 
        $id_program
    );

    if ($stmt->execute()) {
        // Redirect ke halaman program jika berhasil
        header('Location: ../program.php?status=success&message=Program berhasil diperbarui.');
        exit;
    } else {
        // Redirect ke halaman program dengan pesan error
        header("Location: ../program.php?status=error&message=Gagal memperbarui program: " . urlencode($stmt->error));
        exit;
    }

    $stmt->close();
}

$conn->close();
?>
