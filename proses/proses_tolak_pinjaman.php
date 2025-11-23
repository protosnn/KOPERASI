<?php
session_start();
include '../koneksi.php';

// Cek jika user adalah admin
if(!isset($_SESSION['login']) || $_SESSION['login'] != true || $_SESSION['user_type'] != 'admin') {
    header("location: ../admin/dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pinjaman_id = $_POST['pinjaman_id'];
    $alasan_penolakan = isset($_POST['alasan_penolakan']) ? $_POST['alasan_penolakan'] : 'Pinjaman ditolak oleh admin';

    // Validasi input
    if (empty($pinjaman_id)) {
        header("location: ../admin/pinjaman/pinjaman.php?pesan=data_tidak_lengkap");
        exit();
    }

    try {
        // Get data pinjaman terlebih dahulu
        $get_pinjaman_query = "SELECT anggota_id, jumlah_pinjaman FROM pinjaman WHERE id = '$pinjaman_id'";
        $result_pinjaman = mysqli_query($koneksi, $get_pinjaman_query);
        
        if (!$result_pinjaman || mysqli_num_rows($result_pinjaman) == 0) {
            header("location: ../admin/pinjaman/pinjaman.php?pesan=pinjaman_tidak_ditemukan");
            exit();
        }

        $pinjaman_data = mysqli_fetch_assoc($result_pinjaman);
        $anggota_id = $pinjaman_data['anggota_id'];

        // Update status pinjaman menjadi tolak
        $update_query = "UPDATE pinjaman SET status = 'tolak' WHERE id = '$pinjaman_id'";
        
        if (mysqli_query($koneksi, $update_query)) {
            // Buat notifikasi untuk anggota
            $pesan = "Pinjaman Anda sebesar Rp " . number_format($pinjaman_data['jumlah_pinjaman'], 0, ',', '.') . " telah ditolak. Alasan: " . $alasan_penolakan;
            
            $notifikasi_query = "INSERT INTO notifikasi (anggota_id, pinjaman_id, tipe, pesan, status) 
                               VALUES ('$anggota_id', '$pinjaman_id', 'ditolak', '$pesan', 'belum dibaca')";
            
            mysqli_query($koneksi, $notifikasi_query);
            
            header("location: ../admin/pinjaman/pinjaman.php?pesan=penolakan_berhasil");
        } else {
            error_log("Database error: " . mysqli_error($koneksi));
            header("location: ../admin/pinjaman/pinjaman.php?pesan=penolakan_gagal");
        }
    } catch (Exception $e) {
        error_log("Database error: " . $e->getMessage());
        header("location: ../admin/pinjaman/pinjaman.php?pesan=error");
    }
} else {
    header("location: ../admin/pinjaman/pinjaman.php");
}

mysqli_close($koneksi);
?>
