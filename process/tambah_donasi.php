<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id_donatur = trim($_POST['id_donatur']);
    $id_program = trim($_POST['id_program']); // Menambahkan id_program
    $total = trim($_POST['total']);
    $tanggal = trim($_POST['tanggal']);
    $deskripsi = trim($_POST['deskripsi']);

    // Validasi input
    $errors = [];
    if (empty($id_donatur)) $errors[] = "Donatur harus dipilih.";
    if (empty($id_program)) $errors[] = "Program harus dipilih.";
    if (empty($total) || !is_numeric($total) || $total <= 0) $errors[] = "Total donasi harus berupa angka valid.";
    if (empty($tanggal)) $errors[] = "Tanggal donasi harus diisi.";
    if (empty($deskripsi)) $errors[] = "Deskripsi harus diisi.";

    // Jika ada error, redirect dengan pesan error
    if (!empty($errors)) {
        $error_message = implode(", ", $errors);
        header("Location: ../donasi.php?status=error&message=" . urlencode($error_message));
        exit;
    }

    // Masukkan data ke dalam tabel donasi
    $query = "INSERT INTO donasi (id_donatur, id_program, total, tanggal, deskripsi) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiiss", $id_donatur, $id_program, $total, $tanggal, $deskripsi);

    if ($stmt->execute()) {
        // Redirect ke halaman donasi dengan status sukses
        header("Location: ../donasi.php?status=success&message=Donasi berhasil ditambahkan.");
        exit;
    } else {
        // Redirect ke halaman donasi dengan status error
        header("Location: ../donasi.php?status=error&message=Gagal menambahkan donasi: " . urlencode($stmt->error));
        exit;
    }

    $stmt->close();
}

$conn->close();
?>
