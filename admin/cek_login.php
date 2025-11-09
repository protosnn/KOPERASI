<?php
session_start();
if(!isset($_SESSION['login'])){
    echo "<script>
            alert('Anda harus login terlebih dahulu!');
            window.location = '../index.php';
          </script>";
}
?>