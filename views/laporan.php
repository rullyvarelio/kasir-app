<?php
include "../config/database.php";

session_start();
ob_start();

// GET CURRENT PAGE NAME
$current_page = basename($_SERVER["PHP_SELF"]); //WITH EXTENSION (.php)
$page_name = pathinfo($current_page, PATHINFO_FILENAME); //WITHOUT EXTENSION

$id = $_SESSION["id_user"];

if (isset($_SESSION["username"])) {
    $query = "SELECT * FROM user NATURAL JOIN role WHERE id_user = $id";
    $sql = mysqli_query($koneksi, $query);

    while ($r = mysqli_fetch_array($sql)) {
?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <link rel="stylesheet" href="/kasir/assets/css/main.css">
            <link rel="stylesheet" href="/kasir/assets/css/sidebar.css">
            <link rel="stylesheet" href="/kasir/assets/css/navbar.css">
            <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
            <title><?php echo ucfirst($page_name) ?></title>
        </head>

        <body>
            <!-- SIDEBAR -->
            <?php include "../views/partials/sidebar.php" ?>
            <!-- SIDEBAR -->

            <!-- CONTENT -->
            <section id="content">
                <!-- NAVBAR -->
                <?php include "../views/partials/navbar.php" ?>
                <!-- NAVBAR -->

                <!-- MAIN -->
                <main>
                    <div class="menu-head">
                        <h1 class="title"><?php echo $page_name ?></h1>
                    </div>
                    <div class="info">
                        <div class="top-row">
                            <h3>Laporan penjualan bulan ini</h3>
                        </div>
                        <table class="table-info">
                            <tr>
                                <th>Nama Menu</th>
                                <th>Terjual</th>
                                <th>Harga</th>
                                <th>Total Masukan</th>
                            </tr>
                            <?php
                            $query_lihat_menu = "SELECT * FROM menu";
                            $sql_lihat_menu = mysqli_query($koneksi, $query_lihat_menu);
                            while ($r_lihat_menu = mysqli_fetch_array($sql_lihat_menu)) {
                                $id_menu = $r_lihat_menu["id_menu"];
                            ?>
                                <tr>
                                    <td><?php echo $r_lihat_menu["nama_menu"]; ?></td>
                                    <td>
                                        <?php

                                        $query_lihat_stok = "SELECT * FROM stok_out LEFT JOIN transaksi ON stok_out.id_transaksi = transaksi.id_transaksi LEFT JOIN menu ON transaksi.id_menu = menu.id_menu WHERE status_cetak = 'belum cetak'";
                                        $query_jumlah = "SELECT SUM(jumlah_terjual) AS jumlah_terjual FROM stok_out LEFT JOIN transaksi ON stok_out.id_transaksi = transaksi.id_transaksi WHERE id_menu = $id_menu AND status_cetak = 'belum cetak'";

                                        $sql_jumlah = mysqli_query($koneksi, $query_jumlah);
                                        $result_jumlah = mysqli_fetch_array($sql_jumlah);

                                        $jml = 0;

                                        if ($result_jumlah["jumlah_terjual"] != 0 || $result_jumlah["jumlah_terjual"] != null || $result_jumlah["jumlah_terjual"] != "") {
                                            $jml = $result_jumlah["jumlah_terjual"];
                                            echo $jml;
                                        } else {
                                            $jml = 0;
                                            echo $jml;
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo "Rp. " . number_format($r_lihat_menu["harga"], 0, ",", ".");
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $query_lihat_stok = "select * from stok_out left join transaksi on stok_out.id_transaksi = transaksi.id_transaksi left join menu on transaksi.id_menu = menu.id_menu where status_cetak = 'belum cetak'";
                                        $query_jumlah = "select sum(jumlah_terjual) as jumlah_terjual from stok_out left join transaksi on stok_out.id_transaksi = transaksi.id_transaksi where id_menu = $id_menu and status_cetak = 'belum cetak'";
                                        $sql_jumlah = mysqli_query($koneksi, $query_jumlah);
                                        $result_jumlah = mysqli_fetch_array($sql_jumlah);

                                        $jml = 0;

                                        if ($result_jumlah["jumlah_terjual"] != 0 || $result_jumlah["jumlah_terjual"] != null || $result_jumlah["jumlah_terjual"] != "") {
                                            $jml = $result_jumlah["jumlah_terjual"] * $r_lihat_menu["harga"];
                                            echo "Rp. " . number_format($jml, 0, ",", ".");
                                        } else {
                                            $jml = $result_jumlah["jumlah_terjual"] * $r_lihat_menu["harga"];
                                            echo "Rp. " . number_format($jml, 0, ",", ".");
                                        }
                                        ?>

                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                        <div class="bottom-row">
                            <h3>Total</h3>
                            <p>
                                <?php
                                $current_month = date("m");
                                $current_year = date("Y");

                                $query_profit_per_month = "SELECT MONTH(waktu_pesan) AS month, SUM(uang_bayar - uang_kembali) AS net_profit FROM pesanan WHERE YEAR(waktu_pesan) = $current_year GROUP BY MONTH(waktu_pesan)";
                                $sql_profit_per_month = mysqli_query($koneksi, $query_profit_per_month);
                                $r_profit_per_month = mysqli_fetch_array($sql_profit_per_month);
                                $profit = $r_profit_per_month["net_profit"];
                                $format_int_profit = "Rp. " . number_format($profit, 0, ",", ".");

                                echo $format_int_profit
                                ?>
                            </p>
                        </div>
                    </div>
                </main>
                <!-- MAIN -->
            </section>
            <!-- CONTENT -->
            <script src="/kasir/assets/js/script.js"></script>
        </body>

        </html>
<?php }
} else {
    header("location: /kasir/keluar.php");
}
ob_flush();
?>