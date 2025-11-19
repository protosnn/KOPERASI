<?php
require_once 'koneksi.php';

echo "<h2>Status Pinjaman yang Ada:</h2>";
$result = mysqli_query($koneksi, "SELECT DISTINCT status FROM pinjaman");
while($row = mysqli_fetch_assoc($result)) {
    echo "- " . $row['status'] . "<br>";
}

echo "<h2>Total Pinjaman Per Status:</h2>";
$result = mysqli_query($koneksi, "SELECT status, COUNT(*) as total, SUM(jumlah_pinjaman) as total_jml FROM pinjaman GROUP BY status");
while($row = mysqli_fetch_assoc($result)) {
    echo $row['status'] . ": " . $row['total'] . " pinjaman, Total: Rp " . number_format($row['total_jml'], 0, ',', '.') . "<br>";
}

echo "<h2>Semua Pinjaman dengan Detail:</h2>";
$result = mysqli_query($koneksi, "SELECT p.id, a.nama, p.jumlah_pinjaman, p.status FROM pinjaman p JOIN anggota a ON p.anggota_id = a.id");
while($row = mysqli_fetch_assoc($result)) {
    echo "ID: " . $row['id'] . " | Nama: " . $row['nama'] . " | Jumlah: Rp " . number_format($row['jumlah_pinjaman'], 0, ',', '.') . " | Status: " . $row['status'] . "<br>";
}
?>
