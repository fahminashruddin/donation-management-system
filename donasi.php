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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Donasi</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="template/dist/./assets/compiled/svg/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="template/dist/assets/extensions/simple-datatables/style.css">
    <link rel="stylesheet" href="template/dist/./assets/compiled/css/table-datatable.css">
    <link rel="stylesheet" href="template/dist/./assets/compiled/css/app.css">
    <link rel="stylesheet" href="template/dist/./assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="template/dist/assets/extensions/sweetalert2/sweetalert2.min.css">
    <script src="template/dist/assets/extensions/sweetalert2/sweetalert2.min.js"></script>
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
                            <h3>Data Donasi</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            </nav>
                        </div>
                    </div>
                </div>

                <section class="section">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Data Donasi</h5>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDataModal">Tambah Data</button>
                        </div>
                        <div class="card-body">
                        <table class="table table-striped" id="table1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Donatur</th>
                                    <th>Program</th>
                                    <th>Total Donasi</th>
                                    <th>Deskripsi</th>
                                    <th>Tanggal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "
                                    SELECT 
                                        donasi.id_donasi, 
                                        donatur.nama_donatur, 
                                        donasi.total, 
                                        donasi.tanggal, 
                                        donasi.deskripsi, 
                                        program_donasi.nama_program 
                                    FROM donasi 
                                    INNER JOIN donatur ON donatur.id_donatur = donasi.id_donatur 
                                    INNER JOIN program_donasi ON program_donasi.id_program = donasi.id_program 
                                    ORDER BY donasi.id_donasi ASC";
                                $result = mysqli_query($conn, $query);

                                if (!$result) {
                                    die("Query error: " . mysqli_errno($conn) . " - " . mysqli_error($conn));
                                }

                                $no = 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_donatur'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_program'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo 'Rp ' . number_format($row['total'], 0, ',', '.'); ?></td>
                                        <td><?php echo htmlspecialchars($row['deskripsi'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($row['tanggal'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>
                                            <a href="process/hapus_donasi.php?id_donasi=<?php echo $row['id_donasi']; ?>" class="btn btn-outline-danger" onclick="return confirm('Anda yakin ingin menghapus data ini?')">Hapus</a>
                                            <a data-bs-target="#editDonasiModal" class="btn btn-outline-primary edit-button"
                                            data-id-donasi="<?php echo htmlspecialchars($row['id_donasi'], ENT_QUOTES, 'UTF-8'); ?>"
                                            data-id-donatur="<?php echo htmlspecialchars($row['nama_donatur'], ENT_QUOTES, 'UTF-8'); ?>"
                                            data-id-program="<?php echo htmlspecialchars($row['nama_program'], ENT_QUOTES, 'UTF-8'); ?>"
                                            data-total="<?php echo htmlspecialchars($row['total'], ENT_QUOTES, 'UTF-8'); ?>"
                                            data-tanggal="<?php echo htmlspecialchars($row['tanggal'], ENT_QUOTES, 'UTF-8'); ?>"
                                            data-deskripsi="<?php echo htmlspecialchars($row['deskripsi'], ENT_QUOTES, 'UTF-8'); ?>"
                                            data-bs-toggle="modal">Edit</a>
                                        </td>
                                    </tr>
                                <?php
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

     <!-- Modal Tambah Donasi -->
     <div class="modal fade text-left" id="addDataModal" tabindex="-1" role="dialog" aria-labelledby="addDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <form action="process/tambah_donasi.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addDataModalLabel">Tambah Data Donasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="donatur">Donatur:</label>
                        <select class="form-select" name="id_donatur" required>
                            <option value="" disabled selected>Pilih Donatur</option>
                            <?php
                            $query = "SELECT id_donatur, nama_donatur FROM donatur";
                            $result = mysqli_query($conn, $query);
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='{$row['id_donatur']}'>{$row['nama_donatur']}</option>";
                            }
                            ?>
                        </select>

                        <label for="program">Program:</label>
                        <select class="form-select" name="id_program" required>
                            <option value="" disabled selected>Pilih Program</option>
                            <?php
                            $query = "SELECT id_program, nama_program FROM program_donasi";
                            $result = mysqli_query($conn, $query);
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='{$row['id_program']}'>{$row['nama_program']}</option>";
                            }
                            ?>
                        </select>

                        <label for="total">Total Donasi:</label>
                        <input type="number" class="form-control" name="total" required>

                        <label for="tanggal">Tanggal:</label>
                        <input type="date" class="form-control" name="tanggal" required>

                        <label for="deskripsi">Deskripsi:</label>
                        <textarea class="form-control" name="deskripsi" rows="3" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal edit -->
    <div class="modal fade" id="editDonasiModal" tabindex="-1" aria-labelledby="editDonasiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDonasiModalLabel">Edit Donasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="process/edit_donasi.php" method="POST">
                <div class="modal-body">
                    <!-- Hidden input untuk ID Donasi -->
                    <input type="hidden" name="id_donasi" id="edit-id-donasi" />

                    <!-- Pilih Donatur -->
                    <label for="edit-id-donatur">Donatur:</label>
                    <select name="id_donatur" id="edit-id-donatur" class="form-select" required>
                        <option value="" disabled selected>Pilih Donatur</option>
                        <?php
                        $donatur_query = "SELECT id_donatur, nama_donatur FROM donatur";
                        $donatur_result = mysqli_query($conn, $donatur_query);
                        while ($donatur = mysqli_fetch_assoc($donatur_result)) {
                            echo "<option value='{$donatur['id_donatur']}'>{$donatur['nama_donatur']}</option>";
                        }
                        ?>
                    </select>

                    <!-- Pilih Program -->
                    <label for="edit-id-program">Program:</label>
                    <select name="id_program" id="edit-id-program" class="form-select" required>
                        <option value="" disabled selected>Pilih Program</option>
                        <?php
                        $program_query = "SELECT id_program, nama_program FROM program_donasi";
                        $program_result = mysqli_query($conn, $program_query);
                        while ($program = mysqli_fetch_assoc($program_result)) {
                            echo "<option value='{$program['id_program']}'>{$program['nama_program']}</option>";
                        }
                        ?>
                    </select>

                    <!-- Total Donasi -->
                    <label for="edit-total">Total Donasi:</label>
                    <input type="number" id="edit-total" name="total" class="form-control" required />

                    <!-- Tanggal Donasi -->
                    <label for="edit-tanggal">Tanggal:</label>
                    <input type="date" id="edit-tanggal" name="tanggal" class="form-control" required />

                    <!-- Deskripsi Donasi -->
                    <label for="edit-deskripsi">Deskripsi:</label>
                    <textarea id="edit-deskripsi" name="deskripsi" class="form-control" rows="3" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>



    <!-- Script Notifikasi -->
    <script>
    document.querySelectorAll('.edit-button').forEach(button => {
        button.addEventListener('click', function () {
            // Ambil data dari atribut tombol
            const idDonasi = this.getAttribute('data-id-donasi');
            const idDonatur = this.getAttribute('data-id-donatur');
            const idProgram = this.getAttribute('data-id-program');
            const total = this.getAttribute('data-total');
            const tanggal = this.getAttribute('data-tanggal');
            const deskripsi = this.getAttribute('data-deskripsi');

            // Isi input form modal dengan data
            document.getElementById('edit-id-donasi').value = idDonasi;
            document.getElementById('edit-id-donatur').value = idDonatur;
            document.getElementById('edit-id-program').value = idProgram;
            document.getElementById('edit-total').value = total;
            document.getElementById('edit-tanggal').value = tanggal;
            document.getElementById('edit-deskripsi').value = deskripsi;

            // Tampilkan modal
            const editModal = new bootstrap.Modal(document.getElementById('editDonasiModal'));
            editModal.show();
        });
    });
</script>

</body>

</html>
