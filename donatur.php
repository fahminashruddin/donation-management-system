<?php
include 'process/koneksi.php';

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Donatur</title>
    
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
                            <h3>Donatur</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end"></nav>
                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Data Donatur</h5>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDataModal">Tambah Data</button>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Donatur</th>
                                        <th>Email</th>
                                        <th>No Telp</th>
                                        <th>Alamat</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $query = "SELECT * FROM donatur ORDER BY id_donatur ASC";
                                    $result = mysqli_query($conn, $query);

                                    if (!$result) {
                                        die("Query error: " . mysqli_errno($conn) . " - " . mysqli_error($conn));
                                    }

                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $row['nama_donatur']; ?></td>
                                        <td><?php echo $row['email_donatur']; ?></td>
                                        <td><?php echo $row['no_telp_donatur']; ?></td>
                                        <td><?php echo $row['alamat']; ?></td>
                                        <td>
                                            <a href="process/hapus_donatur.php?id_donatur=<?php echo $row['id_donatur']; ?>" class="btn btn-outline-danger" onclick="return confirm('Anda yakin ingin menghapus data ini?')">Hapus</a>
                                            <a data-bs-target="#edit" class="btn btn-outline-primary edit-button" 
                                                data-id="<?php echo $row['id_donatur']; ?>" 
                                                data-nama="<?php echo $row['nama_donatur']; ?>" 
                                                data-email="<?php echo $row['email_donatur']; ?>" 
                                                data-telp="<?php echo $row['no_telp_donatur']; ?>" 
                                                data-alamat="<?php echo $row['alamat']; ?>" 
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

    <!-- Modal Tambah Data -->
    <div class="modal fade text-left" id="addDataModal" tabindex="-1" role="dialog" aria-labelledby="addDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDataModalLabel">Tambah Data Donatur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="process/tambah_donatur.php" method="POST" onsubmit="return validatePhoneNumber('add-no-telp')">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_donatur" class="form-label">Nama Donatur</label>
                            <input type="text" class="form-control" name="nama_donatur" required />
                        </div>
                        <div class="mb-3">
                            <label for="email_donatur" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email_donatur" required />
                        </div>
                        <div class="mb-3">
                            <label for="no_telp_donatur" class="form-label">No Telp</label>
                            <input
                                type="text"
                                id="add-no-telp"
                                class="form-control"
                                name="no_telp_donatur"
                                maxlength="13"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 13)"
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


<!-- Modal Edit Data -->
    <div class="modal fade text-left" id="editDataModal" tabindex="-1" role="dialog" aria-labelledby="editDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDataModalLabel">Edit Data Donatur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="process/edit_donatur.php" method="POST">
                    <input type="hidden" name="id_donatur" id="edit-id" />
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-nama" class="form-label">Nama Donatur</label>
                            <input type="text" name="nama_donatur" id="edit-nama" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="edit-email" class="form-label">Email</label>
                            <input type="email" name="email_donatur" id="edit-email" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="edit-telp" class="form-label">No Telp</label>
                            <input
                                type="text"
                                id="edit-telp"
                                class="form-control"
                                name="no_telp_donatur"
                                maxlength="13"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 13)"
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label for="edit-alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" id="edit-alamat" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


<script>
    // Fungsi validasi nomor telepon
    function validatePhoneNumber(inputId) {
        const phoneNumber = document.getElementById(inputId).value;
        const phoneRegex = /^[0-9]{10,13}$/;

        if (!phoneRegex.test(phoneNumber)) {
            alert("Nomor telepon harus berupa angka dengan panjang 10-13 digit.");
            return false;
        }
        return true;
    }

    // Menghapus parameter `status` dan `message` dari URL setelah notifikasi berhasil ditampilkan
    window.addEventListener('DOMContentLoaded', (event) => {
        if (window.location.href.includes('?status=')) {
            const newURL = window.location.href.split('?')[0];
            window.history.replaceState({}, document.title, newURL);
        }
    });

    // Script untuk menampilkan data ke modal edit
    document.querySelectorAll('.edit-button').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const nama = this.getAttribute('data-nama');
            const email = this.getAttribute('data-email');
            const telp = this.getAttribute('data-telp');
            const alamat = this.getAttribute('data-alamat');

            document.getElementById('edit-id').value = id;
            document.getElementById('edit-nama').value = nama;
            document.getElementById('edit-email').value = email;
            document.getElementById('edit-telp').value = telp;
            document.getElementById('edit-alamat').value = alamat;

            // Tampilkan modal edit
            const editModal = new bootstrap.Modal(document.getElementById('editDataModal'));
            editModal.show();
        });
    });
</script>

</body>
</html>
