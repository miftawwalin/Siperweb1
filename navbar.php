    
<?php
// Start the session to access the logged-in user's data
session_start();
// Check if the user is logged in
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];  // Get the logged-in user's email

    // Database query to fetch user data based on the logged-in email
    $login_query = "SELECT email, akses FROM account WHERE email = '$email'";
    $login_result = mysqli_query($conn, $login_query);

    // Check if query returned any result
    if (mysqli_num_rows($login_result) > 0) {
        // Fetch the user data
        $user_data = mysqli_fetch_assoc($login_result);
        $akses = $user_data['akses'];  // Get the user's role (akses)
        $user_email = $user_data['email'];  // Get the user's email

        // Optionally, get the user's name from the database (if available)
        // Assuming the user's name is stored in a column like 'name' in the account table
        // $user_name = $user_data['name'];
    } else {
        // If no user data found, redirect to login page (security)
        header("Location: login.php");
        exit();
    }
} else {
    // If the user is not logged in, redirect to login page
    header("Location: login.php");
    exit();
}
?>

<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
<link href="css/sb-admin-2.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/index.css" />
<link href="css/sweetalert2.min.css" rel="stylesheet">
<link href="css/all.min.css" rel="stylesheet" type="text/css">
<script src="js/jquery-3.6.0.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/sweetalert2.all.min.js"></script>
</ul>
<div class="sidebar">
    <div class="profile">
        <img src="img/profile.jpg" alt="Profile Picture" class="profile-pic" />
        <p class="profile-name"><?php echo $user_email?></p>
        <p class="profile-role">
            <?php 
                // Display role based on 'akses' field (admin, user, etc.)
                if ($akses == 1) {
                    echo "User";  // Example role for normal users
                } elseif ($akses == 2) {
                    echo "Administrator";  // Example role for admins
                } else {
                    echo "Unknown Role";
                }
            ?>
        </p>
    </div>
    <nav>
      <ul>
        <li>
        <a href="home.php" class="<?= ($activePage == 'home') ? 'active':''; ?>"><i class="fas fa-home"></i> Home</a>
        </li>
        <li>
          <a href="datasekolah.php" class="<?= ($activePage == 'datasekolah') ? 'active':''; ?>"><i class="fas fa-school"></i> Data Sekolah</a>
        </li>
        <li>
          <a href="databuku.php" class="<?= ($activePage == 'databuku') ? 'active':''; ?>"><i class="fas fa-book"></i> Data Buku</a>
        </li>
        <li>
          <a href="datapeminjaman.php" class="<?= ($activePage == 'datapeminjaman') ? 'active':''; ?>"><i class="fas fa-sign-out-alt"></i> Peminjaman</a>
        </li>
        <li>
          <a href="datapengembalian.php" class="<?= ($activePage == 'datapengembalian') ? 'active':''; ?>"><i class="fas fa-sign-in-alt"></i> Pengembalian</a>
        </li>
        <li>
          <a href='dataadmin.php' class="<?= ($activePage == 'dataadmin') ? 'active':''; ?>"><i class="fas fa-user-shield"></i> Data Admin</a>
        </li>
        <li>
          <a href="#" id="logoutBtn"><i class="fa fa-sign"></i> Logout</a>
        </li>
      </ul>
    </nav>
  </div>
  <script>
    document.getElementById('logoutBtn').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent default link behavior

        // SweetAlert Confirmation Dialog
        Swal.fire({
            title: 'Logout',
            text: 'Apakah anda akan Logout?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.isConfirmed) {
                // Proceed with logout (you can redirect to the logout script)
                window.location.href = 'logout.php';  // This page will handle the session destroy and redirect
            } else {
                // User canceled, stay on the current page
                Swal.fire('Canceled', 'You are still logged in.', 'info');
            }
        });
    });
</script>
