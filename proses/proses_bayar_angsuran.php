<?php
session_start();
include '../koneksi.php';

// Validasi input
$angsuran_id = isset($_POST['angsuran_id']) ? (int)$_POST['angsuran_id'] : 0;
$pinjaman_id = isset($_POST['pinjaman_id']) ? (int)$_POST['pinjaman_id'] : 0;
$nominal_bayar = isset($_POST['nominal_bayar']) ? $_POST['nominal_bayar'] : '';
$tgl_bayar = isset($_POST['tgl_bayar']) ? $_POST['tgl_bayar'] : '';

// Sanitasi nominal (hapus format Rupiah, ambil hanya angka)
$nominal_bayar = preg_replace('/[^0-9]/', '', $nominal_bayar);
$nominal_bayar = (int)$nominal_bayar;

// Validasi data
if(!$angsuran_id || !$pinjaman_id || !$nominal_bayar || !$tgl_bayar) {
    $_SESSION['error'] = 'Data pembayaran tidak lengkap';
    header('Location: ../admin/pinjaman/pinjaman.php');
    exit;
}

// Validasi tanggal
$tgl_bayar_check = DateTime::createFromFormat('Y-m-d', $tgl_bayar);
if(!$tgl_bayar_check) {
    $_SESSION['error'] = 'Format tanggal tidak valid';
    header('Location: ../admin/pinjaman/pinjaman.php');
    exit;
}

try {
    // Mulai transaksi
    mysqli_begin_transaction($koneksi);

    // Query untuk ambil data angsuran
    $query_check = "SELECT nominal, status FROM angsuran WHERE id = ? AND pinjaman_id = ?";
    $stmt = mysqli_prepare($koneksi, $query_check);
    mysqli_stmt_bind_param($stmt, "ii", $angsuran_id, $pinjaman_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($result) == 0) {
        throw new Exception('Angsuran tidak ditemukan');
    }
    
    $angsuran_data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    // Cek jika sudah lunas (case-insensitive)
    if(strtolower($angsuran_data['status']) == 'lunas') {
        throw new Exception('Angsuran sudah berstatus Lunas, tidak bisa diubah');
    }

    // Validasi nominal bayar (jangan lebih dari jumlah angsuran)
    if($nominal_bayar > $angsuran_data['nominal']) {
        throw new Exception('Nominal pembayaran tidak boleh lebih dari Rp ' . number_format($angsuran_data['nominal'], 0, ',', '.'));
    }

    // Update status angsuran menjadi lunas (lowercase)
    $query_update = "UPDATE angsuran 
                     SET status = 'lunas', 
                         tgl_pelunasan = ?,
                         nominal = ?
                     WHERE id = ? AND pinjaman_id = ?";
    
    $stmt = mysqli_prepare($koneksi, $query_update);
    
    if(!$stmt) {
        throw new Exception('Prepare statement gagal: ' . mysqli_error($koneksi));
    }
    
    mysqli_stmt_bind_param($stmt, "siii", $tgl_bayar, $nominal_bayar, $angsuran_id, $pinjaman_id);
    
    if(!mysqli_stmt_execute($stmt)) {
        throw new Exception('Execute gagal: ' . mysqli_stmt_error($stmt));
    }
    
    $affected_rows = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);

    if($affected_rows == 0) {
        throw new Exception('Gagal mengupdate angsuran');
    }

    // Commit transaksi
    mysqli_commit($koneksi);
    
    $_SESSION['success'] = 'Pembayaran angsuran berhasil disimpan';
    header('Location: ../admin/pinjaman/pinjaman.php');
    
} catch(Exception $e) {
    // Rollback jika ada error
    mysqli_rollback($koneksi);
    
    $_SESSION['error'] = $e->getMessage();
    header('Location: ../admin/pinjaman/pinjaman.php');
}

exit;
?>
