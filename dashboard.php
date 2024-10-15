<?php
include "config/database.php";

session_start();
ob_start();

// GET CURRENT PAGE NAME
$current_page = basename($_SERVER["PHP_SELF"]); //WITH EXTENSION (.php)
$page_name = pathinfo($current_page, PATHINFO_FILENAME); //WITHOUT EXTENSION

$id = $_SESSION["id_user"];

if (isset($_SESSION["username"])) {
    $query = "SELECT * FROM user NATURAL JOIN role WHERE id_user = $id";
    $sql = mysqli_query($koneksi, $query);

    // GET CURRENT MONTH AND YEAR
    $current_month = date("m");
    $current_year = date("Y");

    // GET ORDER COUNT BY MONTH
    $query_order = "SELECT COUNT(*) AS order_count FROM pesanan WHERE MONTH(waktu_pesan) = $current_month AND YEAR(waktu_pesan) = $current_year";
    $sql_order = mysqli_query($koneksi, $query_order);
    $result_order = mysqli_fetch_array($sql_order);

    // GET PROFIT BY MONTH
    $query_profit_per_month = "SELECT MONTH(waktu_pesan) AS month, SUM(uang_bayar - uang_kembali) AS net_profit FROM pesanan WHERE YEAR(waktu_pesan) = $current_year GROUP BY MONTH(waktu_pesan)";
    $sql_profit_per_month = mysqli_query($koneksi, $query_profit_per_month);
    $r_profit_per_month = mysqli_fetch_array($sql_profit_per_month);
    $profit = $r_profit_per_month["net_profit"];
    $format_int_profit = "Rp. " . number_format($profit, 0, ",", ".");

    // GET POPULAR MENU BY THE TOTAL AMOUNT OF ORDER
    $query_menu_populer = "SELECT m.nama_menu, m.gambar_menu, SUM(t.jumlah) AS total_orders FROM transaksi t JOIN menu m ON t.id_menu = m.id_menu GROUP BY t.id_menu ORDER BY total_orders DESC LIMIT 1";
    $sql_menu_populer = mysqli_query($koneksi, $query_menu_populer);
    $r_menu_populer = mysqli_fetch_array($sql_menu_populer);

    // GET AVAILABLE MENU BY STATUS
    $query_menu_tersedia = "SELECT count(*) AS menu_tersedia FROM menu WHERE status_menu = 'tersedia'";
    $sql_menu_tersedia = mysqli_query($koneksi, $query_menu_tersedia);
    $r_menu_tersedia = mysqli_fetch_array($sql_menu_tersedia);

    while ($r = mysqli_fetch_array($sql)) {
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="assets/css/main.css">
            <link rel="stylesheet" href="assets/css/sidebar.css">
            <link rel="stylesheet" href="assets/css/navbar.css">
            <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
            <title><?php echo ucfirst($page_name) ?></title>
        </head>

        <body>
            <!-- SIDEBAR -->
            <?php include "views/partials/sidebar.php" ?>
            <!-- SIDEBAR -->

            <!-- CONTENT -->
            <section id="content">
                <!-- NAVBAR -->
                <?php include "views/partials/navbar.php" ?>
                <!-- NAVBAR -->

                <!-- MAIN -->
                <main>
                    <div class="menu-head">
                        <h1 class="title"><?php echo $page_name ?></h1>
                    </div>
                    <div class="info-data">
                        <div class="card">
                            <h2><?php echo $result_order["order_count"] ?></h2>
                            <p>Order (Month)</p>
                        </div>
                        <div class="card">
                            <h2><?php echo $format_int_profit ?></h2>
                            <p>Net Profit (Month)</p>
                        </div>
                        <div class="card">
                            <h2><?php echo $r_menu_populer["nama_menu"] ?></h2>
                            <p>Menu populer</p>
                        </div>
                        <div class="card">
                            <h2><?php echo $r_menu_tersedia["menu_tersedia"] ?></h2>
                            <p>Menu Tersedia</p>
                        </div>
                    </div>
                    <div class="data">
                        <div class="content-data">
                            <h3>Sales Report</h3>
                            <div class="chart">
                                <div id="line_chart"></div>
                            </div>
                        </div>
                        <div class="content-data">
                            <h3>Karyawan</h3>
                            <table class="karyawan-table">
                                <thead>
                                    <tr>
                                        <th>No</td>
                                        <th>Nama</td>
                                        <th>Role</td>
                                        <th>Status</td>
                                    </tr>
                                </thead>
                                <?php
                                $no = 1;
                                $query_user = "SELECT * FROM user JOIN role ON user.id_role = role.id_role";
                                $sql_user = mysqli_query($koneksi, $query_user);
                                while ($row_user = mysqli_fetch_array($sql_user)) { ?>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $no++ ?></td>
                                            <td><?php echo $row_user["nama_lengkap"] ?></td>
                                            <td><?php echo $row_user["nama_role"] ?></td>
                                            <td><?php echo $row_user["status"] ?></td>
                                        </tr>
                                    </tbody>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                </main>
                <!-- MAIN -->
            </section>
            <!-- CONTENT -->


            <script src="assets/js/script.js"></script>

            <?php include "views/partials/chart.php" ?>
        </body>

        </html>
<?php }
} else {
    header("location: keluar.php");
}
ob_flush(); ?>