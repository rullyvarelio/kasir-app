<?php
include "/xampp/htdocs/kasir/config/database.php";
session_start();
ob_start();

if (isset($_REQUEST["hapus_user"])) {
    $id_user = $_REQUEST["hapus_user"];

    $query_lihat = "SELECT * FROM user WHERE id_user = $id_user";
    $sql_lihat = mysqli_query($koneksi, $query_lihat);
    $result_lihat = mysqli_fetch_array($sql_lihat);

    $direktori = "/xampp/htdocs/kasir/assets/img/user/";

    if (file_exists($direktori . $result_lihat["gambar_user"])) {
        unlink($direktori . $result_lihat["gambar_user"]);
    }

    $query_hapus_user = "DELETE FROM user WHERE id_user = $id_user";
    $sql_hapus_user = mysqli_query($koneksi, $query_hapus_user);

    if ($sql_hapus_user) {
        header("location: /kasir/views/karyawan.php");
    }
}

if (isset($_REQUEST["edit_user"])) {
    $id_user = $_REQUEST["edit_user"];
    $_SESSION["edit_user"] = $id_user;

    header("location: /kasir/views/user/edit.php");
}

if (isset($_POST["tambah_user"])) {
    $nama_lengkap = $_POST["nama_lengkap"];
    $username = $_POST["username"];
    $password = md5($_POST["password"]);

    $direktori = "/xampp/htdocs/kasir/assets/img/user/";

    $tmp_name = $_FILES["gambar_user"]["tmp_name"];
    $name = pathinfo($_FILES["gambar_user"]["name"], PATHINFO_EXTENSION);
    $nama_baru = $_POST["nama_lengkap"] . "." . $name;
    move_uploaded_file($tmp_name, $direktori . $nama_baru);
    $gambar = $nama_baru;

    $query_tambah_user = "insert into user values ('','$username', '$password', '$nama_lengkap', 2, 'belum aktif','$gambar')";
    $sql_tambah_user = mysqli_query($koneksi, $query_tambah_user);

    if ($sql_tambah_user) {
        header('location: /kasir/views/karyawan.php');
    }
}

if (isset($_REQUEST["batal_user"])) {

    if (isset($_SESSION["edit_user"])) {
        unset($_SESSION["edit_user"]);
    }
    header("location: /kasir/views/karyawan.php");
}

if (isset($_POST["ubah_user"])) {
    $id = $_SESSION["edit_user"];
    $query_data_edit = "SELECT * FROM user WHERE id_user = $id";
    $sql_data_edit = mysqli_query($koneksi, $query_data_edit);
    $result_data_edit = mysqli_fetch_array($sql_data_edit);

    $id_user = $result_data_edit['id_user'];

    $role = $_POST["role_user"];
    $status = $_POST["status"];

    $query_edit_user = "UPDATE user SET id_role = $role, status = '$status' WHERE id_user = '$id_user'";
    $sql_edit_user = mysqli_query($koneksi, $query_edit_user);

    if ($sql_edit_user) {
        unset($_SESSION["edit_user"]);
        header("location: /kasir/views/karyawan.php");
    }
}

ob_flush();
