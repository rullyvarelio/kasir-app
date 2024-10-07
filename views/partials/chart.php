<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
        }],
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
        stroke: {
            curve: 'smooth',
            width: 3,
            colors: ['#290ccd'],
        }

    };

    var chart = new ApexCharts(document.querySelector("#line_chart"), options);
    chart.render();
</script>