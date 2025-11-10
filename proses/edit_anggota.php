<?php
include '../koneksi.php';

// Ambil data berdasarkan ID dari POST atau GET
if (isset($_POST['id'])) {
    $id = $_POST['id'];
} elseif (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    die("ID tidak ditemukan!");
}

// Validasi dan sanitasi ID
$id = mysqli_real_escape_string($koneksi, $id);

// Ambil data berdasarkan ID
$query = mysqli_query($koneksi, "SELECT * FROM anggota WHERE id = '$id'");
if (!$query) {
    die("Error: " . mysqli_error($koneksi));
}

$data = mysqli_fetch_array($query);
if (!$data) {
    die("Data anggota tidak ditemukan!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nama'])) {
    // Proses update data
    $nama_anggota = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $telpon = mysqli_real_escape_string($koneksi, $_POST['telpon']);

    $update_query = "UPDATE anggota SET 
                    nama = '$nama_anggota', 
                    username = '$username', 
                    password = '$password', 
                    almat = '$alamat', 
                    telpon = '$telpon' 
                    WHERE id = '$id'";

    if (mysqli_query($koneksi, $update_query)) {
        echo "<script>
                alert('Data berhasil diupdate');
                window.location.href = '../anggota.php';
              </script>";
    } else {
        echo "<script>
                alert('Error: " . mysqli_error($koneksi) . "');
                window.location.href = '../anggota.php';
              </script>";
    }
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Edit Data Anggota</h2>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($data['nama']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($data['username']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="text" class="form-control" name="password" value="<?php echo htmlspecialchars($data['password']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea class="form-control" name="alamat" rows="3" required><?php echo htmlspecialchars($data['almat']); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Telepon</label>
                <input type="text" class="form-control" name="telpon" value="<?php echo htmlspecialchars($data['telpon']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="../anggota.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>