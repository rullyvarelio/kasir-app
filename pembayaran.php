<?php
    include "db/koneksi.php";
    session_start();
    ob_start();

    $id = $_SESSION['id_user'];

    if(isset($_SESSION['edit_menu'])){
        unset($_SESSION['edit_menu']);
    }

    if(isset ($_SESSION['username'])){
        $query = "select * from user natural join role where id_user = $id";
        $sql = mysqli_query($koneksi, $query);

        while($r = mysqli_fetch_array($sql)){   
            $nama_lengkap = $r['nama_lengkap'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pembayaran</title>
</head>
<body>
    <p><?php echo $nama_lengkap?></p>
    <p><?php echo $r['nama_role'];?></p>
    <a href="keluar.php">Log Out</a><br><hr>

    <?php include "template/sidebar.php"?>

    <?php
        if ($r['id_role'] == 2 || $r['id_role'] == 3 || $r['id_role'] == 4) {
            $id_order = $_SESSION['edit_order'];
            $query_pemesan = "select * from pesanan left join user on pesanan.staff = user.id_user where id_order = $id_order";
            $sql_pemesan = mysqli_query($koneksi, $query_pemesan);
            $result_pemesan = mysqli_fetch_array($sql_pemesan);
            $id_pemesan = $result_pemesan['staff'];
    ?>
        <div>
            <h5>Transaksi Pembayaran (<?php echo $result_pemesan['nama_lengkap'];?>)</h5>
        </div>
        <table>
            <tr>
                <th>No.</th>
                <th>Menu</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
            <?php
              $no_order_fiks = 1;
              $query_order_fiks = "select * from transaksi left join menu on transaksi.id_menu = menu.id_menu where id_user = $id_pemesan and status_transaksi != 'sudah'";
              $sql_order_fiks = mysqli_query($koneksi, $query_order_fiks);
              while($r_order_fiks = mysqli_fetch_array($sql_order_fiks)){
            ?>
                <tr>
                  <td><?php echo $no_order_fiks++; ?></td>
                  <td><?php echo $r_order_fiks['nama_menu'];?></td>
                  <td><?php echo $r_order_fiks['jumlah'];?></td>
                  <td>Rp. <?php echo $r_order_fiks['harga'];?></td>
                  <td>
                      Rp.
                      <?php 
                        $hasil = $r_order_fiks['harga'] * $r_order_fiks['jumlah'];
                        echo $hasil;
                      ?>
                  </td>
                </tr>
            <?php }
              $query_harga = "select * from pesanan where staff = $id_pemesan and status_order = 'belum bayar'";
              $sql_harga = mysqli_query($koneksi, $query_harga);
              $result_harga = mysqli_fetch_array($sql_harga);
            ?>
            <tr>
              <td></td>
              <td>Total</td>
              <td></td>
              <td></td>
              <td>Rp. <span id="total_biaya"><?php echo $result_harga['total_harga'];?></span></td>
            </tr>
        </table>
        <form action="#" method="post" class="form-horizontal">
            <input type="number" id="uang_bayar" name="uang_bayar" placeholder="" oninput="return operasi()"/>
            <input type="number" id="uang_kembali1" class="span11" placeholder="" disabled="" />
            <input type="hidden" id="uang_kembali" name="uang_kembali" class="span11" placeholder=""/>
            <button type="submit" value="<?php echo $result_harga['id_order'];?>" name="save_order" onclick="return validatePayment()">
                Transaksi Selesai
            </button>
            <button type="submit" value="" name="back_order">
                Kembali
            </button>
        </form>
        <?php
            if(isset($_REQUEST['back_order'])){
                if(isset($_SESSION['edit_order'])){
                    unset($_SESSION['edit_order']);
                    header('location: transaksi.php');
                }
            }

            if(isset($_REQUEST['save_order'])){
                if(isset($_SESSION['edit_order'])){
                    unset($_SESSION['edit_order']);
                }
                $uang_bayar = $_POST['uang_bayar'];
                $uang_kembali = $_POST['uang_kembali'];
                $query_save_transaksi = "update pesanan set uang_bayar = $uang_bayar, uang_kembali = $uang_kembali, status_order = 'sudah bayar' where id_order = $id_order";
                $sql_save_transaksi = mysqli_query($koneksi, $query_save_transaksi);

                $query_selesai_pesan = "update transaksi set status_transaksi = 'sudah' where id_user = $id_pemesan and status_transaksi != 'sudah'";
                $sql_selesai_pesan = mysqli_query($koneksi, $query_selesai_pesan);
                if($sql_selesai_pesan){
                    header('location: transaksi.php');
                }
            }
        }
      ?>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script type="text/javascript">
        function operasi(){
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
    header('location: keluar.php');
    }
    ob_flush();
?>