<?php
session_start();
include 'koneksi.php';

// Ambil data dari form login
$username = $_POST['username'];
$password = $_POST['password'];

// Query sederhana untuk cek login
$query = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
$hasil = mysqli_query($koneksi, $query);

// Cek hasil query
if(mysqli_num_rows($hasil) > 0){
    // Jika login berhasil
    $_SESSION['login'] = true;
    echo "<script>
            alert('Login Berhasil!');
            window.location = 'admin/dashboard.php';
          </script>";
} else {
    // Jika login gagal
    echo "<script>
            alert('Username atau Password salah!');
            window.location = 'index.php';
          </script>";
}
?>