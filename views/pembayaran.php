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
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <link rel="stylesheet" href="/kasir/assets/css/crud.css">
            <link rel="stylesheet" href="/kasir/assets/css/sidebar.css">
            <link rel="stylesheet" href="/kasir/assets/css/navbar.css">
            <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
            <title><?php echo ucfirst($page_name) ?></title>
        </head>

        <body>

            <div class="container">
                <div class="crud">
                    <h1>Pembayaran</h1>
                    <div class="info-menu">
                        <table>
                            <tr>
                                <th>Menu</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                            <?php

                            $id_order = $_SESSION["edit_order"];

                            $query_order_fiks = "SELECT * FROM transaksi LEFT JOIN menu ON transaksi.id_menu = menu.id_menu WHERE id_order = $id_order AND status_transaksi = 'belum bayar'";
                            $sql_order_fiks = mysqli_query($koneksi, $query_order_fiks);
                            while ($r_order_fiks = mysqli_fetch_array($sql_order_fiks)) {
                            ?>
                                <tr>
                                    <td><?php echo $r_order_fiks["nama_menu"]; ?></td>
                                    <td><?php echo $r_order_fiks["jumlah"]; ?></td>
                                    <td>

                                        <?php echo "Rp. " . number_format($r_order_fiks["harga"], 0, ",", ".");; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $hasil = $r_order_fiks["harga"] * $r_order_fiks["jumlah"];
                                        echo "Rp. " . number_format($hasil, 0, ",", ".");;
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                        <?php
                        $query_harga = "SELECT * FROM pesanan WHERE id_order = $id_order and status_order = 'belum bayar'";
                        $sql_harga = mysqli_query($koneksi, $query_harga);
                        $result_harga = mysqli_fetch_array($sql_harga);
                        ?>
                        <div class="bottom-row">
                            <p>Total</p>
                            <h3>
                                <?php echo $total_harga = "Rp. " . number_format($result_harga['total_harga'], 0, ",", "."); ?>
                            </h3>
                            <span id="total_biaya" style="display: none;">
                                <?php echo $result_harga['total_harga']; ?>
                            </span>
                        </div>
                    </div>
                    <form action="/kasir/controllers/transactionController.php" method="post">
                        <div class="input-group">
                            <label for="uang_bayar">Uang Bayar</label>
                            <input type="number" id="uang_bayar" name="uang_bayar" oninput="return operasi()" class="input-field">
                        </div>
                        <div class="input-group">
                            <label for="uang_kembali">Uang Kembali</label>
                            <input type="hidden" id="uang_kembali" name="uang_kembali">
                            <input type="number" id="uang_kembali1" class="input-field" readonly>
                        </div>
                        <div class="btn-group">
                            <button type="submit" value="<?php echo $result_harga["id_order"]; ?>" name="save_order" onclick="return validatePayment()">
                                Transaksi Selesai
                            </button>
                            <button type="submit" name="cancel_order">
                                Kembali
                            </button>
                        </div>
                    </form>
                </div>
            </div>


            <script src="/kasir/assets/js/jquery-3.7.1.min.js"></script>
            <script type="text/javascript">
                function operasi() {
                    var total_biaya = $("#total_biaya").text();
                    var uang_bayar = $("#uang_bayar").val();
                    var kembalian = Number(uang_bayar - total_biaya);
                    $("#uang_kembali1").val(kembalian);
                    $("#uang_kembali").val(kembalian);
                }

                function validatePayment() {
                    var total_biaya = Number($("#total_biaya").text());
                    var uang_bayar = Number($("#uang_bayar").val());
                    var kembalian = uang_bayar - total_biaya;

                    if (kembalian < 0) {
                        alert("Uang pembayaran kurang!");
                        return false; // prevent form submission
                    }
                    return true; // allow form submission
                }
            </script>

        </body>

        </html>
<?php }
} else {
    header("location: /kasir/keluar.php");
}
ob_flush();
?>