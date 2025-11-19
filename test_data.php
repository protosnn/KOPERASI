<?php
require_once 'koneksi.php';

echo "<h2>Data Pinjaman:</h2>";
$query1 = "SELECT id, anggota_id, jumlah_pinjaman, status, tanggal_pengajuan, tanggal_acc FROM pinjaman LIMIT 10";
$result1 = mysqli_query($koneksi, $query1);
echo "<table border='1'><tr><th>ID</th><th>Anggota ID</th><th>Jumlah</th><th>Status</th><th>Tgl Pengajuan</th><th>Tgl ACC</th></tr>";
while($row = mysqli_fetch_assoc($result1)) {
    echo "<tr><td>{$row['id']}</td><td>{$row['anggota_id']}</td><td>{$row['jumlah_pinjaman']}</td><td>{$row['status']}</td><td>{$row['tanggal_pengajuan']}</td><td>{$row['tanggal_acc']}</td></tr>";
}
echo "</table>";

echo "<h2>Data Angsuran:</h2>";
$query2 = "SELECT id, pinjaman_id, nominal, status FROM angsuran LIMIT 10";
$result2 = mysqli_query($koneksi, $query2);
echo "<table border='1'><tr><th>ID</th><th>Pinjaman ID</th><th>Nominal</th><th>Status</th></tr>";
while($row = mysqli_fetch_assoc($result2)) {
    echo "<tr><td>{$row['id']}</td><td>{$row['pinjaman_id']}</td><td>{$row['nominal']}</td><td>{$row['status']}</td></tr>";
}
echo "</table>";

echo "<h2>Summary Pinjaman Aktif (status='acc'):</h2>";
$query3 = "SELECT COUNT(*) as total FROM pinjaman WHERE status='acc'";
$result3 = mysqli_query($koneksi, $query3);
$row3 = mysqli_fetch_assoc($result3);
echo "Total pinjaman dengan status 'acc': " . $row3['total'] . "<br>";

echo "<h2>Detail setiap pinjaman 'acc':</h2>";
$query4 = "SELECT p.id, a.nama, p.jumlah_pinjaman, p.status, 
           (SELECT COUNT(*) FROM angsuran WHERE pinjaman_id=p.id) as jml_angsuran,
           (SELECT COUNT(*) FROM angsuran WHERE pinjaman_id=p.id AND LOWER(status)='lunas') as jml_lunas,
           (SELECT SUM(nominal) FROM angsuran WHERE pinjaman_id=p.id AND LOWER(status)='lunas') as total_bayar
           FROM pinjaman p 
           JOIN anggota a ON p.anggota_id = a.id 
           WHERE p.status='acc'";
$result4 = mysqli_query($koneksi, $query4);
echo "<table border='1'><tr><th>ID</th><th>Nama</th><th>Jumlah</th><th>Status</th><th>Jml Angsuran</th><th>Jml Lunas</th><th>Total Bayar</th></tr>";
while($row = mysqli_fetch_assoc($result4)) {
    echo "<tr><td>{$row['id']}</td><td>{$row['nama']}</td><td>{$row['jumlah_pinjaman']}</td><td>{$row['status']}</td><td>{$row['jml_angsuran']}</td><td>{$row['jml_lunas']}</td><td>{$row['total_bayar']}</td></tr>";
}
echo "</table>";
?>
