<?php
require "connection/koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Signup - Perpustakaan Daerah</title>
    <link rel="stylesheet" href="css/signup.css" />
    <!-- SweetAlert2 CSS -->
    <link href="css/all.min.css" rel="stylesheet" type="text/css">
    <script src="js/sweetalert2.all.min.js"></script>
  </head>
  <body>
    <div class="signup-container">
      <div class="signup-box">
        <div class="logo-container">
          <img src="logo.png" alt="Library Logo" class="logo" />
        </div>
        <h2>Perpustakaan Daerah Kabupaten Karawang</h2>
        <form action="" method="POST">
          <div class="input-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required />
          </div>
          <div class="input-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required />
          </div>
          <div class="input-group">
            <label for="confirm-password">Confirm Password</label>
            <input type="password" id="confirm-password" name="confirm_password" required />
          </div>
          <button type="submit" name="submit" class="signup-btn">Sign up</button>
        </form>
        <div class="login">
          <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
      </div>
    </div>

  </body>
</html>
<?php
if (isset($_POST['submit'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  if ($password == $confirm_password) {
    // Insert the new account into the database
    $query = "INSERT INTO account(id_account, email, password, akses) VALUES('', '$email', md5('$password'), 2)";
    
    if (mysqli_query($conn, $query)) {
      echo "<script>
        Swal.fire({
          title: 'Pendaftaran Berhasil!',
          text: 'Akun Anda telah berhasil didaftarkan.',
          icon: 'success',
          confirmButtonText: 'Ok'
        }).then(function() {
          window.location.href = 'login.php';
        });
      </script>";
    } else {
      echo "<script>
        Swal.fire({
          title: 'Pendaftaran Gagal!',
          text: 'Terjadi kesalahan saat mendaftar.',
          icon: 'error',
          confirmButtonText: 'Ok'
        }).then(function() {
          window.location.href = 'signup.php';
        });
      </script>";
    }
  } else {
    echo "<script>
      Swal.fire({
        title: 'Password Tidak Sama!',
        text: 'Pastikan password dan konfirmasi password Anda sama.',
        icon: 'error',
        confirmButtonText: 'Ok'
      }).then(function() {
        window.location.href = 'signup.php';
      });
    </script>";
  }
}
?>
