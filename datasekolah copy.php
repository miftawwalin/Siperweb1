<?php
require "connection/koneksi.php"; // Ensure this file contains a valid database connection

$records_per_page = 5; 
// Check if we are trying to delete a record
if (isset($_GET['id_delete'])) {
    $id_buku = $_GET['id_delete'];

    // Prepare and execute the DELETE query
    $query = "DELETE FROM tbldata_buku WHERE id_buku = '$id_buku'";
    if (mysqli_query($conn, $query)) {
        // Successful deletion, redirect back with a success message
        header("Location: databuku.php?status=success");
        exit;
    } else {
        // If there is an error with deletion
        header("Location: databuku.php?status=error");
        exit;
    }
}
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

$judul_search = isset($_GET['judul']) ? mysqli_real_escape_string($conn, $_GET['judul']) : '';
$penulis_search = isset($_GET['penulis']) ? mysqli_real_escape_string($conn, $_GET['penulis']) : '';
$nm_sekolah_search = isset($_GET['nm_sekolah']) ? mysqli_real_escape_string($conn, $_GET['nm_sekolah']) : '';

// Fetch data from the database
$query = "SELECT * FROM tbldata_buku left join tbldata_sekolah on fk_induk=no_induk";

if ($judul_search) {
  $query .= " AND LOWER(judul) LIKE LOWER('%$judul_search%')";
}

// If 'penulis' search is provided, add it to the query (case-insensitive search)
if ($penulis_search) {
  $query .= " AND LOWER(penulis) LIKE LOWER('%$penulis_search%')";
}

// If 'nm_sekolah' search is provided, add it to the query (case-insensitive search)
if ($nm_sekolah_search) {
  $query .= " AND LOWER(nm_sekolah) LIKE LOWER('%$nm_sekolah_search%')";
}

$query .= " LIMIT $offset, $records_per_page";

$result = mysqli_query($conn, $query);

// Check if query is successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$count_query = "SELECT COUNT(*) AS total_records FROM tbldata_buku LEFT JOIN tbldata_sekolah ON fk_induk=no_induk WHERE 1";
if ($judul_search) {
    $count_query .= " AND LOWER(judul) LIKE LOWER('%$judul_search%')";
}
if ($penulis_search) {
    $count_query .= " AND LOWER(penulis) LIKE LOWER('%$penulis_search%')";
}
if ($nm_sekolah_search) {
    $count_query .= " AND LOWER(nm_sekolah) LIKE LOWER('%$nm_sekolah_search%')";
}
$count_result = mysqli_query($conn, $count_query);
$total_records = mysqli_fetch_assoc($count_result)['total_records'];
$total_pages = ceil($total_records / $records_per_page);

$no = $offset + 1;

$activePage = basename($_SERVER['PHP_SELF'], ".php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Perpustakaan Daerah</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js"></script>
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
                <div class="container-fluid" style="padding-top:1.5rem;">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Data Sekolah</h6>
                        </div>
                        <div class="card-body">
                            <!-- Search Form - Collapsible -->
                            <div class="collapse" id="searchFormCol">
                                <form method="GET" class="mb-3" id="searchForm">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="judul" placeholder="Cari Judul" value="<?= isset($_GET['judul']) ? $_GET['judul'] : '' ?>" id="judul">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="penulis" placeholder="Cari Penulis" value="<?= isset($_GET['penulis']) ? $_GET['penulis'] : '' ?>" id="penulis">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="nm_sekolah" placeholder="Cari Sekolah" value="<?= isset($_GET['nm_sekolah']) ? $_GET['nm_sekolah'] : '' ?>" id="nm_sekolah">
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Button to Add New School -->
                            <div class="row">
                                <div class="mb-3 ml-3">
                                    <a class="btn btn-primary" href="buku.php">Tambah Buku</a>
                                </div>
                                <div class="mb-3 ml-3">
                                    <a class="btn btn-primary mb-3" type="button" data-toggle="collapse" data-target="#searchFormCol" aria-expanded="false" aria-controls="searchFormCol">
                                    <i class="fas fa-filter"></i></a>
                                </div>
                            </div>

                            <!-- Table to display data -->
                            <div class="table-responsive" id="tableContainer">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Penulis</th>
                                        <th>Penerbit</th>
                                        <th>Tahun Terbit</th>
                                        <th>Asal Sekolah</th>
                                        <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            // Initialize row counter
                                            $no = 1;

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
                                                <td><?=$row['nm_sekolah'];?></td>
                                                <td>
                                                    <!-- Button for Edit and Delete actions -->
                                                    <button class="btn btn-primary" onclick="window.location.href='buku.php?id_edit=<?=$row['id_buku'];?>'">Edit</button>
                                                    <a href="#" class="btn btn-danger" onclick="deleteData('<?=$row['id_buku'];?>')">Delete</a>
                                                </td>
                                            </tr>
                                        <?php
                                                $no++; // Increment the row counter
                                            }
                                        } else {
                                            echo "<tr><td colspan='7' class='text-center'>No data available</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="databuku.php?page=<?= $page - 1 ?>&judul=<?= $judul_search ?>&penulis=<?= $penulis_search ?>&nm_sekolah=<?= $nm_sekolah_search ?>">Previous</a>
                                    </li>
                                    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                                    <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">
                                        <a class="page-link" href="databuku.php?page=<?= $page + 1 ?>&judul=<?= $judul_search ?>&penulis=<?= $penulis_search ?>&nm_sekolah=<?= $nm_sekolah_search ?>">Next</a>
                                    </li>
                                    <?php } ?>
                                    <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="databuku.php?page=<?= $page + 1 ?>&judul=<?= $judul_search ?>&penulis=<?= $penulis_search ?>&nm_sekolah=<?= $nm_sekolah_search ?>">Next<a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- End of Data Sekolah Section -->
            </div>
            <!-- End Content -->
        </div>
    </div>

    <script>
      // JavaScript function to handle deletion with SweetAlert2
      function deleteData(id_buku) {
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
            window.location.href = 'databuku.php?id_delete=' + id_buku;
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
          text: 'Data sekolah telah dihapus.',
        });
      <?php } elseif (isset($_GET['status']) && $_GET['status'] == 'error') { ?>
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          text: 'Terjadi kesalahan saat menghapus data.',
        });
      <?php } ?>

      // AJAX to filter data
      $(document).ready(function(){
        $('#judul, #penulis, #nm_sekolah').on('input', function() {
          var judul = $('#judul').val();
          var penulis = $('#penulis').val();
          var nm_sekolah = $('#nm_sekolah').val();
          
          $.ajax({
            url: 'databuku.php',
            method: 'GET',
            data: {
              judul: judul,
              penulis: penulis,
              nm_sekolah: nm_sekolah
            },
            success: function(response) {
              $('#tableContainer').html($(response).find('#tableContainer').html());
            }
          });
        });
      });
    </script>
</body>
</html>

