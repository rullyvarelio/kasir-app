<?php
include "db/koneksi.php";

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

    $query_bayar = "SELECT sum(uang_bayar) AS total_bayar FROM pesanan";
    $sql_bayar = mysqli_query($koneksi, $query_bayar);
    $result_bayar = mysqli_fetch_array($sql_bayar);

    $query_kembali = "SELECT sum(uang_kembali) AS total_kembali FROM pesanan";
    $sql_kembali = mysqli_query($koneksi, $query_kembali);
    $result_kembali = mysqli_fetch_array($sql_kembali);
    $profit = $result_bayar['total_bayar'] - $result_kembali['total_kembali'] * 0.90;
    $format_int_profit = "Rp. " . number_format($profit, 0, ',', '.');

    $query_popular_menu = "SELECT m.nama_menu, m.gambar_menu, SUM(t.jumlah) AS total_orders FROM transaksi t JOIN menu m ON t.id_menu = m.id_menu GROUP BY t.id_menu ORDER BY total_orders DESC LIMIT 1";
    $sql_popular_menu = mysqli_query($koneksi, $query_popular_menu);

    while ($r = mysqli_fetch_array($sql)) {
        $nama_lengkap = $r['nama_lengkap'];
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <title>Beranda</title>
        </head>

        <body>
            <?php include "../kasir/template/sidebar.php" ?>
            <main>
                <?php
                $page_title = pathinfo($current_page, PATHINFO_FILENAME);
                ?>

                <header>
                    <button class="toggle-sidebar">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <div class="profile">
                        <img src="img/user/<?php echo $r['gambar_user'] ?>" alt="Profile icon">
                        <ul>
                            <li><?php echo $nama_lengkap; ?></li>
                            <li><?php echo $r['nama_role']; ?></li>
                        </ul>
                    </div>
                </header>
                <section id="beranda">
                    <h1 class="title"><?php echo $current_page ?></h1>
                    <div class="breadcrumbs">

                    </div>
                    <div class="card span-2">
                        <h1>Order total</h1>
                        <i>Monthly</i>
                        <div class="card-re">
                            <h2>
                                <?php echo $result_order['order_count'] ?>
                            </h2>
                            <span class="material-symbols-outlined">receipt_long</span>
                        </div>
                    </div>
                    <div class="card">
                        <h1>Net profit</h1>
                        <i>Monthly</i>
                        <div class="card-re">
                            <h2><?php echo $format_int_profit ?></h2>
                            <span class="material-symbols-outlined">point_of_sale</span>
                        </div>
                    </div>
                    <div class="card">
                        <h1>
                            Menu populer
                        </h1>
                        <?php while ($row = mysqli_fetch_array($sql_popular_menu)) { ?>
                            <i><?php echo "Total Order: " . $row['total_orders'] ?></i>
                            <p><?php echo $row['nama_menu'] ?></p>
                            <img src="../kasir/img/menu/<?php echo $row['gambar_menu'] ?>" alt="Menu populer">
                        <?php } ?>
                    </div>

                    <!-- CHART -->
                    <canvas id="lineChart"></canvas>
                    <!-- CHART -->
                </section>
            </main>
        </body>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const line = document.getElementById('lineChart');
            const pie = document.getElementById('pieChart');
            const allMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

            <?php
            // Inisialisasi array penuh dari Januari hingga Desember
            $profits_per_month = array_fill(1, 12, 0); // Isi awal dengan 0 untuk semua bulan

            // Query untuk mendapatkan profit per bulan
            $query_profit_per_month = "SELECT MONTH(waktu_pesan) AS month, SUM(uang_bayar - uang_kembali) AS net_profit FROM pesanan WHERE YEAR(waktu_pesan) = '$current_year' GROUP BY MONTH(waktu_pesan)";
            $sql_profit_per_month = mysqli_query($koneksi, $query_profit_per_month);

            // Isi data profit untuk bulan yang memiliki hasil query
            while ($row = mysqli_fetch_array($sql_profit_per_month)) {
                $profits_per_month[$row['month']] = $row['net_profit']; // Masukkan data profit ke bulan yang sesuai
            }
            ?>

            // Data profit dari PHP
            const profits = [
                <?php
                // Output data profit untuk setiap bulan, bahkan jika tidak ada profit (akan menjadi 0)
                foreach ($profits_per_month as $profit) {
                    echo $profit . ", ";
                }
                ?>
            ];

            new Chart(line, {
                type: 'line',
                data: {
                    labels: allMonths,
                    datasets: [{
                        label: 'Net Profit (Rp)',
                        data: profits,
                        backgroundColor: '#f5f5f5',
                        borderColor: '#f5f5f5',
                        borderWidth: 3,
                        fill: false,
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#f5f5f5',
                                font: {
                                    family: 'Roboto',
                                    weight: 'bold',
                                },
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            },
                            grid: {
                                color: '#FCFAEE'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#f5f5f5',
                                font: {
                                    family: 'Roboto',
                                    weight: 'bold',
                                },
                            },
                            grid: {
                                color: '#FCFAEE'
                            }
                        }
                    },
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: "Chart Pendapatan",
                            font: {
                                size: 30,
                                weight: 'bold',
                                family: 'Playfair'
                            },
                            color: '#f5f5f5',

                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(tooltipItem.raw);
                                }
                            }
                        },
                        legend: {
                            display: true,
                            labels: {
                                font: {
                                    size: 16,
                                    weight: 'bold',
                                    family: 'Roboto'
                                },
                                color: '#f5f5f5',
                            },
                            position: 'bottom',
                        }
                    }
                }
            });

            new Chart(pie, {
                type: 'pie',
                data: {
                    labels: [
                        <?php
                        $query_menu_tersedia = "SELECT * FROM menu WHERE status_menu = 'Tersedia'";
                        $sql_menu_tersedia = mysqli_query($koneksi, $query_menu_tersedia);
                        $labels = [];

                        while ($r_mt = mysqli_fetch_array($sql_menu_tersedia)) {
                            $labels[] = "'" . $r_mt['nama_menu'] . "'";
                        }
                        echo implode(", ", $labels);
                        ?>
                    ],
                    datasets: [{
                        label: 'Total Order',
                        data: [
                            <?php
                            $query_total_menu = "SELECT m.nama_menu, SUM(t.jumlah) AS total_orders FROM transaksi t JOIN menu m ON t.id_menu = m.id_menu GROUP BY t.id_menu ORDER BY total_orders DESC";
                            $sql_total_menu = mysqli_query($koneksi, $query_total_menu);

                            $data_orders = [];
                            $total_orders = 0;

                            // Hitung total semua orders
                            while ($r_total_order = mysqli_fetch_array($sql_total_menu)) {
                                $total_orders += $r_total_order['total_orders'];
                                $data_orders[] = $r_total_order['total_orders'];
                            }

                            // Hitung persentase dan keluarkan data dalam bentuk persentase
                            foreach ($data_orders as $order) {
                                $percentage = ($order / $total_orders) * 100;
                                echo round($percentage, 2) . ", ";
                            }
                            ?>
                        ],
                        backgroundColor: [
                            '#E5D9F2',
                            '#F5EFFF',
                            '#CDC1FF',
                            '#A594F9',
                            '#A5B68D',
                            '#ECDFCC',
                            '#FCFAEE',
                            '#DA8359',
                            '#EDDFE0',
                            '#F5F5F7',
                            '#B7B7B7',
                            '#705C53',
                        ],
                        borderColor: ['#00000025'],
                        hoverOffset: 4,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: "Chart Menu",
                            font: {
                                size: 30,
                                weight: 'bold',
                                family: 'Playfair'
                            },
                            color: '#f5f5f5',

                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    const dataset = tooltipItem.dataset;
                                    const currentValue = dataset.data[tooltipItem.dataIndex];
                                    return dataset.label + ": " + currentValue + "%";
                                }
                            }
                        },
                        legend: {
                            display: true,
                            labels: {
                                font: {
                                    size: 16,
                                    weight: 'bold',
                                    family: 'Roboto'
                                },
                                color: '#f5f5f5',
                            },
                            position: 'bottom',
                        }
                    }
                }
            });

            const profile = document.querySelector('header, .profile');
            const profileIcon = profile.querySelector('img');
            const dropdownProfile = profile.querySelector('ul');

            profileIcon.addEventListener('click', function() {
                dropdownProfile.classList.toggle('show');
            });
        </script>

        </html>
<?php
    }
} else {
    header('location: keluar.php');
}
ob_flush();
?>