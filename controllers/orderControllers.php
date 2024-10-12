<?php
include "/xampp/htdocs/kasir/config/database.php";
session_start();
ob_start();

$id = $_SESSION["id_user"];

if (isset($_REQUEST["tambah_pesan"])) {
    $id_menu = $_REQUEST["tambah_pesan"];

    $query_draft_pesan = "INSERT INTO transaksi VALUES('', $id, '', $id_menu, '', 'belum selesai')";
    $sql_draft_pesan = mysqli_query($koneksi, $query_draft_pesan);

    $query_look_pesan = "SELECT * FROM transaksi ORDER BY id_transaksi DESC LIMIT 1";
    $sql_look_pesan = mysqli_query($koneksi, $query_look_pesan);
    $result_look_pesan = mysqli_fetch_array($sql_look_pesan);

    $id_pesan = $result_look_pesan["id_transaksi"];

    $query_olah_stok = "INSERT INTO stok_out VALUES('', $id_pesan, '', 'belum cetak')";
    $sql_olah_stok = mysqli_query($koneksi, $query_olah_stok);

    if ($sql_draft_pesan) {
        header("location: /kasir/views/order.php");
    }
}

if (isset($_POST["hapus_pesan"])) {
    $id_pesan = $_POST["hapus_pesan"];
    $query_hapus_pesan = "DELETE FROM transaksi WHERE id_transaksi = $id_pesan";
    $sql_hapus_pesan = mysqli_query($koneksi, $query_hapus_pesan);

    if ($sql_hapus_pesan) {
        header("location: /kasir/views/order.php");
    }
}

if (isset($_POST['proses_pesan'])) {
    $id_pengunjung = $id;
    $total_harga = $_POST['total_harga'];
    $uang_bayar = '';
    $uang_kembali = '';
    $status_order = 'belum bayar';

    date_default_timezone_set('Asia/Jakarta');
    $time = Date('YmdHis');
    echo $time;

    $query_simpan_order = "INSERT INTO pesanan VALUES('', '$id_pengunjung', $time, '$total_harga', '$uang_bayar', '$uang_kembali', '$status_order')";
    $sql_simpan_order = mysqli_query($koneksi, query: $query_simpan_order);

    // Fetch the id of the newly inserted order
    $id_order = mysqli_insert_id($koneksi);

    $query_payment = "SELECT * FROM transaksi LEFT JOIN menu ON transaksi.id_menu = menu.id_menu WHERE id_user = $id AND status_transaksi = 'belum selesai'";
    $sql_payment = mysqli_query($koneksi, $query_payment);

    while ($r_payment = mysqli_fetch_array($sql_payment)) {
        $id_menu_payment = $r_payment["id_menu"];
        $tempe = $_POST["jumlah" . $id_menu_payment];
        $id_pesan = $r_payment["id_transaksi"];

        $query_stok = "SELECT * FROM menu WHERE id_menu = $id_menu_payment";
        $sql_stok = mysqli_query($koneksi, $query_stok);
        $result_stok = mysqli_fetch_array($sql_stok);
        $sisa_stok = $result_stok["stok"] - $tempe;

        $query_proses_ubah = "UPDATE transaksi SET jumlah = $tempe, id_order = $id_order WHERE id_menu = $id_menu_payment AND id_user = $id AND status_transaksi = 'belum selesai'";
        $query_kurangi_stok = "UPDATE menu SET stok = $sisa_stok WHERE id_menu = $id_menu_payment";

        $query_kelola_stok = "UPDATE stok_out SET jumlah_terjual = $tempe WHERE id_transaksi = $id_pesan";

        $sql_kelola_stok = mysqli_query($koneksi, $query_kelola_stok);
        $sql_kurangi_stok = mysqli_query($koneksi, $query_kurangi_stok);
        $sql_proses_ubah = mysqli_query($koneksi, $query_proses_ubah);
    }

    $query_ubah_status = "UPDATE transaksi SET status_transaksi = '$status_order' WHERE id_order = $id_order";
    $sql_ubah_status = mysqli_query($koneksi, $query_ubah_status);

    if ($sql_simpan_order) {
        header('location: /kasir/views/order.php');
    }
}

ob_flush();
