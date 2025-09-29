<?php
include '../koneksi.php';

$nama = $_POST['anggota_id'];
$tanggal_pengajuan = $_POST['tanggal_pengajuan'];
$nominal = $_POST['jumlah_pinjaman'];
$tenor = $_POST['tenor'];

$query = mysqli_query($koneksi, "INSERT INTO pinjaman (anggota_id, tanggal_pengajuan, jumlah_pinjaman, tenor, status) VALUES ('$nama', '$tanggal_pengajuan', '$nominal', '$tenor', 'pending')");
header("location:../admin/pinjaman/pinjaman.php");
?>