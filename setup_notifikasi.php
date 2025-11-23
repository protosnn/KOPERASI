<?php
include 'koneksi.php';

// 1. Update enum status pinjaman
$update_status = "ALTER TABLE `pinjaman` MODIFY `status` enum('pending','acc','tolak') NOT NULL";
$result1 = mysqli_query($koneksi, $update_status);
if($result1) {
    echo "✓ Status pinjaman updated<br>";
} else {
    echo "✗ Error updating status: " . mysqli_error($koneksi) . "<br>";
}

// 2. Cek apakah tabel notifikasi ada
$check_table = "SHOW TABLES LIKE 'notifikasi'";
$result2 = mysqli_query($koneksi, $check_table);
if(mysqli_num_rows($result2) > 0) {
    echo "✓ Tabel notifikasi sudah ada<br>";
} else {
    echo "✗ Tabel notifikasi belum ada, membuat...<br>";
    
    $create_table = "CREATE TABLE IF NOT EXISTS `notifikasi` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `anggota_id` int(11) NOT NULL,
      `pinjaman_id` int(11) NOT NULL,
      `tipe` varchar(50) NOT NULL,
      `pesan` text NOT NULL,
      `status` enum('dibaca','belum dibaca') NOT NULL DEFAULT 'belum dibaca',
      `tanggal_dibuat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      FOREIGN KEY (`anggota_id`) REFERENCES `anggota` (`id`) ON DELETE CASCADE,
      FOREIGN KEY (`pinjaman_id`) REFERENCES `pinjaman` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    
    $result3 = mysqli_query($koneksi, $create_table);
    if($result3) {
        echo "✓ Tabel notifikasi berhasil dibuat<br>";
    } else {
        echo "✗ Error creating table: " . mysqli_error($koneksi) . "<br>";
    }
}

mysqli_close($koneksi);
echo "<br>Setup selesai!";
?>
