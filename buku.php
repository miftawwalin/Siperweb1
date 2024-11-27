<?php
require "connection/koneksi.php"; // Include the database connection

// Check if the form is submitted via POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and escape form data to prevent SQL injection
    $id_edit = $_POST['id_edit'];
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $fk_induk = $_POST['fk_induk'];
    
    // Check if we're updating an existing record
    if ($id_edit) {
        // Update the existing record
        $query = "UPDATE tbldata_buku 
                  SET judul = '$judul', penulis = '$penulis', penerbit = '$penerbit', tahun_terbit = '$tahun_terbit', fk_induk = '$fk_induk' 
                  WHERE id_buku = '$id_edit'";
        
        if (mysqli_query($conn, $query)) {
            echo 'success|Data Berhasil di Update';
        } else {
            echo 'error|Error updating data: ' . mysqli_error($conn);
        }
    } else {
        // Insert a new record
        $query = "INSERT INTO tbldata_buku (judul, penulis, penerbit, tahun_terbit, fk_induk) 
                  VALUES ('$judul', '$penulis', '$penerbit', '$tahun_terbit', '$fk_induk')";
        
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
    $query = "SELECT * FROM tbldata_buku WHERE id_buku = '$id_edit'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $id_buku = $row['id_buku'];
        $judul = $row['judul'];
        $penulis = $row['penulis'];
        $penerbit = $row['penerbit'];
        $tahun_terbit = $row['tahun_terbit'];
        $fk_induk = $row['fk_induk'];
    } else {
        echo 'error|Record not found!';
        exit;
    }
} else {
    // If no id_edit parameter, initialize form with empty values (for adding new record)
    $id_buku = '';
    $judul = '';
    $penulis = '';
    $penerbit = '';
    $tahun_terbit = '';
    $fk_induk = '';
}

// Fetch all the schools from `tbldata_sekolah` to populate the dropdown
$sekolah_query = "SELECT no_induk, nm_sekolah FROM tbldata_sekolah";
$sekolah_result = mysqli_query($conn, $sekolah_query);
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
                    <form id="bukuForm">
                        <!-- Hidden field to hold the book ID for editing -->
                        <input type="hidden" name="id_edit" value="<?= isset($id_buku) ? $id_buku : '' ?>">

                        <div class="form-group">
                            <label for="judul">Judul Buku</label>
                            <input type="text" class="form-control" id="judul" name="judul" placeholder="Judul Buku" value="<?= $judul ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="penulis">Penulis</label>
                            <input type="text" class="form-control" id="penulis" name="penulis" placeholder="Penulis" value="<?= $penulis ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="penerbit">Penerbit</label>
                            <input type="text" class="form-control" id="penerbit" name="penerbit" placeholder="Penerbit" value="<?= $penerbit ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="tahun_terbit">Tahun Terbit</label>
                            <input type="text" class="form-control" id="tahun_terbit" name="tahun_terbit" placeholder="Tahun Terbit" value="<?= $tahun_terbit ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="fk_induk">Sekolah</label>
                            <select class="form-control" id="fk_induk" name="fk_induk" required>
                                <option value="">-- Pilih Sekolah --</option>
                                <?php while ($sekolah = mysqli_fetch_assoc($sekolah_result)) { ?>
                                    <option value="<?= $sekolah['no_induk'] ?>" <?= ($sekolah['no_induk'] == $fk_induk) ? 'selected' : '' ?>>
                                        <?= $sekolah['nm_sekolah'] ?>
                                    </option>
                                <?php } ?>
                            </select>
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
        var judul = document.getElementById('judul').value;
        var penulis = document.getElementById('penulis').value;
        var penerbit = document.getElementById('penerbit').value;
        var tahun_terbit = document.getElementById('tahun_terbit').value;
        var fk_induk = document.getElementById('fk_induk').value;

        // Check if any field is empty
        if (judul == '') {
            Swal.fire('Error!', 'Judul Buku kosong.', 'error');
            return;
        }
        if (penulis == '') {
            Swal.fire('Error!', 'Penulis kosong.', 'error');
            return;
        }
        if (penerbit == '') {
            Swal.fire('Error!', 'Penerbit kosong.', 'error');
            return;
        }
        if (tahun_terbit == '') {
            Swal.fire('Error!', 'Tahun Terbit kosong.', 'error');
            return;
        }
        if (fk_induk == '') {
            Swal.fire('Error!', 'Sekolah kosong.', 'error');
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
            var formData = new FormData(document.getElementById('bukuForm'));

            fetch('buku.php', {
              method: 'POST',
              body: formData
            })
            .then(response => response.text())
            .then(data => {
              const [status, message] = data.split('|');
              if (status === 'success') {
                Swal.fire('Data Tersimpan!', message, 'success')
                        .then(() => {
                            // Redirect to the databuku.php page after success
                            window.location.href = 'databuku.php';
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
