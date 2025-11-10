<?php
include '../koneksi.php';

$id = $_POST['id'];
$nama_anggota = $_POST['nama'];
$password = $_POST['password'];
$alamat= $_POST['almat'];
$telepon = $_POST['telpon'];
$username = $_POST['username'];

$query = mysqli_query($koneksi, "INSERT INTO anggota (id, nama, password, almat, telpon, username) VALUES ('$id', '$nama_anggota', '$password', '$alamat', '$telepon', '$username')");
header("location:../admin/anggota.php");
?>