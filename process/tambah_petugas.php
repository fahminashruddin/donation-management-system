<?php
include 'koneksi.php';

// Ambil data dari form
$nama_petugas = mysqli_real_escape_string($conn, $_POST['nama_petugas']);
$email_petugas = mysqli_real_escape_string($conn, $_POST['email_petugas']);
$no_telp_admin = mysqli_real_escape_string($conn, $_POST['telpon']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Validasi apakah data sudah ada
$query_check = "SELECT * FROM petugas WHERE 
                LOWER(nama_petugas) = LOWER('$nama_petugas') OR 
                LOWER(email_admin) = LOWER('$email_petugas') OR 
                no_telp_admin = '$no_telp_admin'";
$result_check = mysqli_query($conn, $query_check);

if (mysqli_num_rows($result_check) > 0) {
    // Data duplikat ditemukan, redirect dengan pesan error
    header("Location: ../petugas.php?status=error&message=Petugas dengan nama, email, atau nomor telepon yang sama sudah ada!");
    exit;
}

// Jika tidak ada duplikat, masukkan data baru
$query_insert = "INSERT INTO petugas (nama_petugas, email_admin, no_telp_admin, password) 
                 VALUES ('$nama_petugas', '$email_petugas', '$no_telp_admin', '$password')";

if (mysqli_query($conn, $query_insert)) {
    // Berhasil menambahkan data, redirect dengan pesan sukses
    header("Location: ../petugas.php?status=success&message=Data petugas berhasil ditambahkan.");
} else {
    // Gagal menambahkan data, redirect dengan pesan error
    header("Location: ../petugas.php?status=error&message=Gagal menambahkan data petugas.");
}

// Tutup koneksi
mysqli_close($conn);
?>
