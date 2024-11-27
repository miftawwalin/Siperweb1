<?php

?>
<!DOCTYPE html>  
<html lang="en">  
<head>  
  <meta charset="UTF-8">  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">  
  <title>Perpustakaan Daerah Kabupaten Karawang</title>  
  <link rel="stylesheet" href="css/admin.css">  
 
  </head>
  <body>
    <header>  
        <div class="logo">  
         <img src="logo.png" alt="Perpustakaan Daerah Kabupaten Karawang">  
        </div>  
        <div class="user-icon">  
         <img src="user-icon.png" alt="User Icon">  
        </div>  
       </header>  
       <div class="sidebar">  
        <div class="user-profile">  
         <img src="user-profile.png" alt="Anita Silvana">  
         <span>Administrator</span>
        </div>         
        <ul>  
          <li><a href="home.php">Home</a></li>  
          <li><a href="datasekolah.php">Data Sekolah</a></li>  
          <li><a href="databuku.php">Data Buku</a></li>  
          <li><a href="datapeminjaman.php">Peminjaman</a></li>  
          <li><a href="datapengembalian.php">Pengembalian</a></li>  
          <li><a href="dataadmin.php">Data Admin</a></li>  
         </ul>        
    </div>  
    <div class="main-content">  
     <h2>Upload Data Admin</h2>  
     <div class="tabs">  
      <button class="active" onclick="window.location.href='dataadmin.php'">Data Admin</button>  
     </div>  
     <form>  
      <label for="no-induk">No Induk:</label>  
      <input type="text" id="no-induk" name="no-induk">  
      <br>  
      <label for="nama">Nama:</label>  
      <input type="text" id="nama" name="nama">  
      <br>  
      <label for="jabatan">Jabatan:</label>  
      <input type="text" id="jabatan" name="jabatan">  
      <br> 
      <label for="no-telepon">No Telepon:</label>  
    <input type="text" id="no-telepon" name="no-telepon">  
    <br>  
    <button type="submit">Upload</button>  
   </form>  
  </div>  
</body>  
</html>
  </body>