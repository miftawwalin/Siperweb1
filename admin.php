<?php
require "connection/koneksi.php"; // Include the database connection

// Check if the form is submitted via POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and escape form data to prevent SQL injection
    $id_admin = $_POST['id_admin'];  // Used for editing existing records
    $nip = $_POST['nip'];
    $nm_lengkap = $_POST['nm_lengkap'];
    $no_hp = $_POST['no_hp'];
    $nm_jabatan = $_POST['nm_jabatan'];
    $fk_account = $_POST['fk_account']; // This will be the id_account from the dropdown
    
    // Check if we're updating an existing record
    if ($id_admin) {
        // Update the existing record
        $query = "UPDATE tbldata_admin SET nip = '$nip', nm_lengkap = '$nm_lengkap', no_hp = '$no_hp', nm_jabatan = '$nm_jabatan', fk_account = '$fk_account' WHERE id_admin = '$id_admin'";
        
        if (mysqli_query($conn, $query)) {
            echo 'success|Data Berhasil di Update';
        } else {
            echo 'error|Error updating data: ' . mysqli_error($conn);
        }
    } else {
        // Insert a new record
        $query = "INSERT INTO tbldata_admin (nip, nm_lengkap, no_hp, nm_jabatan, fk_account) 
                  VALUES ('$nip', '$nm_lengkap', '$no_hp', '$nm_jabatan', '$fk_account')";
        if (mysqli_query($conn, $query)) {
            echo 'success|Data Tersimpan';
        } else {
            echo 'error|Error saving data: ' . mysqli_error($conn);
        }
    }
    exit; // Ensure no further code is executed after the response
}

// Check if we have an id_admin parameter in the URL for editing an existing record
if (isset($_GET['id_edit'])) {
    $id_admin = $_GET['id_edit'];
    // Fetch the current data for the selected record
    $query = "SELECT * FROM tbldata_admin WHERE id_admin = '$id_admin'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $nip = $row['nip'];
        $nm_lengkap = $row['nm_lengkap'];
        $no_hp = $row['no_hp'];
        $nm_jabatan = $row['nm_jabatan'];
        $fk_account = $row['fk_account'];
    } else {
        echo 'error|Record not found!';
        exit;
    }
} else {
    // If no id_admin parameter, initialize form with empty values (for adding new record)
    $nip = '';
    $nm_lengkap = '';
    $no_hp = '';
    $nm_jabatan = '';
    $fk_account = '';
}

// Fetch accounts to populate the dropdown
$query_accounts = "SELECT id_account, email FROM account WHERE 1";
$result_accounts = mysqli_query($conn, $query_accounts);
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
                    <form id="adminForm">
                        <!-- Hidden field to hold the admin ID for editing -->
                        <input type="hidden" name="id_admin" value="<?= isset($id_admin) ? $id_admin : '' ?>">

                        <div class="form-group">
                            <label for="nip">NIP</label>
                            <input type="text" class="form-control" id="nip" name="nip" placeholder="NIP" value="<?= $nip ?>" <?= isset($id_admin) ? 'readonly' : '' ?> required>
                        </div>
                        <div class="form-group">
                            <label for="nm_lengkap">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nm_lengkap" name="nm_lengkap" placeholder="Nama Lengkap" value="<?= $nm_lengkap ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="no_hp">No HP</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="No HP" value="<?= $no_hp ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="nm_jabatan">Jabatan</label>
                            <input type="text" class="form-control" id="nm_jabatan" name="nm_jabatan" placeholder="Jabatan" value="<?= $nm_jabatan ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="fk_account">Account</label>
                            <select class="form-control" id="fk_account" name="fk_account" required>
                                <option value="">Pilih Account</option>
                                <?php while ($account = mysqli_fetch_assoc($result_accounts)): ?>
                                    <option value="<?= $account['id_account'] ?>" <?= $fk_account == $account['id_account'] ? 'selected' : '' ?>>
                                        <?= $account['email'] ?>
                                    </option>
                                <?php endwhile; ?>
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

    <script>
      document.getElementById('save').addEventListener('click', function() {
        // Validate the form fields
        var nip = document.getElementById('nip').value;
        var nm_lengkap = document.getElementById('nm_lengkap').value;
        var no_hp = document.getElementById('no_hp').value;
        var nm_jabatan = document.getElementById('nm_jabatan').value;
        var fk_account = document.getElementById('fk_account').value;

        // Check if any field is empty
        if (nip == '') {
            Swal.fire('Error!', 'NIP kosong.', 'error');
            return;
        }
        if (nm_lengkap == '') {
            Swal.fire('Error!', 'Nama Lengkap kosong.', 'error');
            return;
        }
        if (no_hp == '') {
            Swal.fire('Error!', 'No HP kosong.', 'error');
            return;
        }
        if (nm_jabatan == '') {
            Swal.fire('Error!', 'Jabatan kosong.', 'error');
            return;
        }
        if (fk_account == '') {
            Swal.fire('Error!', 'Account kosong.', 'error');
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
            var formData = new FormData(document.getElementById('adminForm'));

            fetch('admin.php', {
              method: 'POST',
              body: formData
            })
            .then(response => response.text())
            .then(data => {
              const [status, message] = data.split('|');
              if (status === 'success') {
                Swal.fire('Data Tersimpan!', message, 'success')
                        .then(() => {
                            // Redirect to the dataadmin.php page after success
                            window.location.href = 'dataadmin.php';
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
