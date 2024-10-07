<?php
include "../db/koneksi.php";

session_start();
ob_start();

$id = $_SESSION['id_user'];

if (isset($_SESSION['username'])) {
    $query = "SELECT * FROM user NATURAL JOIN role WHERE id_user = $id";
    $sql = mysqli_query($koneksi, $query);

    $current_month = date('m');
    $current_year = date('Y');

    $query_order = "SELECT COUNT(*) AS order_count FROM pesanan WHERE MONTH(waktu_pesan) = '$current_month' AND YEAR(waktu_pesan) = '$current_year'";
    $sql_order = mysqli_query($koneksi, $query_order);
    $result_order = mysqli_fetch_array($sql_order);

    $query_profit_per_month = "SELECT MONTH(waktu_pesan) AS month, SUM(uang_bayar - uang_kembali) AS net_profit FROM pesanan WHERE YEAR(waktu_pesan) = '$current_year' GROUP BY MONTH(waktu_pesan)";
    $sql_profit_per_month = mysqli_query($koneksi, $query_profit_per_month);
    $r_profit_per_month = mysqli_fetch_array($sql_profit_per_month);
    $profit = $r_profit_per_month['net_profit'];
    $format_int_profit = "Rp. " . number_format($profit, 0, ',', '.');

    $query_menu_populer = "SELECT m.nama_menu, m.gambar_menu, SUM(t.jumlah) AS total_orders FROM transaksi t JOIN menu m ON t.id_menu = m.id_menu GROUP BY t.id_menu ORDER BY total_orders DESC LIMIT 1";
    $sql_menu_populer = mysqli_query($koneksi, $query_menu_populer);
    $r_menu_populer = mysqli_fetch_array($sql_menu_populer);

    $query_menu_tersedia = "SELECT count(*) AS menu_tersedia FROM menu WHERE status_menu = 'tersedia'";
    $sql_menu_tersedia = mysqli_query($koneksi, $query_menu_tersedia);
    $r_menu_tersedia = mysqli_fetch_array($sql_menu_tersedia);

    while ($r = mysqli_fetch_array($sql)) {
        $nama_lengkap = $r['nama_lengkap'];
        $gambar_user = "../img/user/" . $r['gambar_user'];
        $role = $r['nama_role'];
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
            <link rel="stylesheet" href="style.css">
            <title>AdminSite</title>
        </head>

        <body>

            <!-- SIDEBAR -->
            <section id="sidebar">
                <div class="brand">
                    <i class='bx bxs-basket icon'></i>
                    RestoPro
                </div>
                <ul class="side-menu">
                    <?php $current_page = basename($_SERVER['PHP_SELF']) ?>
                    <li>
                        <a href="dashboard.php" class="<?= ($current_page == 'dashboard.php') ? 'active' : '' ?>">
                            <i class='bx bxs-dashboard icon'></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="../kasir/menu.php" class="<?= ($current_page == 'menu.php') ? 'active' : '' ?>">
                            <i class='bx bxs-food-menu icon'></i>
                            Menu
                        </a>
                    </li>
                    <li>
                        <a href="../kasir/karyawan.php" class="<?= ($current_page == 'karyawan.php') ? 'active' : '' ?>">
                            <i class='bx bxs-user icon'></i>
                            Karyawan
                        </a>
                    </li>
                    <li>
                        <a href="../kasir/order.php" class="<?= ($current_page == 'order.php') ? 'active' : '' ?>">
                            <i class='bx bxs-book-content icon'></i>
                            Order
                        </a>
                    </li>
                    <li>
                        <a href="../kasir/transaksi.php" class="<?= ($current_page == 'transaksi.php') ? 'active' : '' ?>">
                            <i class='bx bxs-wallet icon'></i>
                            Pembayaran
                        </a>
                    </li>
                    <li>
                        <a href="../kasir/laporan.php" class="<?= ($current_page == 'laporan.php') ? 'active' : '' ?>">
                            <i class='bx bxs-report icon'></i>
                            Laporan
                        </a>
                    </li>
                </ul>
            </section>
            <!-- SIDEBAR -->

            <!-- NAVBAR -->
            <section id="content">
                <!-- NAVBAR -->
                <nav>
                    <i class='bx bx-menu toggle-sidebar'></i>
                    <div class="profile">
                        <p><?php echo $nama_lengkap ?></p>
                        <img src="<?php echo $gambar_user ?>" alt="Profile Icon">
                        <ul class="profile-link">
                            <li>
                                <i class='bx bx-info-circle'></i>
                                <?php echo $role ?>
                            </li>
                        </ul>
                    </div>
                </nav>
                <!-- NAVBAR -->

                <!-- MAIN -->
                <main>
                    <h1 class="title">Dashboard</h1>
                    <div class="info-data">
                        <div class="card">
                            <h2><?php echo $result_order['order_count'] ?></h2>
                            <p>Order (Month)</p>
                        </div>
                        <div class="card">
                            <h2><?php echo $format_int_profit ?></h2>
                            <p>Net Profit (Month)</p>
                        </div>
                        <div class="card">
                            <h2><?php echo $r_menu_populer['nama_menu'] ?></h2>
                            <p>Menu populer</p>
                        </div>
                        <div class="card">
                            <h2><?php echo $r_menu_tersedia['menu_tersedia'] ?></h2>
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
                                $query_user = "SELECT * FROM user JOIN role ON user.id_role = role.id_role";
                                $sql_user = mysqli_query($koneksi, $query_user);
                                while ($row = mysqli_fetch_array($sql_user)) { ?>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $row['id_user'] ?></td>
                                            <td><?php echo $row['nama_lengkap'] ?></td>
                                            <td><?php echo $row['nama_role'] ?></td>
                                            <td><?php echo $row['status'] ?></td>
                                        </tr>
                                    </tbody>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                </main>
                <!-- MAIN -->
            </section>
            <!-- NAVBAR -->

            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
            <script src="script.js"></script>
            <script>
                const allMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

                <?php
                $profits_per_month = array_fill(1, 12, 0);

                $query_profit_per_month = "SELECT MONTH(waktu_pesan) AS month, SUM(uang_bayar - uang_kembali) AS net_profit FROM pesanan WHERE YEAR(waktu_pesan) = '$current_year' GROUP BY MONTH(waktu_pesan)";
                $sql_profit_per_month = mysqli_query($koneksi, $query_profit_per_month);

                while ($row = mysqli_fetch_array($sql_profit_per_month)) {
                    $profits_per_month[$row['month']] = $row['net_profit'];
                }
                ?>

                const profits = [
                    <?php
                    foreach ($profits_per_month as $profit) {
                        echo $profit . ", ";
                    }
                    ?>
                ];

                var options = {
                    chart: {
                        type: "line",
                    },
                    series: [{

                        name: "Net Profit",
                        data: profits,
                    }, ],
                    xaxis: {
                        categories: allMonths,
                    },
                    yaxis: {
                        labels: {
                            formatter: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value); // Format angka ke Rupiah
                            }
                        }
                    },

                };

                var chart = new ApexCharts(document.querySelector("#line_chart"), options);
                chart.render();
            </script>
        </body>

        </html>
<?php }
} else {
    header('location: keluar.php');
}
ob_flush(); ?>