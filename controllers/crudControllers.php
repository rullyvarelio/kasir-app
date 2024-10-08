<?php
include_once($_SERVER["DOCUMENT_ROOT"] . "/kasir/config/database.php");
session_start();
ob_start();

if (isset($_POST["tambah_menu"])) {

    $nama_menu = $_POST["nama_menu"];
    $harga = $_POST["harga"];
    $stok = $_POST["stok"];

    $direktori = "/xampp/htdocs/kasir/assets/img/menu/";

    $tmp_name = $_FILES["gambar_menu"]["tmp_name"];
    $name = pathinfo($_FILES["gambar_menu"]["name"], PATHINFO_EXTENSION);
    $nama_baru = $_POST["nama_menu"] . "." . $name;
    move_uploaded_file($tmp_name, $direktori . $nama_baru);
    $gambar = $nama_baru;

    if ($stok > 0) {
        $status_menu = "tersedia";
    } else {
        $status_menu = "habis";
    }

    $query_tambah_menu = "INSERT INTO menu VALUES ('', '$nama_menu', $harga, $stok, '$status_menu', '$gambar')";
    $sql_tambah_menu = mysqli_query($koneksi, $query_tambah_menu);

    if ($sql_tambah_menu) {
        header("location: /kasir/views/menu.php");
    }
}

if (isset($_REQUEST["batal_menu"])) {

    if (isset($_SESSION["edit_menu"])) {
        unset($_SESSION["edit_menu"]);
    }

    header("location: /kasir/views/menu.php");
}

if (isset($_POST["ubah_menu"])) {
    $nama_menu = $_POST["nama_menu"];
    $harga = $_POST["harga"];
    $stok = $_POST["stok"];


    $id = $_SESSION["edit_menu"];
    $query_edit = "SELECT * FROM menu WHERE id_menu = $id";
    $sql_edit = mysqli_query($koneksi, $query_edit);
    $result_edit = mysqli_fetch_array($sql_edit);

    $id_menu = $result_edit["id_menu"];
    $gambar_menu = $direktori . $result_edit["gambar_menu"];


    if ($stok > 0) {
        $status_menu = "tersedia";
    } else {
        $status_menu = "habis";
    }

    $direktori = "/xampp/htdocs/kasir/assets/img/menu/";

    $tmp_name = $_FILES["gambar_menu"]["tmp_name"];
    $name = pathinfo($_FILES["gambar_menu"]["name"], PATHINFO_EXTENSION);
    $nama_baru = $_POST['nama_menu'] . "." . $name;
    move_uploaded_file($tmp_name, $direktori . "/" . $nama_baru);
    $gambar = $nama_baru;

    $query_ubah_menu = "UPDATE menu SET nama_menu = '$nama_menu', harga = $harga, stok = $stok, status_menu = '$status_menu', gambar_menu = '$gambar' WHERE id_menu = $id_menu";
    $sql_ubah_menu = mysqli_query($koneksi, $query_ubah_menu);

    if ($sql_ubah_menu) {
        unset($_SESSION["edit_menu"]);
        header("location: /kasir/views/menu.php");
    }
}

ob_flush();
