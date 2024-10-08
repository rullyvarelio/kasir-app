<?php
include "/xampp/htdocs/kasir/config/database.php";
session_start();
ob_start();

if (isset($_REQUEST["add_stok"])) {
    $id_menu = $_REQUEST["add_stok"];
    $_SESSION["add_stok"] = $id_menu;

    header("location: /kasir/views/menu/edit.php");
}

if (isset($_REQUEST["edit_menu"])) {
    $id_menu = $_REQUEST["edit_menu"];
    $_SESSION["edit_menu"] = $id_menu;

    header("location: /kasir/views/menu/edit.php");
}

if (isset($_REQUEST["hapus_menu"])) {
    $id_menu = $_REQUEST["hapus_menu"];

    $query_lihat = "SELECT * FROM menu WHERE id_menu = $id_menu";
    $sql_lihat = mysqli_query($koneksi, $query_lihat);
    $result_lihat = mysqli_fetch_array($sql_lihat);

    $direktori = "/xampp/htdocs/kasir/assets/img/menu/";

    if (file_exists($direktori . $result_lihat["gambar_menu"])) {
        unlink($direktori . $result_lihat["gambar_menu"]);
    }

    $query_hapus_menu = "DELETE FROM menu WHERE id_menu = $id_menu";
    $sql_hapus_menu = mysqli_query($koneksi, $query_hapus_menu);

    if ($sql_hapus_menu) {
        header("location: /kasir/views/menu.php");
    }
}

ob_flush();
