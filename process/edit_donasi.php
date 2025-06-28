<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id_donasi = trim($_POST['id_donasi']);
    $id_donatur = trim($_POST['id_donatur']);
    $id_program = trim($_POST['id_program']); // Ambil program yang dipilih
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

    // Update data donasi
    $query = "UPDATE donasi SET id_donatur = ?, id_program = ?, total = ?, tanggal = ?, deskripsi = ? WHERE id_donasi = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiissi", $id_donatur, $id_program, $total, $tanggal, $deskripsi, $id_donasi);

    if ($stmt->execute()) {
        // Redirect ke halaman donasi dengan status sukses
        header("Location: ../donasi.php?status=success&message=Donasi berhasil diperbarui.");
        exit;
    } else {
        // Redirect ke halaman donasi dengan status error
        header("Location: ../donasi.php?status=error&message=Gagal memperbarui donasi: " . urlencode($stmt->error));
        exit;
    }

    $stmt->close();
}

$conn->close();
?>
