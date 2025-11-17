<?php
include "../koneksi.php";

// Validasi parameter
if (!isset($_GET['pinjaman_id'])) {
    echo "<script type='text/javascript'>alert('ID Pinjaman tidak ditemukan'); window.location.href='../admin/pinjaman/pinjaman.php';</script>";
    exit;
}

$pinjaman_id = (int)$_GET['pinjaman_id'];

// Mulai transaksi
mysqli_begin_transaction($koneksi);

try {
    // 1. Hapus semua data angsuran terkait pinjaman ini
    $query_hapus_angsuran = "DELETE FROM angsuran WHERE pinjaman_id = ?";
    $stmt = mysqli_prepare($koneksi, $query_hapus_angsuran);
    
    if (!$stmt) {
        throw new Exception("Prepare gagal: " . mysqli_error($koneksi));
    }
    
    mysqli_stmt_bind_param($stmt, "i", $pinjaman_id);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Gagal menghapus angsuran: " . mysqli_error($koneksi));
    }
    mysqli_stmt_close($stmt);
    
    // 2. Hapus data pinjaman
    $query_hapus_pinjaman = "DELETE FROM pinjaman WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $query_hapus_pinjaman);
    
    if (!$stmt) {
        throw new Exception("Prepare gagal: " . mysqli_error($koneksi));
    }
    
    mysqli_stmt_bind_param($stmt, "i", $pinjaman_id);
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Gagal menghapus pinjaman: " . mysqli_error($koneksi));
    }
    
    $affected_rows = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    
    // Jika tidak ada baris yang dihapus, berarti pinjaman tidak ditemukan
    if ($affected_rows === 0) {
        throw new Exception("Pinjaman tidak ditemukan");
    }
    
    // Commit transaksi
    mysqli_commit($koneksi);
    
    // Redirect dengan pesan sukses
    echo "<script type='text/javascript'>alert('Pinjaman berhasil dihapus'); window.location.href='../admin/pinjaman/pinjaman.php';</script>";
    
} catch (Exception $e) {
    // Rollback transaksi jika terjadi error
    mysqli_rollback($koneksi);
    
    echo "<script type='text/javascript'>alert('Gagal menghapus pinjaman: " . htmlspecialchars($e->getMessage()) . "'); window.location.href='../admin/pinjaman/pinjaman.php';</script>";
}
?>
