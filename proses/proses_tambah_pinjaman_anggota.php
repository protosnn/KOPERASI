<?php
session_start();
include '../koneksi.php';

// Cek jika user sudah login sebagai anggota
if(!isset($_SESSION['status']) || $_SESSION['status'] != "login_anggota") {
    header("location: ../anggota/login.php?pesan=belum_login");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $anggota_id = $_POST['anggota_id'];
    $jumlah_pinjaman = $_POST['jumlah_pinjaman'];
    $tenor = $_POST['lama_angsuran'];
    $tanggal_pengajuan = date('Y-m-d');

    // Validasi input
    if (empty($anggota_id) || empty($jumlah_pinjaman) || empty($tenor)) {
        header("location: ../anggota/pengajuan.php?pesan=data_tidak_lengkap");
        exit();
    }

    if ($jumlah_pinjaman < 100000) {
        header("location: ../anggota/pengajuan.php?pesan=jumlah_minimal");
        exit();
    }

    try {
        // Insert pinjaman dengan status pending
        $query = "INSERT INTO pinjaman (anggota_id, tanggal_pengajuan, jumlah_pinjaman, tenor, status) 
                  VALUES ('$anggota_id', '$tanggal_pengajuan', '$jumlah_pinjaman', '$tenor', 'pending')";
        
        if (mysqli_query($koneksi, $query)) {
            header("location: ../anggota/pengajuan.php?pesan=pengajuan_berhasil");
        } else {
            error_log("Database error: " . mysqli_error($koneksi));
            header("location: ../anggota/pengajuan.php?pesan=pengajuan_gagal");
        }
    } catch (Exception $e) {
        error_log("Database error: " . $e->getMessage());
        header("location: ../anggota/pengajuan.php?pesan=error");
    }
} else {
    header("location: ../anggota/pengajuan.php");
}

mysqli_close($koneksi);
?>
