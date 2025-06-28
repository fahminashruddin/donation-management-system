<?php
include 'process/koneksi.php';

// Notifikasi menggunakan SweetAlert
if (isset($_GET['status']) && isset($_GET['message'])) {
    $status = $_GET['status'];
    $message = $_GET['message'];

    echo "<script src='template/dist/assets/extensions/sweetalert2/sweetalert2.min.js'></script>";
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '{$status}' === 'success' ? 'Berhasil!' : 'Gagal!',
                text: '{$message}',
                icon: '{$status}',
                confirmButtonText: 'OK'
            });
        });
    </script>";
}

session_start(); // Memulai session untuk memeriksa login
if (!isset($_SESSION['id_petugas'])) {
    header('Location: login.php'); // Redirect ke halaman login jika belum login
    exit; // Hentikan eksekusi script lebih lanjut
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Petugas</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="template/dist/./assets/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="template/dist/assets/extensions/simple-datatables/style.css">
    <link rel="stylesheet" href="template/dist/./assets/compiled/css/table-datatable.css">
    <link rel="stylesheet" href="template/dist/./assets/compiled/css/app.css">
    <link rel="stylesheet" href="template/dist/./assets/compiled/css/app-dark.css">
</head>

<body>
    <script src="template/dist/assets/static/js/initTheme.js"></script>
    <div id="app">
        <?php include 'lib/sidebar.php'; ?>

        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
            
            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Petugas</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end"></nav>
                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Data Petugas</h5>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDataModal">Tambah Data</button>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Petugas</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $query = "SELECT * FROM petugas ORDER BY id_petugas ASC";
                                    $result = mysqli_query($conn, $query);

                                    if (!$result) {
                                        die("Query error: " . mysqli_errno($conn) . " - " . mysqli_error($conn));
                                    }

                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $row['nama_petugas']; ?></td>
                                        <td>
                                            <a href="process/hapus_petugas.php?id_petugas=<?php echo $row['id_petugas']; ?>" class="btn btn-outline-danger" onclick="return confirm('Anda yakin ingin menghapus data ini?')">Hapus</a>
                                            <a data-bs-target="#edit" class="btn btn-outline-primary edit-button" 
                                                data-id="<?php echo $row['id_petugas']; ?>" 
                                                data-nama="<?php echo $row['nama_petugas']; ?>" 
                                                data-email="<?php echo $row['email_admin']; ?>" 
                                                data-telp="<?php echo $row['no_telp_admin']; ?>" 
                                                data-bs-toggle="modal">Edit</a>
                                        </td>
                                    </tr>
                                    <?php 
                                    $no++;
                                    } 
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    
    <script src="template/dist/assets/static/js/components/dark.js"></script>
    <script src="template/dist/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="template/dist/assets/compiled/js/app.js"></script>
    <script src="template/dist/assets/extensions/simple-datatables/umd/simple-datatables.js"></script>
    <script src="template/dist/assets/static/js/pages/simple-datatables.js"></script>

    <!-- Modal for Edit Data -->
    <div class="modal fade text-left" id="edit" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Petugas</h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <form action="process/edit_petugas.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="edit-id" name="id_petugas">
                        <label for="email_admin">Email Petugas:</label>
                        <div class="form-group">
                            <input id="edit-email" type="email" class="form-control" name="email_admin" required />
                        </div>
                        <label for="nama_petugas">Nama Petugas:</label>
                        <div class="form-group">
                            <input id="edit-nama" type="text" class="form-control" name="nama_petugas" required />
                        </div>
                        <label for="no_telp_admin">No Telpon:</label>
                        <div class="form-group">
                            <input id="edit-telp" type="text" class="form-control" name="no_telp_admin" 
                                maxlength="13" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 13)" required />
                        </div>

                        <label for="password">Password:</label>
                        <div class="form-group">
                            <input id="edit-password" type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak ingin mengubah" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal for Add Data -->
    <div class="modal fade text-left" id="addDataModal" tabindex="-1" role="dialog" aria-labelledby="addDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDataModalLabel">Tambah Data Petugas</h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <form action="process/tambah_petugas.php" method="POST">
                    <div class="modal-body">
                        <label for="nama_petugas">Nama Petugas:</label>
                        <div class="form-group">
                            <input id="add-petugas" type="text" class="form-control" name="nama_petugas" required />
                        </div>
                        <label for="email_petugas">Email Petugas:</label>
                        <div class="form-group">
                            <input id="add-email" type="email" class="form-control" name="email_petugas" required />
                        </div>
                        <label for="telpon">No Telpon:</label>
                        <div class="form-group">
                            <input id="add-telpon" type="text" class="form-control" name="telpon" 
                                maxlength="13" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 13)" required />
                        </div>
                        <label for="password">Password:</label>
                        <div class="form-group">
                            <input id="add-password" type="password" class="form-control" name="password" required />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        // Menghapus parameter `status` dan `message` dari URL setelah notifikasi ditampilkan
        if (window.location.href.includes('?status=')) {
            const newURL = window.location.href.split('?')[0];
            window.history.replaceState({}, document.title, newURL);
        }

        // Mengisi form modal edit dengan data dari tombol
        document.querySelectorAll('.edit-button').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                const email = this.getAttribute('data-email');
                const telp = this.getAttribute('data-telp');

                document.getElementById('edit-id').value = id;
                document.getElementById('edit-nama').value = nama;
                document.getElementById('edit-email').value = email;
                document.getElementById('edit-telp').value = telp;

                // Tampilkan modal edit
                const editModal = new bootstrap.Modal(document.getElementById('edit'));
                editModal.show();
            });
        });
    </script>
</body>
</html>
