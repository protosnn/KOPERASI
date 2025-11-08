<?php
require_once '../koneksi.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_GET['pinjaman_id'])) {
    echo json_encode(['error' => 'ID Pinjaman tidak ditemukan']);
    exit;
}

$pinjaman_id = $_GET['pinjaman_id'];

// Ambil data pinjaman
$query_pinjaman = "SELECT id, jumlah_pinjaman, status FROM pinjaman WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $query_pinjaman);
mysqli_stmt_bind_param($stmt, "i", $pinjaman_id);
mysqli_stmt_execute($stmt);
$result_pinjaman = mysqli_stmt_get_result($stmt);
$pinjaman = mysqli_fetch_assoc($result_pinjaman);

if (!$pinjaman) {
    echo json_encode(['error' => 'Data pinjaman tidak ditemukan']);
    exit;
}

// Ambil data angsuran
$query_angsuran = "SELECT 
    id,
    nominal,
    DATE_FORMAT(tgl_pelunasan, '%d/%m/%Y') as tanggal_bayar,
    status
FROM angsuran 
WHERE pinjaman_id = ?
ORDER BY tanggal_bayar ASC";

$stmt = mysqli_prepare($koneksi, $query_angsuran);
mysqli_stmt_bind_param($stmt, "i", $pinjaman_id);
mysqli_stmt_execute($stmt);
$result_angsuran = mysqli_stmt_get_result($stmt);

$angsuran = [];
$total_angsuran = 0;

while ($row = mysqli_fetch_assoc($result_angsuran)) {
    $angsuran[] = $row;
    $total_angsuran += $row['nominal'];
}

$response = [
    'pinjaman' => $pinjaman,
    'angsuran' => $angsuran,
    'total_angsuran' => $total_angsuran
];

echo json_encode($response);
?>