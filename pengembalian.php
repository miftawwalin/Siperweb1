<?php
require "connection/koneksi.php"; // Include the database connection

// Check if the form is submitted via POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and escape form data to prevent SQL injection
    $id_edit = $_POST['id_edit'];
    $fk_peminjaman = $_POST['fk_peminjaman'];
    $nilai_denda = $_POST['nilai_denda'];
    $tgl_kembali = $_POST['tgl_kembali'];
    
    // Check if we're updating an existing record
    if ($id_edit) {
        // Update the existing record in tblpengembalian
        $query = "UPDATE tblpengembalian 
                  SET nilai_denda = '$nilai_denda', tgl_kembali = '$tgl_kembali'
                  WHERE id_pengembalian = '$id_edit'";
        
        if (mysqli_query($conn, $query)) {
            echo 'success|Data Berhasil di Update';
        } else {
            echo 'error|Error updating data: ' . mysqli_error($conn);
        }
    } else {
        // Insert a new record into tblpengembalian
        $query = "INSERT INTO tblpengembalian (fk_peminjaman, nilai_denda, tgl_kembali) 
                  VALUES ('$fk_peminjaman', '$nilai_denda', '$tgl_kembali')";
        
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
    $query = "SELECT * FROM tblpengembalian WHERE id_pengembalian = '$id_edit'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $id_pengembalian = $row['id_pengembalian'];
        $fk_peminjaman = $row['fk_peminjaman'];
        $nilai_denda = $row['nilai_denda'];
        $tgl_kembali = date("Y-m-d", strtotime($row['tgl_kembali']));
    } else {
        echo 'error|Record not found!';
        exit;
    }
} else {
    // If no id_edit parameter, initialize form with empty values (for adding new record)
    $id_pengembalian = '';
    $fk_peminjaman = '';
    $nilai_denda = '';
    $tgl_kembali = '';
}

