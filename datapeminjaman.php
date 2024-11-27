<?php
require "connection/koneksi.php"; // Pastikan file ini berisi koneksi database yang valid

// Pagination settings
$records_per_page = 5; // Jumlah record per halaman

// Cek apakah ada penghapusan record
if (isset($_GET['id_delete'])) {
    $pk_id = $_GET['id_delete'];  // Ambil ID peminjaman untuk dihapus

    // Query DELETE untuk menghapus record peminjaman
    $query = "DELETE FROM tblpeminjaman WHERE pk_id = '$pk_id'"; // Gunakan $pk_id untuk penghapusan
    if (mysqli_query($conn, $query)) {
        // Penghapusan berhasil, redirect dengan pesan sukses
        header("Location: datapeminjaman.php?status=success");
        exit;
    } else {
        // Jika ada error dalam penghapusan
        header("Location: datapeminjaman.php?status=error");
        exit;
    }
}

// Ambil nomor halaman dari URL, default ke 1 jika tidak ada
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Ambil query pencarian jika ada
$sekolah_search = isset($_GET['nm_sekolah']) ? mysqli_real_escape_string($conn, $_GET['nm_sekolah']) : '';
$buku_search = isset($_GET['buku']) ? mysqli_real_escape_string($conn, $_GET['buku']) : '';

// Query dasar untuk mengambil data peminjaman
// Query dasar untuk mengambil data peminjaman dengan LEFT JOIN
$query = "SELECT pk_id, fk_induk_sekolah, fk_buku, tgl_peminjam, tgl_kembali, 
            s.nm_sekolah, b.judul 
          FROM tblpeminjaman p
          LEFT JOIN tbldata_sekolah s ON p.fk_induk_sekolah = s.no_induk
          LEFT JOIN tbldata_buku b ON p.fk_buku = b.id_buku
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
$count_query = "SELECT COUNT(*) AS total_records FROM tblpeminjaman WHERE 1";
if ($sekolah_search) {
    $count_query .= " AND LOWER(fk_induk_sekolah) LIKE LOWER('%$sekolah_search%')";
}
if ($buku_search) {
    $count_query .= " AND LOWER(fk_buku) LIKE LOWER('%$buku_search%')";
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

                <!-- Data Peminjaman Section -->
                <div class="container-fluid" style="padding-top:1.5rem;">
                  <div class="card shadow mb-4">
                      <div class="card-header py-3">
                          <h6 class="m-0 font-weight-bold text-primary">Data Peminjaman</h6>
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
                                          <input type="text" class="form-control" name="buku" placeholder="Cari Buku" value="<?= isset($_GET['buku']) ? $_GET['buku'] : '' ?>" id="buku">
                                      </div>
                                  </div>
                              </form>
                          </div>

                          <!-- Button to Add New Peminjaman -->
                          <div class="row">
                              <div class="mb-3 ml-3">
                                  <a class="btn btn-primary" href="peminjaman.php">Tambah Peminjaman</a>
                              </div>
                              <div class="ml-3">
                                  <a class="btn btn-primary mb-3" type="button" data-toggle="collapse" data-target="#searchFormCol" aria-expanded="false" aria-controls="searchFormCol">
                                  <i class="fas fa-filter"></i></a>
                              </div>
                          </div>
                          <!-- Display Total Records -->
                          <div class="row mb-1">
                              <div class="col">
                                  <strong>Total Data: </strong><?= $total_records; ?> Peminjaman
                              </div>
                          </div>

                          <!-- Table to display data -->
                          <div class="table-responsive" id="tableContainer">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Induk Sekolah</th>
                                            <th>Nama Sekolah</th>
                                            <th>Buku</th>
                                            <th>Tanggal Peminjaman</th>
                                            <th>Tanggal Kembali</th>
                                            <th>Action</th>
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
                                                <td><?=$no?></td>
                                                <td><?=$row['fk_induk_sekolah'];?></td>
                                                <td><?=$row['nm_sekolah'];?></td>  <!-- Menampilkan nama sekolah -->
                                                <td><?=$row['judul'];?></td>  <!-- Menampilkan judul buku -->
                                                <td><?=$row['tgl_peminjam'];?></td>
                                                <td><?=$row['tgl_kembali'];?></td>
                                                <td>
                                                    <!-- Button untuk Edit dan Hapus -->
                                                    <button class="btn btn-primary" onclick="window.location.href='peminjaman.php?id_edit=<?=$row['pk_id'];?>'">Edit</button>
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
                                      <a class="page-link" href="?page=<?= $page - 1; ?>&nm_sekolah=<?= $sekolah_search ?>&buku=<?= $buku_search ?>">Previous</a>
                                  </li>
                                  <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                                      <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>"><a class="page-link" href="?page=<?= $i; ?>&nm_sekolah=<?= $sekolah_search ?>&buku=<?= $buku_search ?>"><?= $i; ?></a></li>
                                  <?php } ?>
                                  <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                      <a class="page-link" href="?page=<?= $page + 1; ?>&nm_sekolah=<?= $sekolah_search ?>&buku=<?= $buku_search ?>">Next</a>
                                  </li>
                              </ul>
                          </nav>
                      </div>
                  </div>
              </div>
              <!-- End of Data Peminjaman Section -->
            </div>
            <!-- End Content -->
        </div>
    </div>

    <script>
      // JavaScript function to handle deletion with SweetAlert2
      function deleteData(pk_id) {
        Swal.fire({
          title: 'Apakah Anda yakin?',
          text: "Data ini akan dihapus dan tidak dapat dikembalikan!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, Hapus!',
          cancelButtonText: 'Batal',
          reverseButtons: true
        }).then((result) => {
          if (result.isConfirmed) {
            // Redirect to the PHP page with the 'id_delete' parameter
            window.location.href = 'datapeminjaman.php?id_delete=' + pk_id;
          } else {
            Swal.fire(
              'Dibatalkan',
              'Data tidak dihapus.',
              'info'
            );
          }
        });
      }

      // Show SweetAlert if status is present in the URL
      <?php if (isset($_GET['status']) && $_GET['status'] == 'success') { ?>
        Swal.fire({
          icon: 'success',
          title: 'Data Berhasil Dihapus!',
          text: 'Data peminjaman telah dihapus.',
        });
      <?php } elseif (isset($_GET['status']) && $_GET['status'] == 'error') { ?>
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          text: 'Terjadi kesalahan saat menghapus data.',
        });
      <?php } ?>

      // JavaScript for AJAX search and SweetAlert
      $(document).ready(function() {
        // AJAX search handler
        $('#searchForm input').on('input', function() {
          var sekolah = $('#nm_sekolah').val();
          var buku = $('#buku').val();

          // AJAX request to fetch filtered data
          $.ajax({
            url: 'datapeminjaman.php',
            method: 'GET',
            data: {
              nm_sekolah: sekolah,
              buku: buku,
            },
            success: function(response) {
              // Replace the table content with filtered results
              $('#tableContainer').html($(response).find('#tableContainer').html());
            }
          });
        });
      });
    </script>
</body>
</html>
