<?php
session_start();

// Include file koneksi dengan path yang benar
include('../koneksi.php');

// Cek jika koneksi berhasil
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Ambil data dari form
$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = mysqli_real_escape_string($koneksi, $_POST['password']);

// Validasi input
if(empty($username) || empty($password)) {
    echo "<script>
        alert('Username dan password harus diisi!');
        window.location.href = 'login_anggota.php';
    </script>";
    exit();
}

// Query untuk cek user
$query = "SELECT * FROM anggota WHERE username = '$username' AND password = '$password'";
$result = mysqli_query($koneksi, $query);

// Cek jika query berhasil
if($result) {
    // Cek jika user ditemukan
    if(mysqli_num_rows($result) > 0) {
        // Login berhasil - arahkan langsung ke home.php
        $user_data = mysqli_fetch_assoc($result);
        
        // Set session
        $_SESSION['username'] = $username;
        $_SESSION['nama'] = $user_data['nama']; // menggunakan 'nama' untuk konsistensi
        $_SESSION['id_anggota'] = $user_data['id'];
        $_SESSION['status'] = "login_anggota";
        
        // Redirect ke home.php dengan alert sukses
        echo "<script>
            alert('Login berhasil! Selamat datang $user_data[nama]');
            window.location.href = 'home.php';
        </script>";
        exit();
    } else {
        // Login gagal - username/password salah
        echo "<script>
            alert('Username atau password salah! Silakan coba lagi.');
            window.location.href = 'login.php';
        </script>";
        exit();
    }
} else {
    // Error query
    echo "<script>
        alert('Terjadi kesalahan sistem. Silakan coba lagi.');
        window.location.href = 'login_anggota.php';
    </script>";
    exit();
}

mysqli_close($koneksi);
?>