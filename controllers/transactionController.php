<?php
include "/xampp/htdocs/kasir/config/database.php";
session_start();
ob_start();

$id = $_SESSION["id_user"];


if (isset($_REQUEST["edit_order"])) {
    $id_order = $_REQUEST["edit_order"];
    $_SESSION["edit_order"] = $id_order;

    header("location: /kasir/views/pembayaran.php");
}

if (isset($_REQUEST["hapus_order"])) {
    $id_order = $_REQUEST["hapus_order"];

    $query_hapus_order = "DELETE FROM pesanan WHERE id_order = $id_order";
    $query_hapus_pesan_order = "DELETE FROM transaksi WHERE id_order = $id_order";

    $sql_hapus_order = mysqli_query($koneksi, $query_hapus_order);
    $sql_hapus_pesan_order = mysqli_query($koneksi, $query_hapus_pesan_order);
    if ($sql_hapus_order) {
        header("location: /kasir/views/transaksi.php");
    }
}

if (isset($_REQUEST["hapus_transaksi"])) {
    $id_order = $_REQUEST["hapus_transaksi"];

    $query_hapus_transaksi = "DELETE FROM pesanan WHERE id_order = $id_order";
    $query_hapus_pesan = "DELETE FROM transaksi WHERE id_order = $id_order";

    $sql_hapus_transaksi = mysqli_query($koneksi, $query_hapus_transaksi);
    $sql_hapus_pesan = mysqli_query($koneksi, $query_hapus_pesan);

    if ($sql_hapus_transaksi) {
        header("location: /kasir/views/transaksi.php");
    }
}

if (isset($_REQUEST["cancel_order"])) {

    if (isset($_SESSION["edit_order"])) {
        unset($_SESSION["edit_order"]);
        header("location: /kasir/views/transaksi.php");
    }
}

if (isset($_REQUEST["save_order"])) {

    $id_order = $_REQUEST["save_order"];

    $uang_bayar = $_POST["uang_bayar"];
    $uang_kembali = $_POST["uang_kembali"];

    $query_save_transaksi = "UPDATE pesanan SET uang_bayar = $uang_bayar, uang_kembali = $uang_kembali, status_order = 'sudah bayar' WHERE id_order = $id_order";
    $sql_save_transaksi = mysqli_query($koneksi, $query_save_transaksi);

    $query_selesai_pesan = "UPDATE transaksi SET status_transaksi = 'sudah' WHERE id_order = $id_order AND status_transaksi = 'belum bayar'";
    $sql_selesai_pesan = mysqli_query($koneksi, $query_selesai_pesan);

    if ($sql_selesai_pesan) {
        header("location: /kasir/views/transaksi.php");
    }
}

ob_flush();
