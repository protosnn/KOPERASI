<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $query = "DELETE FROM anggota WHERE id = $id";
    
    if (mysqli_query($koneksi, $query)) {
        header("Location: anggota.php?pesan=hapus_berhasil");
    } else {
        header("Location: anggota.php?pesan=hapus_gagal");
    }
} else {
    header("Location: anggota.php?pesan=id_tidak_valid");
}
?>