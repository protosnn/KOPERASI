<?php
include 'koneksi.php';

echo "<h3>Semua Data Pinjaman:</h3>";
$query = "SELECT p.id, p.anggota_id, a.nama, p.jumlah_pinjaman, p.tenor, p.status, p.tanggal_pengajuan FROM pinjaman p JOIN anggota a ON p.anggota_id = a.id ORDER BY p.id DESC";

$result = mysqli_query($koneksi, $query);

if ($result) {
    $rows = mysqli_num_rows($result);
    echo "<p>Total pinjaman: $rows</p>";
    
    if ($rows > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Anggota ID</th><th>Nama</th><th>Jumlah</th><th>Tenor</th><th>Status</th><th>Tgl Pengajuan</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['anggota_id'] . "</td>";
            echo "<td>" . $row['nama'] . "</td>";
            echo "<td>Rp " . number_format($row['jumlah_pinjaman'], 0, ',', '.') . "</td>";
            echo "<td>" . $row['tenor'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            echo "<td>" . $row['tanggal_pengajuan'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} else {
    echo "<p style='color: red;'>Query error: " . mysqli_error($koneksi) . "</p>";
}

mysqli_close($koneksi);
?>
