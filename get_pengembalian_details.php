<?php
require "connection/koneksi.php";

// Check if the ID is provided
if (isset($_GET['id'])) {
    $fk_peminjaman = $_GET['id'];

    // Fetch the relevant data for the selected peminjaman
    $query = "
        SELECT 
            tbldata_sekolah.nm_sekolah, 
            tbldata_buku.judul AS nm_buku ,tgl_kembali,tgl_peminjam
        FROM tblpeminjaman 
        INNER JOIN tbldata_sekolah ON tblpeminjaman.fk_induk_sekolah = tbldata_sekolah.no_induk
        INNER JOIN tbldata_buku ON tblpeminjaman.fk_buku = tbldata_buku.id_buku
        WHERE tblpeminjaman.pk_id = '$fk_peminjaman'
    ";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode($row); // Return data as JSON
    } else {
        echo json_encode([]); // Return empty array if not found
    }
}
?>
