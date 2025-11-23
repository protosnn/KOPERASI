<?php
session_start();
include 'koneksi.php';

// Tampilkan session info
echo "<h3>Session Info:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Jika ada session anggota
if(isset($_SESSION['id_anggota'])) {
    $anggota_id = $_SESSION['id_anggota'];
    
    echo "<h3>Checking pinjaman for anggota_id: $anggota_id</h3>";
    
    // Query untuk cek pinjaman
    $query = "SELECT * FROM pinjaman WHERE anggota_id = " . intval($anggota_id);
    echo "<p>Query: " . $query . "</p>";
    
    $result = mysqli_query($koneksi, $query);
    
    if ($result) {
        $rows = mysqli_num_rows($result);
        echo "<p>Rows found: $rows</p>";
        
        if ($rows > 0) {
            echo "<table border='1'>";
            echo "<tr><th>ID</th><th>Anggota ID</th><th>Jumlah</th><th>Tenor</th><th>Status</th><th>Tgl Pengajuan</th></tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['anggota_id'] . "</td>";
                echo "<td>" . $row['jumlah_pinjaman'] . "</td>";
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
}

mysqli_close($koneksi);
?>
