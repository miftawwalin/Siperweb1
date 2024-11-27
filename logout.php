<?php
require "connection/koneksi.php";
include "navbar.php";

session_start();

// Unset all session variables
unset($_SESSION['id']);
unset($_SESSION['email']);

// Destroy the session
session_destroy();

// Output SweetAlert script and redirect
echo "<script>
    Swal.fire({
        title: 'Logout Berhasil!',
        text: 'Anda Berhasil Logout.',
        icon: 'success',
        confirmButtonText: 'OK'
    }).then(function() {
        window.location.href = 'login.php'; // Redirect to login page after SweetAlert confirmation
    });
</script>";
exit; // Prevent further script execution after redirection
?>
