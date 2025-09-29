<?php
    include '../koneksi.php';
    $PinjamanID = $_GET['pinjaman_id'];
    $query_pinjaman = mysqli_query($koneksi, "select * form pinjaman where id=$PinjamanID");
    $data_pinjaman = mysqli_fetch_assoc($query_pinjaman);
    $jumlah_pinjaman = $data_pinjaman['jumlah_pinjaman'];
    $tenor = $data_pinjaman['tenor'];

    //Ambil total saldo



?>