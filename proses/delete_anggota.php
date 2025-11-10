<?php
include '../koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Validasi ID
    if (is_numeric($id)) {
        $query = "DELETE FROM anggota WHERE id = $id";
        
        if (mysqli_query($koneksi, $query)) {
            echo "<script>
                    alert('Data berhasil dihapus');
                    window.location.href = '../anggota.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal menghapus data: " . mysqli_error($koneksi) . "');
                    window.location.href = '../anggota.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('ID tidak valid');
                window.location.href = '../anggota.php';
              </script>";
    }
} else {
    echo "<script>
            alert('ID tidak ditemukan');
            window.location.href = '../anggota.php';
          </script>";
}
?>