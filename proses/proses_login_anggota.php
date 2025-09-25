<?php

include '../koneksi.php';
session_start();

$username = $_POST['username'];
$password = $_POST['password'];

$query = "SELECT * FROM anggota WHERE username='$username' AND password='$password'";
$result = mysqli_query($koneksi, $query);
if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
    $_SESSION['anggota_id'] = $row['id_anggota'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['nama_anggota'] = $row['nama_anggota'];
    header("Location: ../anggota/dashboard_angota.php");
    exit();
} else {
    header("Location: ../login.php?error=1");
    exit();
}
?>