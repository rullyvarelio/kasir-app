<?php
$host = "localhost";
$user = "root";
$pass = "";

$db = "kasir_db";
$koneksi = mysqli_connect($host, $user, $pass, $db);
mysqli_select_db($koneksi, $db);

if (!$koneksi) {
    die('koneksi gagal: ' . $connect->connect_error);
}
