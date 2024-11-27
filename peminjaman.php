<?php
require "connection/koneksi.php"; // Include the database connection

// Check if the form is submitted via POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and escape form data to prevent SQL injection
    $id_edit = $_POST['id_edit'];
    $fk_induk_sekolah = $_POST['fk_induk_sekolah'];
    $fk_buku = $_POST['fk_buku'];
    $tgl_peminjam = $_POST['tgl_peminjam'];
    $tgl_kembali = $_POST['tgl_kembali'];
    
    // Check if we're updating an existing record
    if ($id_edit) {
        // Update the existing record in tblpeminjaman
        $query = "UPDATE tblpeminjaman 
                  SET fk_induk_sekolah = '$fk_induk_sekolah', fk_buku = '$fk_buku', tgl_peminjam = '$tgl_peminjam', tgl_kembali = '$tgl_kembali'
                  WHERE pk_id = '$id_edit'";
        
        if (mysqli_query($conn, $query)) {
            echo 'success|Data Berhasil di Update';
        } else {
            echo 'error|Error updating data: ' . mysqli_error($conn);
        }
    } else {
        // Insert a new record into tblpeminjaman
        $query = "INSERT INTO tblpeminjaman (fk_induk_sekolah, fk_buku, tgl_peminjam, tgl_kembali) 
                  VALUES ('$fk_induk_sekolah', '$fk_buku', '$tgl_peminjam', '$tgl_kembali')";
        
        if (mysqli_query($conn, $query)) {
            echo 'success|Data Tersimpan';
        } else {
            echo 'error|Error saving data: ' . mysqli_error($conn);
        }
    }
    exit; // Ensure no further code is executed after the response
}

// Check if we have an id_edit parameter in the URL for editing an existing record
if (isset($_GET['id_edit'])) {
    $id_edit = $_GET['id_edit'];
    // Fetch the current data for the selected record
    $query = "SELECT * FROM tblpeminjaman WHERE pk_id = '$id_edit'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $pk_id = $row['pk_id'];
        $fk_induk_sekolah = $row['fk_induk_sekolah'];
        $fk_buku = $row['fk_buku'];
        $tgl_peminjam = date("Y-m-d",strtotime($row['tgl_peminjam']));
        $tgl_kembali = date("Y-m-d", strtotime($row['tgl_kembali']));
    } else {
        echo 'error|Record not found!';
        exit;
    }
} else {
    // If no id_edit parameter, initialize form with empty values (for adding new record)
    $pk_id = '';
    $fk_induk_sekolah = '';
    $fk_buku = '';
    $tgl_peminjam = '';
    $tgl_kembali = '';
}

// Fetch all the schools from `tbldata_sekolah` to populate the dropdown
$sekolah_query = "SELECT no_induk, nm_sekolah FROM tbldata_sekolah";
$sekolah_result = mysqli_query($conn, $sekolah_query);

// Fetch all the books from `tbldata_buku` to populate the dropdown
$buku_query = "SELECT id_buku, judul FROM tbldata_buku";
$buku_result = mysqli_query($conn, $buku_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Perpustakaan Daerah</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sweetalert2.min.css" rel="stylesheet">
    <script src="js/sweetalert2.all.min.js"></script>
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css" />
</head>
<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include 'navbar.php'; ?>
        <!-- End Sidebar -->

        <!-- Main Content -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- Page Heading -->
                <header class="d-flex justify-content-between align-items-center py-3">
                    <h1 class="h3 text-gray-800">Perpustakaan Daerah Kabupaten Karawang</h1>
                    <div class="user-icon">
                        <img src="img/LAMBANG_KABUPATEN_KARAWANG.png" alt="User Icon" />
                    </div>
                </header>

                <div class="card-body">
                    <form id="peminjamanForm">
                        <!-- Hidden field to hold the pk_id for editing -->
                        <input type="hidden" name="id_edit" value="<?= isset($pk_id) ? $pk_id : '' ?>">

                        <div class="form-group">
                            <label for="fk_induk_sekolah">Sekolah</label>
                            <select class="form-control" id="fk_induk_sekolah" name="fk_induk_sekolah" required>
                                <option value="">-- Pilih Sekolah --</option>
                                <?php while ($sekolah = mysqli_fetch_assoc($sekolah_result)) { ?>
                                    <option value="<?= $sekolah['no_induk'] ?>" <?= ($sekolah['no_induk'] == $fk_induk_sekolah) ? 'selected' : '' ?>>
                                        <?= $sekolah['nm_sekolah'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="fk_buku">Buku</label>
                            <select class="form-control" id="fk_buku" name="fk_buku" required>
                                <option value="">-- Pilih Buku --</option>
                                <?php while ($buku = mysqli_fetch_assoc($buku_result)) { ?>
                                    <option value="<?= $buku['id_buku'] ?>" <?= ($buku['id_buku'] == $fk_buku) ? 'selected' : '' ?>>
                                        <?= $buku['judul'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tgl_peminjam">Tanggal Peminjaman</label>
                            <input type="date" class="form-control col-4" id="tgl_peminjam" name="tgl_peminjam" value="<?= $tgl_peminjam ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="tgl_kembali">Tanggal Kembali</label>
                            <input type="date" class="form-control col-4" id="tgl_kembali" name="tgl_kembali" value="<?= $tgl_kembali ?>" required>
                        </div>

                        <button type="button" class="btn btn-primary" id="save">Simpan</button>
                        <button type="button" class="btn btn-secondary" id="backButton">Kembali</button>
                    </form>
                </div>
              </div>
            </div>
            <!-- End Content -->
        </div>
    </div>

    <script>
      document.getElementById('save').addEventListener('click', function() {
        // Validate the form fields
        var fk_induk_sekolah = document.getElementById('fk_induk_sekolah').value;
        var fk_buku = document.getElementById('fk_buku').value;
        var tgl_peminjam = document.getElementById('tgl_peminjam').value;
        var tgl_kembali = document.getElementById('tgl_kembali').value;

        // Check if any field is empty
        if (fk_induk_sekolah == '') {
            Swal.fire('Error!', 'Sekolah kosong.', 'error');
            return;
        }
        if (fk_buku == '') {
            Swal.fire('Error!', 'Buku kosong.', 'error');
            return;
        }
        if (tgl_peminjam == '') {
            Swal.fire('Error!', 'Tanggal Peminjaman kosong.', 'error');
            return;
        }
        if (tgl_kembali == '') {
            Swal.fire('Error!', 'Tanggal Kembali kosong.', 'error');
            return;
        }

        // Show a confirmation dialog
        Swal.fire({
          title: 'Simpan Data?',
          text: "Apakah data sudah sesuai?",
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: 'Ya, Simpan!',
          cancelButtonText: 'Batal!'
        }).then((result) => {
          if (result.isConfirmed) {
            var formData = new FormData(document.getElementById('peminjamanForm'));

            fetch('peminjaman.php', {
              method: 'POST',
              body: formData
            })
            .then(response => response.text())
            .then(data => {
              const [status, message] = data.split('|');
              if (status === 'success') {
                Swal.fire('Data Tersimpan!', message, 'success')
                        .then(() => {
                            // Redirect to the datalaporan.php page after success
                            window.location.href = 'datapeminjaman.php';
                        });
              } else {
                Swal.fire('Error!', message, 'error');
              }
            })
            .catch(error => Swal.fire('Error!', 'There was an issue with the server.', 'error'));
          } else {
            Swal.fire('Batal', 'Data tidak tersimpan.', 'error');
          }
        });
      });

      document.getElementById('backButton').addEventListener('click', function() {
        window.history.back();
      });
    </script>
</body>
</html>
