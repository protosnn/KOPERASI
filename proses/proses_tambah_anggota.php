<?php
include '../koneksi.php';

$id = $_POST['id'];
$nama_anggota = $_POST['nama'];
$password = $_POST['password'];
$alamat= $_POST['alamat'];
$telepon = $_POST['telpon'];
$username = $_POST['username'];

$query = mysqli_query($koneksi, "INSERT INTO anggota (id, nama, password, alamat, telpon, username, status) VALUES ('$id', '$nama_anggota', '$password', '$alamat', '$telepon', '$username', 'pending')");
header("location:../admin/anggota.php");
?>