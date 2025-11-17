<?php
    session_start();
    include "../koneksi.php";
    $anggota = $_POST['anggota_id'];
    $jenis = $_POST['jenissimpanan_id'];
    // sanitize and validate tanggal; fallback to today if invalid or empty
    $tanggal_raw = isset($_POST['tanggal']) ? trim($_POST['tanggal']) : '';
    if (empty($tanggal_raw)) {
        $tanggal = date('Y-m-d');
    } else {
        $d = DateTime::createFromFormat('Y-m-d', $tanggal_raw);
        if ($d && $d->format('Y-m-d') === $tanggal_raw) {
            $tanggal = $tanggal_raw;
        } else {
            // If not in Y-m-d format, try common d/m/Y then convert
            $d2 = DateTime::createFromFormat('d/m/Y', $tanggal_raw);
            if ($d2 && $d2->format('d/m/Y') === $tanggal_raw) {
                $tanggal = $d2->format('Y-m-d');
            } else {
                // fallback to today to avoid '0000-00-00'
                $tanggal = date('Y-m-d');
            }
        }
    }

    //ambil nominal dari jenissimpanan (jika ada)
    $jenis = (int)$jenis;
    $anggota = (int)$anggota;
    $query_jenis_simpanan = mysqli_query($koneksi, "SELECT nominal FROM jenissimpanan WHERE id={$jenis}");
    $data_jenis_simpanan = mysqli_fetch_assoc($query_jenis_simpanan);
    $nominal = isset($data_jenis_simpanan['nominal']) ? (int)$data_jenis_simpanan['nominal'] : 0;

    //kalau jenis simpanan 1 2 nominal diambil dari database
    if($jenis==1){
        $cek_simpanan_pokok=mysqli_query($koneksi, "select * from simpanan where anggota_id=$anggota and jenissimpanan_id=1");
        $hitung=mysqli_num_rows($cek_simpanan_pokok);
        if($hitung==1){
            $_SESSION['error'] = 'Simpanan pokok untuk anggota ini sudah ada dan tidak bisa ditambah lagi';
            header('Location: ../admin/pemasukan/simpanan.php');
            exit;
        }else{
            // insert with tanggal validated
            $stmt = mysqli_prepare($koneksi, "INSERT INTO simpanan (anggota_id, jenissimpanan_id, nominal, tanggal) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, 'iiis', $anggota, $jenis, $nominal, $tanggal);
            if(mysqli_stmt_execute($stmt)) {
                $_SESSION['success'] = 'Simpanan pokok berhasil ditambahkan';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan simpanan pokok';
            }
            mysqli_stmt_close($stmt);
        }
    }else if($jenis==2){
        $cek_simpanan_wajib=mysqli_query(
            $koneksi, "select * from simpanan where anggota_id=$anggota 
            AND jenissimpanan_id=2 
            AND MONTH(tanggal)=MONTH('$tanggal') 
            AND YEAR(tanggal)=YEAR('$tanggal')
            ");
        $hitung=mysqli_num_rows($cek_simpanan_wajib);
        if($hitung==1){
            $_SESSION['error'] = 'Simpanan wajib untuk bulan ini sudah ada dan tidak bisa ditambah lagi';
            header('Location: ../admin/pemasukan/simpanan.php');
            exit;
        }else{
            $stmt = mysqli_prepare($koneksi, "INSERT INTO simpanan (anggota_id, jenissimpanan_id, nominal, tanggal) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, 'iiis', $anggota, $jenis, $nominal, $tanggal);
            if(mysqli_stmt_execute($stmt)) {
                $_SESSION['success'] = 'Simpanan wajib berhasil ditambahkan';
            } else {
                $_SESSION['error'] = 'Gagal menambahkan simpanan wajib';
            }
            mysqli_stmt_close($stmt);
        }
    }else{
        // sanitize nominal input (remove non-digit chars)
        $nominal_raw = isset($_POST['nominal']) ? $_POST['nominal'] : 0;
        $nominal_clean = (int)preg_replace('/[^0-9]/', '', $nominal_raw);
        if ($nominal_clean <= 0) {
            $_SESSION['error'] = 'Nominal simpanan harus lebih dari 0';
            header('Location: ../admin/pemasukan/simpanan.php');
            exit;
        }
        $stmt = mysqli_prepare($koneksi, "INSERT INTO simpanan (anggota_id, jenissimpanan_id, nominal, tanggal) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'iiis', $anggota, $jenis, $nominal_clean, $tanggal);
        if(mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = 'Simpanan sukarela berhasil ditambahkan';
        } else {
            $_SESSION['error'] = 'Gagal menambahkan simpanan sukarela';
        }
        mysqli_stmt_close($stmt);
    }

    header("Location: ../admin/pemasukan/simpanan.php");
?>