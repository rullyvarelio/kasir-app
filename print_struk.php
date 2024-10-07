<?php
    include "db/koneksi.php";
    session_start();
    ob_start();

    $id = $_SESSION['id_user'];

    if(isset($_SESSION['edit_order'])){
        unset($_SESSION['edit_order']);
    }

    if(isset ($_SESSION['username'])){
        $query = "select * from user natural join role where id_user = $id";

    mysqli_query($koneksi, $query);
    $sql = mysqli_query($koneksi, $query);

    while($r = mysqli_fetch_array($sql)){
        
        $nama_lengkap = $r['nama_lengkap'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Struk</title>
    <style>
        @page{
        size: auto;
        }
        body {
        background: rgb(204,204,204); 
        }

        page {
        background: white;
        display: block;
        margin: 0 auto;
        margin-bottom: 0.5cm;
        box-shadow: 0 0 0.1cm rgba(0,0,0,0.5);
        }
        page[size="dipakai"][layout="landscape"] {
        width: 20cm;
        height: 20cm;  
        }
        @media print {
        body, page {
            margin: auto;
            box-shadow: 0;
        }
        }
    </style>
</head>
<body>
  <page size="dipakai" layout="landscape">
    <br>
      <span id="remove">
        <a id="ct">CETAK</a>
      </span>
    <?php
      $id_order = $_REQUEST['konten'];
      $query_order = "select * from pesanan left join user on pesanan.staff = user.id_user where id_order = $id_order";
      $sql_order = mysqli_query($koneksi, $query_order);
      $result_order = mysqli_fetch_array($sql_order);
      //echo $id_order
    ?>
      <center>
        <h4>
          Restoran
        </h4>
        <span>
            Alamat
            Kontak
        </span>
      </center>
        <hr>
              <table style="width: 100%" class="">
                <tr>
                  <td>
                    Staff
                  </td>
                  <td>
                  :
                  </td>
                  <td>
                    <?php echo $result_order['nama_lengkap'];?>
                  </td>
                </tr>
                <tr>
                  <td>
                    Waktu Pesan
                  </td>
                  <td>
                  :
                  </td>
                  <td>
                    <?php echo $result_order['waktu_pesan'];?>
                  </td>
                </tr>
              </table>

              <hr>

              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="head0">No.</th>
                    <th class="head1">Menu</th>
                    <th class="head0 right">Jumlah</th>
                    <th class="head1 right">Harga</th>
                    <th class="head0 right">Total</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $no_order_fiks = 1;
                    $query_order_fiks = "select * from transaksi natural join menu where id_order = $id_order";
                    $sql_order_fiks = mysqli_query($koneksi, $query_order_fiks);
                    //echo $query_order_fiks;
                    while($r_order_fiks = mysqli_fetch_array($sql_order_fiks)){
                  ?>
                  <tr>
                    <td><center><?php echo $no_order_fiks++; ?>. </center></td>
                    <td><?php echo $r_order_fiks['nama_menu'];?></td>
                    <td><center><?php echo $r_order_fiks['jumlah'];?></center></td>
                    <td>Rp. <?php echo $r_order_fiks['harga'];?>,-</td>
                    <td>
                      <strong>
                        Rp.
                        <?php 
                          $hasil = $r_order_fiks['harga'] * $r_order_fiks['jumlah'];
                          echo $hasil;
                        ?>,-
                      </strong>
                    </td>
                  </tr>
                  <?php
                    }
                    $query_harga = "select * from pesanan where id_order = $id_order";
                    $sql_harga = mysqli_query($koneksi, $query_harga);
                    $result_harga = mysqli_fetch_array($sql_harga);
                  ?>

                  <tr>
                    <td></td>
                    <td><strong><center>Total</center></strong></td>
                    <td></td>
                    <td></td>
                    <td><strong>Rp. <?php echo $result_harga['total_harga'];?>,-</strong></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td><strong><center>Uang Bayar</center></strong></td>
                    <td></td>
                    <td></td>
                    <td><strong>Rp. <?php echo $result_harga['uang_bayar'];?>,-</strong></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td><strong><center>Uang Kembali</center></strong></td>
                    <td></td>
                    <td></td>
                    <td><strong>Rp. <?php echo $result_harga['uang_kembali'];?>,-</strong></td>
                  </tr>
                </tbody>
              </table>

            <hr>
            <center>
              <h5>
                TERIMAKASIH ATAS KUNJUNGANNYA
              </h5>
            </center>
            <hr>
            
  </page>
  <script src="js/jquery-3.7.1.min.js"></script>
  <script type="text/javascript">
    document.getElementById('ct').onclick = function(){
      $("#remove").remove();
      window.print();
    }
    $(document).ready(function(){
      $("remove").remove();
  
    });
   
  </script>
<?php
    }
  }
?>
</body>
</html>
