<?php
require "connection/koneksi.php";
$activePage = basename($_SERVER['PHP_SELF'], ".php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Perpustakaan Daerah</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css" />
  </head>
  <body>
    <div class="row">
      <!-- Sidebar -->
        <?php 
          include 'navbar.php';
        ?>
      <!-- Akhir Sidebar -->
      <!-- Main content -->
      <div class="main-content">
        <header>
          <h1>Perpustakaan Daerah Kabupaten Karawang</h1>
          <div class="user-icon">
            <img src="img/LAMBANG_KABUPATEN_KARAWANG.png" alt="User Icon" />
          </div>
        </header>
          <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Data Sekolah</h6>
          </div>
          <div class="card-body">
              <div class="table-responsive">
                  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                  <tr>
                    <th>No</th>
                    <th>No Induk</th>
                    <th>Id Buku</th>
                    <th>Judul Buku</th>
                    <th>Tanggal Kembali</th>
                    <th>Denda</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                    <tr>
                      <td>1</td>
                      <td>12345678</td>
                      <td>1234567</td>
                      <td>A</td>
                      <td>25/10/2024</td>
                      <td>
                        <button class="btn btn-primary">Edit</button>
                        </td>
                    </tr>
                  </tbody>
                  </table>
              </div>
          </div>
      </div>
      <!-- Akhir Main Content -->
    </div>
  </body>
</html>