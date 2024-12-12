<?php
require "connection/koneksi.php"; // Make sure this file contains a valid database connection

// Check if POST data is available
if (isset($_POST['id_pengembalian']) && isset($_POST['nilai_denda'])) {
    $id_pengembalian = $_POST['id_pengembalian'];
    $nilai_denda = $_POST['nilai_denda']; // No need to remove "Rp", just use numeric value

    // Sanitize the input
    $id_pengembalian = mysqli_real_escape_string($conn, $id_pengembalian);
    $nilai_denda = mysqli_real_escape_string($conn, $nilai_denda);

    // Query to update the denda value
    $query = "UPDATE tblpengembalian SET nilai_denda = '$nilai_denda' WHERE id_pengembalian = '$id_pengembalian'";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
