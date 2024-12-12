<?php
require "connection/koneksi.php"; // Include the database connection

// Check if the form is submitted via POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and escape form data to prevent SQL injection
    $id_account = $_POST['id_account'];  // Used for editing existing records
    $email_account = $_POST['email_account'];
    $old_password = $_POST['old_password'];  // Old password
    $new_password = $_POST['new_password'];  // New password
    $confirm_password = $_POST['confirm_password'];  // Confirm new password

    // Fetch current password from database
    $query = "SELECT password FROM account WHERE id_account = '$id_account'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $current_password = $row['password']; // Current password in the database

        // Check if the old password is correct using MD5
        if (md5($old_password) !== $current_password) {
            echo 'error|Password lama salah';
            exit;
        }

        // Check if new password and confirm password match
        if ($new_password !== $confirm_password) {
            echo 'error|Password baru dan konfirmasi tidak cocok';
            exit;
        }

        // Check if new password is different from the old password
        if ($new_password === $old_password) {
            echo 'error|Password baru tidak boleh sama dengan password lama';
            exit;
        }

        // Hash the new password with MD5 before saving it
        $hashed_new_password = md5($new_password);

        // Update the record with the new email and password
        $update_query = "UPDATE account SET email = '$email_account', password = '$hashed_new_password' WHERE id_account = '$id_account'";
        
        if (mysqli_query($conn, $update_query)) {
            echo 'success|Data berhasil diperbarui';
        } else {
            echo 'error|Terjadi kesalahan saat memperbarui data: ' . mysqli_error($conn);
        }
    } else {
        echo 'error|Akun tidak ditemukan';
    }
    exit; // Ensure no further code is executed after the response
}

// Check if we have an id_account parameter in the URL for editing an existing record
if (isset($_GET['id_edit'])) {
    $id_account = $_GET['id_edit'];
    // Fetch the current data for the selected record
    $query = "SELECT email as email_account FROM account WHERE id_account = '$id_account'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $email_account = $row['email_account'];
    } else {
        echo 'error|Data tidak ditemukan';
        exit;
    }
} else {
    // If no id_account parameter, initialize form with empty values (for adding new record)
    $email_account = '';
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Perpustakaan Daerah - Edit Akun</title>
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
                    <form id="accountForm">
                        <!-- Hidden field to hold the account ID for editing -->
                        <input type="hidden" name="id_account" value="<?= isset($id_account) ? $id_account : '' ?>">

                        <div class="form-group">
                            <label for="email_account">Email</label>
                            <input type="email" class="form-control" id="email_account" name="email_account" placeholder="Email" value="<?= $email_account ?>" required>
                        </div>

                        <!-- New Fields for password update -->
                        <div class="form-group">
                            <label for="old_password">Password Lama</label>
                            <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Password Lama" required>
                        </div>

                        <div class="form-group">
                            <label for="new_password">Password Baru</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Password Baru" required>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Konfirmasi Password Baru" required>
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
        var email_account = document.getElementById('email_account').value;
        var old_password = document.getElementById('old_password').value;
        var new_password = document.getElementById('new_password').value;
        var confirm_password = document.getElementById('confirm_password').value;

        // Check if the email is empty
        if (email_account == '') {
            Swal.fire('Error!', 'Email kosong.', 'error');
            return;
        }

        // Check if old password is empty
        if (old_password == '') {
            Swal.fire('Error!', 'Password lama diperlukan.', 'error');
            return;
        }

        // Check if new password is empty
        if (new_password == '') {
            Swal.fire('Error!', 'Password baru diperlukan.', 'error');
            return;
        }

        // Check if confirm password is empty
        if (confirm_password == '') {
            Swal.fire('Error!', 'Konfirmasi password baru diperlukan.', 'error');
            return;
        }

        // Check if new password matches confirm password
        if (new_password !== confirm_password) {
            Swal.fire('Error!', 'Password baru dan konfirmasi tidak cocok.', 'error');
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
            var formData = new FormData(document.getElementById('accountForm'));

            fetch('edit_account.php', {
              method: 'POST',
              body: formData
            })
            .then(response => response.text())
            .then(data => {
              const [status, message] = data.split('|');
              if (status === 'success') {
                Swal.fire('Data Tersimpan!', message, 'success')
                        .then(() => {
                            console.log("Redirecting to dataadmin.php");
                            window.location.href = 'dataadmin.php';
                        });
              } else {
                Swal.fire('Error!', message, 'error');
              }
            })
            .catch(error => Swal.fire('Error!', 'Terjadi masalah dengan server.', 'error'));
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
