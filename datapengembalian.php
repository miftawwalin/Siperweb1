<?php
require "connection/koneksi.php"; // Pastikan file ini berisi koneksi database yang valid

// Pagination settings
$records_per_page = 5; // Jumlah record per halaman

// Cek apakah ada penghapusan record
if (isset($_GET['id_delete'])) {
    $id_pengembalian = $_GET['id_delete'];  // Ambil ID pengembalian untuk dihapus

    // Query DELETE untuk menghapus record pengembalian
    $query = "DELETE FROM tblpengembalian WHERE id_pengembalian = '$id_pengembalian'"; // Gunakan $id_pengembalian untuk penghapusan
    if (mysqli_query($conn, $query)) {
        // Penghapusan berhasil, redirect dengan pesan sukses
        header("Location: datapengembalian.php?status=success");
        exit;
    } else {
        // Jika ada error dalam penghapusan
        header("Location: datapengembalian.php?status=error");
        exit;
    }
}

// Ambil nomor halaman dari URL, default ke 1 jika tidak ada
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Ambil query pencarian jika ada
$sekolah_search = isset($_GET['nm_sekolah']) ? mysqli_real_escape_string($conn, $_GET['nm_sekolah']) : '';
$buku_search = isset($_GET['nm_buku']) ? mysqli_real_escape_string($conn, $_GET['nm_buku']) : '';

// Query dasar untuk mengambil data pengembalian
$query = "SELECT p.id_pengembalian, p.fk_peminjaman, p.tgl_kembali, p.nilai_denda, 
                 pm.tgl_peminjam, s.nm_sekolah, b.judul as nm_buku
          FROM tblpengembalian p
          LEFT JOIN tblpeminjaman pm ON p.fk_peminjaman = pm.pk_id
          LEFT JOIN tbldata_sekolah s ON pm.fk_induk_sekolah = s.no_induk
          LEFT JOIN tbldata_buku b ON pm.fk_buku = b.id_buku
          WHERE 1";

// Menambahkan filter pencarian ke query
if ($sekolah_search) {
    $query .= " AND LOWER(s.nm_sekolah) LIKE LOWER('%$sekolah_search%')";
}

if ($buku_search) {
    $query .= " AND LOWER(b.judul) LIKE LOWER('%$buku_search%')";
}

// Terapkan pagination
$query .= " LIMIT $offset, $records_per_page";

$result = mysqli_query($conn, $query);

// Cek apakah query berhasil
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Hitung jumlah total record untuk pagination
$count_query = "SELECT COUNT(*) AS total_records FROM tblpengembalian p
                LEFT JOIN tblpeminjaman pm ON p.fk_peminjaman = pm.pk_id
                LEFT JOIN tbldata_sekolah s ON pm.fk_induk_sekolah = s.no_induk
                LEFT JOIN tbldata_buku b ON pm.fk_buku = b.id_buku
                WHERE 1";
if ($sekolah_search) {
    $count_query .= " AND LOWER(s.nm_sekolah) LIKE LOWER('%$sekolah_search%')";
}
if ($buku_search) {
    $count_query .= " AND LOWER(b.nm_buku) LIKE LOWER('%$buku_search%')";
}

$count_result = mysqli_query($conn, $count_query);
$total_records = mysqli_fetch_assoc($count_result)['total_records'];
$total_pages = ceil($total_records / $records_per_page);