// Fetch all the borrow records that have not yet been returned
$pinjam_query = "SELECT * from (
    select pk_id, fk_induk_sekolah, fk_buku from tblpeminjaman where pk_id not in (select fk_peminjaman from tblpengembalian)
) as tblpinjam";
$pinjam_result = mysqli_query($conn, $pinjam_query);
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
                    <form id="pengembalianForm">
                        <!-- Hidden field to hold the pk_id for editing -->
                        <input type="hidden" name="id_edit" value="<?= isset($id_pengembalian) ? $id_pengembalian : '' ?>">

                        <div class="form-group">
                            <label for="fk_peminjaman">Data Peminjam</label>
                            <select class="form-control" id="fk_peminjaman" name="fk_peminjaman" required>
                                <option value="">-- Pilih Data --</option>
                                <?php while ($pinjam = mysqli_fetch_assoc($pinjam_result)) { ?>
                                    <option value="<?= $pinjam['pk_id'] ?>" <?= ($pinjam['pk_id'] == $fk_peminjaman) ? 'selected' : '' ?>>
                                        <?= $pinjam['fk_induk_sekolah'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="nm_sekolah">Sekolah</label>
                            <input type="text" class="form-control col-4" id="nm_sekolah" name="nm_sekolah" value="<?= isset($nm_sekolah) ? $nm_sekolah : '' ?>" readonly required>
                        </div>

                        <div class="form-group">
                            <label for="nm_buku">Buku</label>
                            <input type="text" class="form-control col-4" id="nm_buku" name="nm_buku" value="<?= isset($nm_buku) ? $nm_buku : '' ?>" readonly required>
                        </div>

                        <div class="form-group">
                            <label for="tgl_pinjam">Tanggal Pinjam</label>
                            <input type="text" class="form-control col-4" id="tgl_pinjam" name="tgl_pinjam" value="<?= isset($tgl_pinjam) ? $tgl_pinjam : '' ?>" readonly required>
                        </div>

                        <div class="form-group">
                            <label for="tgl_akan_dikembalikan">Tanggal Akan Dikembalikan</label>
                            <input type="text" class="form-control col-4" id="tgl_akan_dikembalikan" name="tgl_akan_dikembalikan" value="<?= isset($tgl_akan_dikembalikan) ? $tgl_akan_dikembalikan : '' ?>" readonly required>
                        </div>


                        <div class="form-group">
                            <label for="tgl_kembali">Tanggal Pengembalian</label>
                            <input type="date" class="form-control col-4" id="tgl_kembali" name="tgl_kembali" value="<?= $tgl_kembali ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="nilai_denda">Denda</label>
                            <input type="text" class="form-control col-4" id="nilai_denda" name="nilai_denda" value="<?= $nilai_denda ?? '0' ?>" required />
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

      document.getElementById('nilai_denda').addEventListener('input', function(event) {
          var value = event.target.value;

          // Remove any non-numeric characters except commas and periods
          value = value.replace(/[^0-9]/g, '');

          // Format the value as Rupiah
          value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Adds thousands separator

          // Display the formatted value
          event.target.value = value ? 'Rp ' + value : ''; // If there's a value, prepend "Rp"
      });

      document.getElementById('save').addEventListener('click', function() {
          // Get the value of 'nilai_denda' input and remove "Rp" and formatting
          var nilai_denda = document.getElementById('nilai_denda').value.replace(/[^0-9]/g, '');

          // Set the cleaned value back to the input field before submitting
          document.getElementById('nilai_denda').value = nilai_denda;

          // Continue with the form submission process...
      });
 
      document.getElementById('fk_peminjaman').addEventListener('change', function() {
        var fk_peminjaman = this.value;

        if (fk_peminjaman) {
            fetch('get_pengembalian_details.php?id=' + fk_peminjaman)
                .then(response => response.json())
                .then(data => {
                    // Populate school and book details
                    document.getElementById('nm_sekolah').value = data.nm_sekolah;
                    document.getElementById('nm_buku').value = data.nm_buku;
                    
                    // Format the dates to d/m/Y format and display
                    var tglPinjam = new Date(data.tgl_peminjam);
                    var tglAkanDikembalikan = new Date(data.tgl_kembali);

                    // Convert to d/m/Y format
                    var formattedTglPinjam = tglPinjam.getDate().toString().padStart(2, '0') + '/' + (tglPinjam.getMonth() + 1).toString().padStart(2, '0') + '/' + tglPinjam.getFullYear();
                    var formattedTglAkanDikembalikan = tglAkanDikembalikan.getDate().toString().padStart(2, '0') + '/' + (tglAkanDikembalikan.getMonth() + 1).toString().padStart(2, '0') + '/' + tglAkanDikembalikan.getFullYear();

                    // Populate the date fields
                    document.getElementById('tgl_pinjam').value = formattedTglPinjam;
                    document.getElementById('tgl_akan_dikembalikan').value = formattedTglAkanDikembalikan;
                })
                .catch(error => {
                    console.log('Error fetching data:', error);
                });
        }
      });



      document.getElementById('save').addEventListener('click', function() {
        // Validate the form fields
        var fk_peminjaman = document.getElementById('fk_peminjaman').value;
        var tgl_kembali = document.getElementById('tgl_kembali').value;
        var nilai_denda = document.getElementById('nilai_denda').value;

        // Check if any field is empty
        if (fk_peminjaman == '') {
            Swal.fire('Error!', 'Data Peminjam kosong.', 'error');
            return;
        }
        if (tgl_kembali == '') {
            Swal.fire('Error!', 'Tanggal Kembali kosong.', 'error');
            return;
        }
        if (nilai_denda == '') {
            Swal.fire('Error!', 'Nilai Denda kosong.', 'error');
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
            var formData = new FormData(document.getElementById('pengembalianForm'));

            fetch('pengembalian.php', {
              method: 'POST',
              body: formData
            })
            .then(response => response.text())
            .then(data => {
              const [status, message] = data.split('|');
              if (status === 'success') {
                Swal.fire('Data Tersimpan!', message, 'success')
                        .then(() => {
                            // Redirect to the datalaporan.php page after success
                            window.location.href = 'datapengembalian.php';
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
