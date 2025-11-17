<?php
session_start();
include 'koneksi.php';

// Ambil data dari form login
$username = $_POST['username'];
$password = $_POST['password'];

// Validasi input
if(empty($username) || empty($password)) {
    echo "<script>
            alert('Username dan Password harus diisi!');
            window.location = 'index.php';
          </script>";
    exit();
}

// CEK LOGIN ADMIN TERLEBIH DAHULU
$query_admin = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
$hasil_admin = mysqli_query($koneksi, $query_admin);

if(mysqli_num_rows($hasil_admin) > 0){
    // Jika login admin berhasil 
    $data_admin = mysqli_fetch_array($hasil_admin);
    $_SESSION['login'] = true;
    $_SESSION['user_type'] = 'admin';
    $_SESSION['user_id'] = $data_admin['id'];
    $_SESSION['username'] = $data_admin['username'];
    $_SESSION['nama'] = $data_admin['username']; // Jika tidak ada kolom nama, gunakan username
    
    echo "<script>
            alert('Login Admin Berhasil!');
            window.location = 'admin/dashboard.php';
          </script>";
    
} else {
    // JIKA BUKAN ADMIN, CEK ANGGOTA
    $query_anggota = "SELECT * FROM anggota WHERE username='$username' AND password='$password'";
    $hasil_anggota = mysqli_query($koneksi, $query_anggota);
    
    if(mysqli_num_rows($hasil_anggota) > 0){
        // Jika login anggota berhasil
        $data_anggota = mysqli_fetch_array($hasil_anggota);
        $_SESSION['login'] = true;
        $_SESSION['user_type'] = 'anggota';
        $_SESSION['user_id'] = $data_anggota['id'];
        $_SESSION['username'] = $data_anggota['username'];
        $_SESSION['nama'] = $data_anggota['nama'];
        $_SESSION['alamat'] = $data_anggota['almat'];
        $_SESSION['telpon'] = $data_anggota['telpon'];
        
        echo "<script>
                alert('Login Anggota Berhasil!');
                window.location = './angoota/home.php';
              </script>";
              
    } else {
        // Jika kedua login gagal
        echo "<script>
                alert('Username atau Password salah!');
                window.location = 'index.php';
              </script>";
    }
}
?>