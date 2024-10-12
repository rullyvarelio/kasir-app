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
      <link rel="stylesheet" href="/kasir/assets/css/main.css">
      <link rel="stylesheet" href="/kasir/assets/css/sidebar.css">
      <link rel="stylesheet" href="/kasir/assets/css/navbar.css">
      <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
      <link rel="shortcut icon" href="/kasir/assets/img/favicon/favicon.ico" type="image/x-icon">
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
          <div class="order-container">
            <div class="info-data center">
              <?php
              $pesan = array();
              $query_pesan = "SELECT * FROM transaksi WHERE id_user = $id AND status_transaksi = 'belum selesai'";
              $sql_pesan = mysqli_query($koneksi, $query_pesan);

              while ($r_pesan = mysqli_fetch_array($sql_pesan)) {
                array_push($pesan, $r_pesan["id_menu"]);
              }

              $query_menu = "SELECT * FROM menu WHERE stok > 0 ";
              $sql_menu = mysqli_query($koneksi, $query_menu);

              while ($r_menu = mysqli_fetch_array($sql_menu)) {
              ?>
                <div class="card">
                  <img src="/kasir/assets/img/menu/<?php echo $r_menu["gambar_menu"] ?>" alt="gambar menu">
                  <div class="card-info">
                    <h5><?php echo $r_menu["nama_menu"] ?></h5>
                    <h3><?php echo "Rp. " . number_format($r_menu["harga"], 0, ",", ".") ?></h3>
                    <span><?php echo $r_menu["stok"] ?> Porsi</span>
                  </div>
                  <form action="/kasir/controllers/orderControllers.php" method="post">
                    <?php if (in_array($r_menu["id_menu"], $pesan)) { ?>
                      <button type="submit" value="<?php echo $r_menu["id_menu"]; ?>" name="tambah_pesan" class="cart after" disabled>
                        <i class="bx bx-cart"></i>
                      </button>
                    <?php } else { ?>
                      <button type="submit" value="<?php echo $r_menu["id_menu"]; ?>" name="tambah_pesan" class="cart">
                        <i class="bx bx-cart"></i>
                      </button>
                    <?php } ?>
                  </form>
                </div>
              <?php } ?>
            </div>
            <div class="keranjang">
              <h2>Order</h2>
              <form action="/kasir/controllers/orderControllers.php" method="post">
                <table class="draft_order">
                  <tr>
                    <th style="width: auto;">No.</th>
                    <th style="width: 50%;">Menu</th>
                    <th style="width: 30%;">Jumlah</th>
                    <th style="width: 20%;"></th>
                  </tr>
                  <?php
                  $no = 1;
                  $query_draft_pesan = "SELECT * FROM transaksi NATURAL JOIN menu WHERE id_user = $id AND status_transaksi = 'belum selesai'";
                  $sql_draft_pesan = mysqli_query($koneksi, $query_draft_pesan);

                  while ($r_draft_pesan = mysqli_fetch_array($sql_draft_pesan)) {
                  ?>
                    <tr>
                      <td><?php echo $no++ ?></td>
                      <td><?php echo $r_draft_pesan["nama_menu"] ?></td>
                      <td>
                        <center>
                          <input type="hidden" id="<?php echo "harga" . $r_draft_pesan["id_transaksi"]; ?>" value="<?php echo $r_draft_pesan["harga"]; ?>">
                          <input type="text" id="<?php echo "jumlah" . $r_draft_pesan["id_transaksi"]; ?>" name="jumlah<?php echo $r_draft_pesan["id_menu"]; ?>" oninput="return operasi()" inputmode="numeric" onkeypress="return restrictAlphabet(event)">
                        </center>
                      </td>
                      <td>
                        <center>
                          <button type="submit" value="<?php echo $r_draft_pesan["id_transaksi"]; ?>" name="hapus_pesan" class="cart after" style="cursor: pointer;">
                            <i class="bx bx-trash"></i>
                          </button>
                        </center>
                      </td>
                    </tr>
                  <?php } ?>
                </table>
                <div class="bottom-row">
                  <p>
                    Rp.
                    <span id="total_harga">0</span>
                  </p>
                  <input id="tot" name="total_harga" type="hidden">
                  <center>
                    <button type="submit" name="proses_pesan" class="process-order">
                      Proses Pesanan
                    </button>
                  </center>
                </div>
              </form>
            </div>
          </div>
        </main>
        <!-- MAIN -->
      </section>
      <!-- CONTENT -->

      <script src="/kasir/assets/js/script.js"></script>
      <script src="/kasir/assets/js/jquery-3.7.1.min.js"></script>
      <script type="text/javascript">
        function restrictAlphabet(e) {
          var x = e.which || e.keycode;
          if (x >= 48 && x <= 57) {
            return true
          } else {
            return false
          }

        }

        function operasi() {
          var pesan = new Array();
          var jumlah = new Array();
          var total = 0;
          for (var a = 0; a < 1000; a++) {
            pesan[a] = $("#harga" + a).val();
            jumlah[a] = $("#jumlah" + a).val();
          }
          for (var a = 0; a < 1000; a++) {
            if (pesan[a] == null || pesan[a] == "") {
              pesan[a] = 0;
              jumlah[a] = 0;
            }
            total += Number(pesan[a] * jumlah[a]);
          }

          //alert(total);
          $("#total_harga").text(total);
          $("#tot").val(total);
        }
      </script>
    </body>

    </html>
<?php
  }
} else {
  header("location: /kasir/keluar.php");
}
ob_flush();
?>