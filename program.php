<?php
include 'process/koneksi.php';

session_start(); // Memulai session untuk memeriksa login
if (!isset($_SESSION['id_petugas'])) {
    header('Location: login.php'); // Redirect ke halaman login jika belum login
    exit; // Hentikan eksekusi script lebih lanjut
}

if (isset($_GET['status']) && isset($_GET['message'])) {
    $status = $_GET['status'];
    $message = $_GET['message'];

    echo "<script src='template/dist/assets/extensions/sweetalert2/sweetalert2.min.js'></script>";
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '" . ($status === 'success' ? 'Berhasil!' : 'Gagal!') . "',
                text: '$message',
                icon: '" . ($status === 'success' ? 'success' : 'error') . "',
                confirmButtonText: 'OK'
            }).then(() => {
                // Hapus parameter query dari URL setelah notifikasi
                const newURL = window.location.href.split('?')[0];
                window.history.replaceState({}, document.title, newURL);
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
    <title>Data Program Donasi</title>
    
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
                            <h3>Program Donasi</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end"></nav>
                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Data Program Donasi</h5>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProgramModal">Tambah Program</button>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Program</th>
                                        <th>Target Dana</th>
                                        <th>Total Donasi</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Alamat</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $query = "
                                        SELECT 
                                            pd.id_program, 
                                            pd.nama_program, 
                                            pd.target_dana, 
                                            pd.alamat, 
                                            pd.tanggal, 
                                            pd.deskripsi, 
                                            pd.status, 
                                            COALESCE(SUM(d.total), 0) AS total_donasi 
                                        FROM program_donasi pd
                                        LEFT JOIN donasi d ON pd.id_program = d.id_program
                                        GROUP BY pd.id_program
                                        ORDER BY pd.id_program ASC";
                                    $result = mysqli_query($conn, $query);

                                    if (!$result) {
                                        die("Query error: " . mysqli_errno($conn) . " - " . mysqli_error($conn));
                                    }

                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        // Perbarui status jika total donasi sudah tercapai
                                        if ($row['total_donasi'] >= $row['target_dana'] && $row['status'] !== 'Tercapai') {
                                            $updateStatusQuery = "UPDATE program_donasi SET status = 'Tercapai' WHERE id_program = ?";
                                            $stmt = $conn->prepare($updateStatusQuery);
                                            $stmt->bind_param('i', $row['id_program']);
                                            $stmt->execute();
                                            $row['status'] = 'Tercapai';
                                        }
                                    ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo htmlspecialchars($row['nama_program']); ?></td>
                                            <td><?php echo 'Rp ' . number_format($row['target_dana'], 0, ',', '.'); ?></td>
                                            <td><?php echo 'Rp ' . number_format($row['total_donasi'], 0, ',', '.'); ?></td>
                                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                                            <td><?php echo htmlspecialchars($row['tanggal']); ?></td>
                                            <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                                            <td>
                                                <a href="process/hapus_program.php?id_program=<?php echo $row['id_program']; ?>" class="btn btn-outline-danger" onclick="return confirm('Anda yakin ingin menghapus data ini?')">Hapus</a>
                                                <a data-bs-target="#editProgramModal" class="btn btn-outline-primary edit-button" 
                                                    data-id="<?php echo $row['id_program']; ?>" 
                                                    data-nama-program="<?php echo $row['nama_program']; ?>"
                                                    data-target-dana="<?php echo $row['target_dana']; ?>"
                                                    data-alamat="<?php echo $row['alamat']; ?>"
                                                    data-tanggal="<?php echo $row['tanggal']; ?>"
                                                    data-deskripsi="<?php echo $row['deskripsi']; ?>"
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

    <!-- Modal for Edit Program -->
    <div class="modal fade" id="editProgramModal" tabindex="-1" role="dialog" aria-labelledby="editProgramModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProgramModalLabel">Edit Program Donasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="process/edit_program.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="edit-program-id" name="id_program" />
                        <label for="nama_program">Nama Program:</label>
                        <div class="form-group">
                            <input id="edit-nama-program" type="text" class="form-control" name="nama_program" required />
                        </div>
                        <label for="target_dana">Target Dana:</label>
                        <div class="form-group">
                            <input id="edit-target-dana" type="number" class="form-control" name="target_dana" required />
                        </div>
                        <label for="alamat">Alamat:</label>
                        <div class="form-group">
                            <input id="edit-alamat" type="text" class="form-control" name="alamat" required />
                        </div>
                        <label for="tanggal">Tanggal:</label>
                        <div class="form-group">
                            <input id="edit-tanggal" type="date" class="form-control" name="tanggal" required />
                        </div>
                        <label for="deskripsi">Deskripsi:</label>
                        <div class="form-group">
                            <textarea id="edit-deskripsi" class="form-control" name="deskripsi" rows="3" required></textarea>
                        </div>
                        <label for="penerima">Penerima:</label>
                        <div class="form-group">
                            <input id="edit-penerima" type="text" class="form-control" readonly />
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

    <!-- Modal Tambah Program -->
    <div class="modal fade text-left" id="addProgramModal" tabindex="-1" role="dialog" aria-labelledby="addProgramModalLabel" aria-hidden="true">
    
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProgramModalLabel">Tambah Program Donasi</h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form action="process/tambah_program.php" method="POST">
                
                <div class="modal-body">
                    <label for="nama_program">Nama Program:</label>
                    <div class="form-group">
                        <input id="add-nama-program" type="text" class="form-control" name="nama_program" required />
                    </div>

                    <label for="target_dana">Target Dana:</label>
                    <div class="form-group">
                        <input id="add-target-dana" type="number" class="form-control" name="target_dana" required />
                    </div>

                    <label for="tanggal">Tanggal:</label>
                    <div class="form-group">
                        <input id="add-tanggal" type="date" class="form-control" name="tanggal" required />
                    </div>

                    <label for="alamat">Alamat:</label>
                    <div class="form-group">
                        <input id="add-alamat" type="text" class="form-control" name="alamat" required />
                    </div>

                    <label for="deskripsi">Deskripsi:</label>
                    <div class="form-group">
                        <textarea id="add-deskripsi" class="form-control" name="deskripsi" rows="3" required></textarea>
                    </div>

                    <label for="id_penerima">Penerima:</label>
                    <div class="form-group">
                        <select id="add-id-penerima" class="form-control" name="id_penerima" required>
                            <option value="" disabled selected>Pilih Penerima</option>
                            <?php
                            $penerima_query = "SELECT id_penerima, nama_penerima FROM penerima";
                            $penerima_result = mysqli_query($conn, $penerima_query);
                            while ($penerima = mysqli_fetch_assoc($penerima_result)) {
                                echo "<option value='{$penerima['id_penerima']}'>{$penerima['nama_penerima']}</option>";
                            }
                            ?>
                        </select>
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
       document.querySelectorAll('.edit-button').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const namaProgram = this.getAttribute('data-nama-program');
            const targetDana = this.getAttribute('data-target-dana');
            const alamat = this.getAttribute('data-alamat');
            const tanggal = this.getAttribute('data-tanggal');
            const deskripsi = this.getAttribute('data-deskripsi');
            const namaPenerima = this.getAttribute('data-nama-penerima');

            // Isi input form modal dengan data dari atribut tombol
            document.getElementById('edit-program-id').value = id;
            document.getElementById('edit-nama-program').value = namaProgram;
            document.getElementById('edit-target-dana').value = targetDana;
            document.getElementById('edit-alamat').value = alamat;
            document.getElementById('edit-tanggal').value = tanggal;
            document.getElementById('edit-deskripsi').value = deskripsi;
            document.getElementById('edit-penerima').value = namaPenerima;

            // Tampilkan modal edit
            const editModal = new bootstrap.Modal(document.getElementById('editProgramModal'));
            editModal.show();
        });
    });
    </script>
</body>
</html>
