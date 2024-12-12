<?php
require "connection/koneksi.php"; // Ensure this file contains a valid database connection

// Get the 'no_induk' (school identification) from the URL (or session) to filter books by school
$no_induk = isset($_GET['id_edit']) ? mysqli_real_escape_string($conn, $_GET['id_edit']) : '';

// Pagination settings for books data
$records_per_page = 5; // Adjust this to show the number of records per page

// Get the current page number from the URL, default to 1 if not set
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Fetch books data related to the specific school (no_induk)
$query = "SELECT * FROM tbldata_buku WHERE fk_induk = '$no_induk' LIMIT $offset, $records_per_page";

// Execute the query
$result = mysqli_query($conn, $query);

// Check if query is successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Calculate the total number of records for pagination
$count_query = "SELECT COUNT(*) AS total_records FROM tbldata_buku WHERE fk_induk = '$no_induk'";
$count_result = mysqli_query($conn, $count_query);
$total_records = mysqli_fetch_assoc($count_result)['total_records'];
$total_pages = ceil($total_records / $records_per_page);

// Initialize row counter for pagination
$no = $offset + 1;

// Fetch school name for display
$school_query = "SELECT nm_sekolah FROM tbldata_sekolah WHERE no_induk = '$no_induk'";
$school_result = mysqli_query($conn, $school_query);
$school_data = mysqli_fetch_assoc($school_result);
$nm_sekolah = $school_data['nm_sekolah'];

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

                <!-- Data Buku Sekolah Section -->
                <div class="container-fluid" style="padding-top:1.5rem;">
                  <div class="card shadow mb-4">
                      <div class="card-header py-3">
                          <h6 class="m-0 font-weight-bold text-primary">Data Buku Sekolah <?=$nm_sekolah?></h6>
                      </div>

                      <div class="card-body">
                            <a href="datasekolah.php" class="btn btn-primary mb-3">Kembali</a>

                          <div class="table-responsive" id="tableContainer">
                              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                  <thead>
                                      <tr>
                                          <th>No</th>
                                          <th>Judul Buku</th>
                                          <th>Penulis</th>
                                          <th>Penerbit</th>
                                          <th>Tahun Terbit</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <?php
                                          // Check if there are any results
                                          if (mysqli_num_rows($result) > 0) {
                                              // Loop through the result and populate the table
                                              while ($row = mysqli_fetch_assoc($result)) {
                                      ?>
                                          <tr>
                                              <td><?=$no?></td>
                                              <td><?=$row['judul'];?></td>
                                              <td><?=$row['penulis'];?></td>
                                              <td><?=$row['penerbit'];?></td>
                                              <td><?=$row['tahun_terbit'];?></td>
                                          </tr>
                                      <?php
                                                  $no++; // Increment the row counter
                                              }
                                          } else {
                                              echo "<tr><td colspan='5' class='text-center'>No books available for this school</td></tr>";
                                          }
                                      ?>
                                  </tbody>
                              </table>
                          </div>

                          <!-- Pagination Controls -->
                          <nav aria-label="Page navigation">
                              <ul class="pagination justify-content-center">
                                  <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                                      <a class="page-link" href="?page=<?= $page - 1; ?>&no_induk=<?= $no_induk; ?>">Previous</a>
                                  </li>
                                  <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                                      <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>"><a class="page-link" href="?page=<?= $i; ?>&no_induk=<?= $no_induk; ?>"><?= $i; ?></a></li>
                                  <?php } ?>
                                  <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                      <a class="page-link" href="?page=<?= $page + 1; ?>&no_induk=<?= $no_induk; ?>">Next</a>
                                  </li>
                              </ul>
                          </nav>
                      </div>
                  </div>
              </div>

                <!-- End of Data Buku Sekolah Section -->
            </div>
            <!-- End Content -->
        </div>
    </div>

    <script>
      // Show SweetAlert if status is present in the URL
      <?php if (isset($_GET['status']) && $_GET['status'] == 'success') { ?>
        Swal.fire({
          icon: 'success',
          title: 'Data Berhasil Dihapus!',
          text: 'Data buku telah dihapus.',
        });
      <?php } elseif (isset($_GET['status']) && $_GET['status'] == 'error') { ?>
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          text: 'Terjadi kesalahan saat menghapus data.',
        });
      <?php } ?>
    </script>
</body>
</html>
