<?php
    include "../koneksi.php";
    $anggota = $_POST['anggota_id'];
    $jenis = $_POST['jenissimpanan_id'];
    $tanggal = $_POST['tanggal'];

    //ambil nominal dari jenissimpanan
    $query_jenis_simpanan=mysqli_query($koneksi, "select * from jenissimpanan where id=$jenis");
    $data_jenis_simpanan= mysqli_fetch_assoc($query_jenis_simpanan);
    $nominal = $data_jenis_simpanan['nominal'];

    //kalau jenis simpanan 1 2 nominal diambil dari database
    if($jenis==1){
        $cek_simpanan_pokok=mysqli_query($koneksi, "select * from simpanan where anggota_id=$anggota and jenissimpanan_id=1");
        $hitung=mysqli_num_rows($cek_simpanan_pokok);
        if($hitung==1){
            echo "<script type='text/javascript'>alert('Sudah Lunas'); window.location.href='../admin/pemasukan/simpanan.php';</script>";
            exit;
        }else{
            mysqli_query($koneksi, "insert into simpanan(anggota_id,jenissimpanan_id,nominal,tanggal) values($anggota,$jenis,$nominal,$tanggal)");
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
            echo "<script type='text/javascript'>alert('Sudah Lunas'); window.location.href='../admin/pemasukan/simpanan.php';</script>";
            exit;
        }else{
            mysqli_query($koneksi, "insert into simpanan(anggota_id,jenissimpanan_id,nominal,tanggal) values($anggota,$jenis,$nominal,$tanggal)");
        }
    }else{
        $nominal=$_POST['nominal'];
        mysqli_query($koneksi, "insert into simpanan(anggota_id,jenissimpanan_id,nominal,tanggal) values($anggota,$jenis,$nominal,$tanggal)");
    }

    header("location:../admin/pemasukan/simpanan.php")
?>