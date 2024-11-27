<?php
require "connection/koneksi.php"; // Include the database connection

// Check if the form is submitted via POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and escape form data to prevent SQL injection
    $id_edit = $_POST['id_edit'];
    $no_induk = $_POST['no_induk'];
    $nm_sekolah = $_POST['nm_sekolah'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    
    // Check if we're updating an existing record
    if ($id_edit) {
        // Update the existing record
        $query = "UPDATE tbldata_sekolah SET nm_sekolah = '$nm_sekolah', alamat = '$alamat', email = '$email' WHERE no_induk = '$no_induk'";
        
        if (mysqli_query($conn, $query)) {
            echo 'success|Data Berhasil di Update';
        } else {
            echo 'error|Error updating data: ' . mysqli_error($conn);
        }
    } else {
        // Insert a new record
        $query = "INSERT INTO tbldata_sekolah (no_induk, nm_sekolah, alamat, email) 
                  VALUES ('$no_induk', '$nm_sekolah', '$alamat', '$email')";
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
    $query = "SELECT * FROM tbldata_sekolah WHERE no_induk = '$id_edit'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $no_induk = $row['no_induk'];
        $nm_sekolah = $row['nm_sekolah'];
        $alamat = $row['alamat'];
        $email = $row['email'];
    } else {
        echo 'error|Record not found!';
        exit;
    }
} else {
    // If no id_edit parameter, initialize form with empty values (for adding new record)
    $no_induk = '';
    $nm_sekolah = '';
    $alamat = '';
    $email = '';
}
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
                    <form id="sekolahForm">
                        <!-- Hidden field to hold the school ID for editing -->
                        <input type="hidden" name="id_edit" value="<?= isset($no_induk) ? $no_induk : '' ?>">

                        <div class="form-group">
                            <label for="no_induk">No Induk</label>
                            <input type="text" class="form-control" id="no_induk" name="no_induk" placeholder="No Induk" value="<?= $no_induk ?>" <?= isset($id_edit) ? 'readonly' : '' ?> required>
                        </div>
                        <div class="form-group">
                            <label for="nm_sekolah">Nama Sekolah</label>
                            <input type="text" class="form-control" id="nm_sekolah" name="nm_sekolah" placeholder="Nama Sekolah" value="<?= $nm_sekolah ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat" value="<?= $alamat ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?= $email ?>" required>
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
        var no_induk = document.getElementById('no_induk').value;
        var nm_sekolah = document.getElementById('nm_sekolah').value;
        var alamat = document.getElementById('alamat').value;
        var email = document.getElementById('email').value;

        // Check if any field is empty
        if (no_induk == '') {
            Swal.fire('Error!', 'No Induk Sekolah kosong.', 'error');
            return;
        }
        if (nm_sekolah == '') {
            Swal.fire('Error!', 'Nama Sekolah kosong.', 'error');
            return;
        }
        if (alamat == '') {
            Swal.fire('Error!', 'Alamat kosong.', 'error');
            return;
        }
        if (email == '') {
            Swal.fire('Error!', 'Email kosong.', 'error');
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
            var formData = new FormData(document.getElementById('sekolahForm'));

            fetch('sekolah.php', {
              method: 'POST',
              body: formData
            })
            .then(response => response.text())
            .then(data => {
              const [status, message] = data.split('|');
              if (status === 'success') {
                Swal.fire('Data Tersimpan!', message, 'success')
                        .then(() => {
                            // Redirect to the datasekolah.php page after success
                            window.location.href = 'datasekolah.php';
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