// Inisialisasi nomor urut
$no = $offset + 1;

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

                <!-- Data Pengembalian Section -->
                <div class="container-fluid" style="padding-top:1.5rem;">
                  <div class="card shadow mb-4">
                      <div class="card-header py-3">
                          <h6 class="m-0 font-weight-bold text-primary">Data Pengembalian</h6>
                      </div>
                      <div class="card-body">
                          <!-- Search Form - Collapsible -->
                          <div class="collapse" id="searchFormCol">
                              <form method="GET" class="mb-3" id="searchForm">
                                  <div class="row">
                                      <div class="col-md-3">
                                          <input type="text" class="form-control" name="nm_sekolah" placeholder="Cari Sekolah" value="<?= isset($_GET['nm_sekolah']) ? $_GET['nm_sekolah'] : '' ?>" id="nm_sekolah">
                                      </div>
                                      <div class="col-md-3">
                                          <input type="text" class="form-control" name="nm_buku" placeholder="Cari Buku" value="<?= isset($_GET['nm_buku']) ? $_GET['nm_buku'] : '' ?>" id="nm_buku">
                                      </div>
                                  </div>
                              </form>
                          </div>

                          <div class="row">
                              <div class="mb-3 ml-3">
                                  <a class="btn btn-primary" href="pengembalian.php">Tambah Pengembalian</a>
                              </div>
                              <div class="ml-3">
                                  <a class="btn btn-primary mb-3" type="button" data-toggle="collapse" data-target="#searchFormCol" aria-expanded="false" aria-controls="searchFormCol">
                                  <i class="fas fa-filter"></i></a>
                              </div>
                          </div>

                          <!-- Display Total Records -->
                          <div class="row mb-1">
                              <div class="col">
                                  <strong>Total Data: </strong><?= $total_records; ?> Pengembalian
                              </div>
                          </div>

                          <!-- Table to display data -->
                          <div class="table-responsive" id="tableContainer">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Sekolah</th>
                                            <th>Nama Buku</th>
                                            <th>Tanggal Peminjaman</th>
                                            <th>Tanggal Kembali</th>
                                            <th>Nilai Denda</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            // Cek apakah ada hasil
                                            if (mysqli_num_rows($result) > 0) {
                                                // Loop dan tampilkan hasil
                                                while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                            <tr>
                                                <td><?=$row['id_pengembalian']?></td>
                                                <td><?=$row['nm_sekolah'];?></td>
                                                <td><?=$row['nm_buku'];?></td>
                                                <td><?=$row['tgl_peminjam'];?></td>
                                                <td><?=$row['tgl_kembali'];?></td>
                                                <td>
                                                    <input type="text" class="form-control denda-input" 
                                                        value="<?= 'Rp ' . number_format($row['nilai_denda'], 0, ',', '.'); ?>" 
                                                        data-id="<?= $row['id_pengembalian']; ?>"
                                                        id="denda_<?=$row['id_pengembalian'];?>" 
                                                        min="0" 
                                                        step="1000" />
                                                </td>

                                            </tr>
                                        <?php
                                                $no++; // Increment nomor urut
                                            }
                                        } else {
                                            echo "<tr><td colspan='7' class='text-center'>No data available</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                          <!-- Pagination Controls -->
                          <nav aria-label="Page navigation">
                              <ul class="pagination justify-content-center">
                                  <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                                      <a class="page-link" href="?page=<?= $page - 1; ?>&nm_sekolah=<?= $sekolah_search ?>&nm_buku=<?= $buku_search ?>">Previous</a>
                                  </li>
                                  <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                                      <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>"><a class="page-link" href="?page=<?= $i; ?>&nm_sekolah=<?= $sekolah_search ?>&nm_buku=<?= $buku_search ?>"><?= $i; ?></a></li>
                                  <?php } ?>
                                  <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                      <a class="page-link" href="?page=<?= $page + 1; ?>&nm_sekolah=<?= $sekolah_search ?>&nm_buku=<?= $buku_search ?>">Next</a>
                                  </li>
                              </ul>
                          </nav>
                      </div>
                  </div>
              </div>
              <!-- End of Data Pengembalian Section -->
            </div>
            <!-- End Content -->
        </div>
    </div>

    <script>
     $(document).ready(function() {
         // AJAX search handler for the search form
         $('#searchForm input').on('input', function() {
             var sekolah = $('#nm_sekolah').val(); // Get the sekolah search value
             var buku = $('#nm_buku').val(); // Get the buku search value

             // Make the AJAX request
             $.ajax({
                 url: 'datapengembalian.php',
                 method: 'GET',
                 data: {
                     nm_sekolah: sekolah,
                     nm_buku: buku,
                     page: 1 // Optional: Reset to page 1 if you want to apply the search from the first page
                 },
                 success: function(response) {
                     // Replace the table content with filtered results
                     $('#tableContainer').html($(response).find('#tableContainer').html());
                     // Optionally, you can replace pagination if needed
                     // $('#paginationContainer').html($(response).find('#paginationContainer').html());
                 }
             });
         });

         // Handle keyup event for real-time formatting as the user types
         $('.denda-input').on('keyup', function() {
             var value = $(this).val();
             
             // Remove any non-numeric characters except the period (for decimal input)
             value = value.replace(/[^\d]/g, '');
             
             // Format the number to Rupiah (add commas for thousands)
             value = numberWithCommas(value);
             
             // Prepend "Rp " to the formatted value
             $(this).val('Rp ' + value);
         });

         // Handle when the user finishes typing (change event)
         $('.denda-input').on('change', function() {
             var value = $(this).val();
             
             // Remove "Rp " and any commas for the raw numeric value
             value = value.replace(/[^0-9]/g, '');
             
             var idPengembalian = $(this).data('id');

             // Send the cleaned value (raw numeric value) to the server for saving
             $.ajax({
                 url: 'update_denda.php',  // The file that will handle the update
                 method: 'POST',
                 data: {
                     id_pengembalian: idPengembalian,
                     nilai_denda: value // Send the raw numeric value without "Rp"
                 },
                 success: function(response) {
                     if (response === 'success') {
                         Swal.fire({
                             icon: 'success',
                             title: 'Denda Updated!',
                             text: 'Denda has been successfully updated.',
                         });
                     } else {
                         Swal.fire({
                             icon: 'error',
                             title: 'Error!',
                             text: 'There was an issue updating the denda.',
                         });
                     }
                 },
                 error: function() {
                     Swal.fire({
                         icon: 'error',
                         title: 'Error!',
                         text: 'Something went wrong with the request.',
                     });
                 }
             });
         });

         // Function to format numbers with commas (for thousands separator)
         function numberWithCommas(x) {
             return x.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
         }
     });

    </script>
</body>
</html>
