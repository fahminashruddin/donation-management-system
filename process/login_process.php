<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Query untuk mendapatkan data admin berdasarkan email
    $query = "SELECT * FROM petugas WHERE email_admin = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        // Verifikasi password menggunakan password_verify
        if (password_verify($password, $user['password'])) {
            // Set session untuk admin yang login
            $_SESSION['id_petugas'] = $user['id_petugas'];
            $_SESSION['nama_petugas'] = $user['nama_petugas'];

            // Redirect ke halaman dashboard
            header('Location: ../index.php');
            exit;
        } else {
            // Jika password salah
            echo "<script>alert('Login gagal! Password salah.'); window.location.href='../login.php';</script>";
        }
    } else {
        // Jika email tidak ditemukan
        echo "<script>alert('Login gagal! Email tidak ditemukan.'); window.location.href='../login.php';</script>";
    }
}

// Menutup koneksi database
mysqli_close($conn);
?>
