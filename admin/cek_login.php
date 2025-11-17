<?php
session_start();

// Cek apakah user sudah login
if(!isset($_SESSION['login']) || $_SESSION['login'] != true) {
    header("Location: index.php");
    exit();
}

// Cek tipe user untuk redirect yang sesuai
$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF']));

if($_SESSION['user_type'] == 'admin') {
    // Jika admin mengakses halaman home anggota, redirect ke dashboard admin
    if($current_page == 'home.php' && $current_dir != 'admin') {
        header("Location: admin/dashboard.php");
        exit();
    }
} elseif($_SESSION['user_type'] == 'anggota') {
    // Jika anggota mengakses halaman admin, redirect ke home
    if($current_dir == 'admin') {
        header("Location: ../home.php");
        exit();
    }
}
?>