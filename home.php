<?php
require "connection/koneksi.php"; // Ensure this file contains a valid database connection

$count_query_buku = "SELECT COUNT(*) AS total_records FROM tbldata_buku";
$count_query_sekolah = "SELECT COUNT(*) AS total_records FROM tbldata_sekolah";

$count_buku = mysqli_query($conn, $count_query_buku);
$total_buku = mysqli_fetch_assoc($count_buku)['total_records'];
$count_sekolah = mysqli_query($conn, $count_query_sekolah);
$total_sekolah = mysqli_fetch_assoc($count_sekolah)['total_records'];

$activePage = basename($_SERVER['PHP_SELF'], ".php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Perpustakaan Daerah</title>
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

                <!-- Data Sekolah Section -->
                <div class="dashboard">
                  <div class="cardhome">
                    <div class="cardhome-icon">
                      <i class="fa fa-graduation-cap fa-3x"></i>
                    </div>
                    <div class="cardhome-details">
                      <p>Total Sekolah</p>
                      <h2><?=$total_sekolah;?></h2>
                    </div>
                  </div>
                  <div class="cardhome">
                    <div class="cardhome-icon">
                      <i class="fa fa-book fa-3x"></i>
                    </div>
                    <div class="cardhome-details">
                      <p>Total Buku</p>
                      <h2><?=$total_buku;?></h2>
                    </div>
                  </div>
                </div>
            <!-- End Content -->
        </div>
    </div>
</body>
</html>
<?php

?>