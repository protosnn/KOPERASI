<?php
include '../koneksi.php';

// Ambil data berdasarkan ID
///$id = $_GET['id'];///
$query = mysqli_query($koneksi, "SELECT * FROM anggota WHERE id = $id");
$data = mysqli_fetch_array($query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Proses update data
    $nama_anggota = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $alamat = $_POST['almat'];
    $telepon = $_POST['telpon'];

    $update_query = "UPDATE anggota SET 
                    nama = '$nama_anggota', 
                    username = '$username', 
                    password = '$password', 
                    almat = '$alamat', 
                    telpon = '$telepon' 
                    WHERE id = $id";

    if (mysqli_query($koneksi, $update_query)) {
        header("Location: anggota.php?pesan=update_berhasil");
    } else {
        header("Location: anggota.php?pesan=update_gagal");
    }
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
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" class="form-control" name="nama" value="<?php echo $data['nama']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" value="<?php echo $data['username']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="text" class="form-control" name="password" value="<?php echo $data['password']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea class="form-control" name="almat" rows="3" required><?php echo $data['almat']; ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Telepon</label>
                <input type="text" class="form-control" name="telpon" value="<?php echo $data['telpon']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="anggota.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>