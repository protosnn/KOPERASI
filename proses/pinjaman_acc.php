<?php
    include '../koneksi.php';
    $pinjaman_id=$_GET['pinjaman_id'];
    $query_pinjaman = mysqli_query($koneksi, "select * from pinjaman where id=$pinjaman_id");
    $data_pinjaman = mysqli_fetch_assoc($query_pinjaman);
    $jumlah_pinjaman = $data_pinjaman['jumlah_pinjaman'];
    $tenor = $data_pinjaman['tenor'];

    //Ambil total saldo
    $query_simpanan = mysqli_query($koneksi, "select sum(nominal) as total from simpanan");
    $data_simpanan = mysqli_fetch_assoc($query_simpanan);
    $saldo = $data_simpanan['total'];

    //cek saldo 
    if($jumlah_pinjaman>$saldo){
        echo "<script>alert('Maaf, saldo simpanan tidak mencukupi untuk pinjaman ini'); window.location.href='../admin/pinjaman/pinjaman.php';</script>";
    }else{
        //ambil bunga
        $query_setting = mysqli_query($koneksi, "select * from setting");
        $data_setting = mysqli_fetch_assoc($query_setting);
        $bunga = $data_setting['bunga'];

        $bunga = $bunga*$jumlah_pinjaman;
        $angsuran = $jumlah_pinjaman/$tenor;
        $tanggal_acc = date('Y-m-d');

        for($i=0; $i <= $tenor; $i++) { 
            //tanggal jatuh tempo 
            $bulan_depan=$i+1;
            $tanggal_jatuh_tempo=date('Y-m-d', strtotime("+$bulan_depan month", strtotime($tanggal_acc)));

            //tambah ke tabel angsuran
            mysqli_query($koneksi, "insert into angsuran(pinjaman_id,angsuran_ke,nominal,tgl_jatuhtempo,status,bunga) values($pinjaman_id,$i,$angsuran,$tanggal_jatuh_tempo,'belum lunas',$bunga)");
        }
        //update status jadi acc
        mysqli_query($koneksi, "update pinjaman set status='acc',tanggal_acc=now() where id=$pinjaman_id");
        header('location: ../admin/pinjaman/pinjaman.php');
    }


?>