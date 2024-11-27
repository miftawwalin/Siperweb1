<?php

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Perpustakaan Daerah Kabupaten Karawang</title>
    <link rel="stylesheet" href="css/pengembalian.css" />
  </head>
  <body>
    <header>
      <div class="logo">
        <img src="logo.png" alt="Perpustakaan Daerah Kabupaten Karawang" />
      </div>
      <div class="user-icon">
        <img src="user-icon.png" alt="User Icon" />
      </div>
    </header>
    <div class="sidebar">
      <div class="user-profile">
        <img src="user-profile.png" alt="Anita Silvana" />
        <span>Administrator</span>
      </div>
      <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="datasekolah.php">Data Sekolah</a></li>
        <li><a href="databuku.php">Data Buku</a></li>
        <li><a href="peminjaman.php">Peminjaman</a></li>
        <li><a href="pengembalian.php">Pengembalian</a></li>
        <li><a href="dataadmin.php">Data Admin</a></li>
      </ul>
    </div>
    <div class="main-content">
      <h2>Data Pengembalian</h2>
      <form>
        <label for="no-induk">No Induk:</label>
        <input type="text" id="no-induk" name="no-induk" />
        <br />
        <label for="id-buku">Id Buku:</label>
        <input type="text" id="id-buku" name="id-buku" />
        <br />
        <label for="judul-buku">Judul Buku:</label>
        <input type="text" id="judul-buku" name="judul-buku" />
        <br />
        <label for="tanggal-kembali">Tanggal Kembali:</label>
        <input type="text" id="tanggal-kembali" name="tanggal-kembali" />
        <br />
        <label for="denda">Denda:</label>
        <input type="text" id="denda" name="denda" />
        <br />
        <button type="submit">Upload</button>
      </form>
    </div>
  </body>
</html>
