<?php
    include "db/koneksi.php";
    session_start();
    ob_start();

    $id = $_SESSION['id_user'];

    if(isset ($_SESSION['username'])) {  
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
    <title>Order</title>
</head>
<body>
    <p><?php echo $nama_lengkap?></p>
    <p><?php echo $r['nama_role'];?></p>
    <a href="keluar.php">Log Out</a><br><hr>

    <?php include "template/sidebar.php"?>

    <?php if($r['id_role'] == 2 || $r['id_role'] == 3 || $r['id_role'] == 4){ 
        
      $order = array();
      $query_lihat_order = "select * from pesanan";
      $sql_lihat_order = mysqli_query($koneksi, $query_lihat_order);

      while($r_dt_order = mysqli_fetch_array($sql_lihat_order)){
        if($r_dt_order['status_order'] != 'sudah bayar'){
          array_push($order, $r_dt_order['staff']);
        }
      }

      if(in_array($id, $order)){
    ?>
      <div>
        <p>Notifications</p>
        <p>
          Informasi ! <br>
          Terimakasih, Anda telah melakukan pemesanan.<br>
          Silahkan tunggu pesanan tiba di meja saudara. Apabila selesai menyantap hidangan, silahkan lakukan proses pembayaran di kasir !
        </p>
      </div>
      <table>
        <tr>
          <th class="head0">No.</th>
          <th class="head1">Menu</th>
          <th class="head0 right">Jumlah</th>
          <th class="head1 right">Harga</th>
          <th class="head0 right">Total</th>
        </tr>
        <tbody>
          <?php
            $no_order_fiks = 1;
            $query_order_fiks = "select * from transaksi natural join menu where id_user = $id and status_transaksi != 'sudah'";
            $sql_order_fiks = mysqli_query($koneksi, $query_order_fiks);
            while($r_order_fiks = mysqli_fetch_array($sql_order_fiks)){
          ?>
            <tr>
              <td><?php echo $no_order_fiks++; ?></td>
              <td><?php echo $r_order_fiks['nama_menu'];?></td>
              <td><?php echo $r_order_fiks['jumlah'];?></td>
              <td>Rp. <?php echo $r_order_fiks['harga'];?>,-</td>
              <td>
                  Rp.
                  <?php 
                    $hasil = $r_order_fiks['harga'] * $r_order_fiks['jumlah'];
                    echo $hasil;
                  ?>
              </td>
            </tr>
            <?php }
              $query_harga = "select * from pesanan where staff = $id and status_order = 'belum bayar'";
              $sql_harga = mysqli_query($koneksi, $query_harga);
              $result_harga = mysqli_fetch_array($sql_harga);
            ?>
              <tr>
                <td></td>
                <td>Total</td>
                <td></td>
                <td></td>
                <td>Rp. <?php echo $result_harga['total_harga'];?></td>
              </tr>
        </tbody>
      </table>
    <?php } else { ?>
      <p>Menu Makanan</p>
      <?php
        $pesan = array();

        $query_lihat_pesan = "select * from transaksi where id_user = $id and status_transaksi != 'sudah'";
        $sql_lihat_pesan = mysqli_query($koneksi, $query_lihat_pesan);

        while($r_dt_pesan = mysqli_fetch_array($sql_lihat_pesan)){
          array_push($pesan, $r_dt_pesan['id_menu']);
        }

        $query_data_makanan = "select * from menu where stok > 0 ";
        $sql_data_makanan = mysqli_query($koneksi, $query_data_makanan);
        $no_makanan = 1;

        while($r_dt_makanan = mysqli_fetch_array($sql_data_makanan)){
      ?>
        <img src="img/menu/<?php echo $r_dt_makanan['gambar_menu']?>" alt="" >
        <p><?php echo $r_dt_makanan['nama_menu'];?></p>
        <p>Rp. <?php echo $r_dt_makanan['harga'];?></p>
        <p><?php echo $r_dt_makanan['stok'];?> Porsi</p>

        <form action="" method="post">
          <?php if(in_array($r_dt_makanan['id_menu'], $pesan)){ ?>
            <button type="submit" value="<?php echo $r_dt_makanan['id_menu'];?>" name="tambah_pesan" disabled>Telah dipesan</button>
          <?php } else { ?>
            <button type="submit" value="<?php echo $r_dt_makanan['id_menu'];?>" name="tambah_pesan">Pesan</button>
          <?php } ?>
        </form>
        <?php }
          if(isset($_REQUEST['tambah_pesan'])){
            $id_masakan = $_REQUEST['tambah_pesan'];

            $query_tambah_pesan = "insert into transaksi values('', '$id', '', '$id_masakan', '', '')";
            $sql_tambah_pesan= mysqli_query($koneksi, $query_tambah_pesan);

            $query_lihat_pesannya = "select * from transaksi order by id_transaksi desc limit 1";
            $sql_lihat_pesannya = mysqli_query($koneksi, $query_lihat_pesannya);
            $result_lihat_pesannya = mysqli_fetch_array($sql_lihat_pesannya);

            $id_pesannya = $result_lihat_pesannya['id_transaksi'];

            $query_olah_stok = "insert into stok_out values('', '$id_pesannya', '', 'belum cetak')";
            $sql_olah_stok= mysqli_query($koneksi, $query_olah_stok);
            if($sql_tambah_pesan){
              header('location: order.php');
            }
          }
        ?>
      <form action="" method="post">
        <p>Menu Pesanan</p><br>
        <p>Jumlah</p><br>
        <p>Aksi</p><br>
        <?php
          $query_draft_pesan = "select * from transaksi natural join menu where id_user = $id and status_transaksi != 'sudah'";
          $sql_draft_pesan = mysqli_query($koneksi, $query_draft_pesan);
          while($r_draft_pesan = mysqli_fetch_array($sql_draft_pesan)){
        ?>
          <p id="<?php echo "nama".$r_draft_pesan['id_transaksi']; ?>"><?php echo $r_draft_pesan['nama_menu'];?></p><br>
          <input id="<?php echo "harga".$r_draft_pesan['id_transaksi']; ?>" type="hidden" value="<?php echo $r_draft_pesan['harga'];?>" >
          <input id="<?php echo "jumlah".$r_draft_pesan['id_transaksi']; ?>" name="jumlah<?php echo $r_draft_pesan['id_menu']; ?>" type="number" oninput="return operasi()" >
            <button type="submit" value="<?php echo $r_draft_pesan['id_transaksi'];?>" name="hapus_pesan"></button>
        <?php } ?>
        <div>
          <p>Total Harga</p><br>
          <span >Rp. <span id="total_harga">0</span>
          <input id="tot" name="total_harga" type="hidden" value="" placeholder="" />
        </div>
        <br>
        <button type="submit" value="" name="proses_pesan"> Proses Pesanan </button>
        <hr>
      </form>
      <?php
        }
        if(isset($_POST['hapus_pesan'])){
          $id_pesan = $_POST['hapus_pesan'];
          $query_hapus_pesan = "delete from transaksi where id_transaksi = $id_pesan";
          $sql_hapus_pesan = mysqli_query($koneksi, $query_hapus_pesan);

          if($sql_hapus_pesan){
            header('location: order.php');
          }
        }

        if(isset($_POST['proses_pesan'])){
          $id_pengunjung = $id;
          $total_harga = $_POST['total_harga'];
          $uang_bayar = '';
          $uang_kembali = '';
          $status_order = 'belum bayar';

          date_default_timezone_set('Asia/Jakarta');
          $time = Date('YmdHis');
          echo $time;
          $query_simpan_order = "insert into pesanan values('', '$id_pengunjung', $time, '$total_harga', '$uang_bayar', '$uang_kembali', '$status_order')";
          $sql_simpan_order = mysqli_query($koneksi, $query_simpan_order);

          $query_tampil_order = "select * from pesanan where staff = $id order by id_order desc limit 1";
          $sql_tampil_order = mysqli_query($koneksi, $query_tampil_order);
          $result_tampil_order = mysqli_fetch_array($sql_tampil_order);

          $id_ordernya = $result_tampil_order['id_order'];

          $query_ubah_jumlah = "select * from transaksi left join menu on transaksi.id_menu = menu.id_menu where id_user = $id and status_transaksi != 'sudah'";
          $sql_ubah_jumlah = mysqli_query($koneksi, $query_ubah_jumlah);
          while($r_ubah_jumlah = mysqli_fetch_array($sql_ubah_jumlah)){
            $tahu = $r_ubah_jumlah['id_menu'];
            $tempe = $_POST['jumlah'.$tahu];
            $id_pesan = $r_ubah_jumlah['id_transaksi'];
            $query_stok = "select * from menu where id_menu = $tahu";
            $sql_stok = mysqli_query($koneksi, $query_stok);
            $result_stok = mysqli_fetch_array($sql_stok);
            $sisa_stok = $result_stok['stok'] - $tempe;
              //echo $tempe;
            $query_proses_ubah = "update transaksi set jumlah = $tempe, id_order = $id_ordernya where id_menu = $tahu and id_user = $id and status_transaksi != 'sudah'";
            $query_kurangi_stok = "update menu set stok = $sisa_stok where id_menu = $tahu";
              
            $query_kelola_stok = "update stok_out set jumlah_terjual = $tempe where id_transaksi = $id_pesan";

            $sql_kelola_stok = mysqli_query($koneksi, $query_kelola_stok);
            $sql_kurangi_stok = mysqli_query($koneksi, $query_kurangi_stok);
            $sql_proses_ubah = mysqli_query($koneksi, $query_proses_ubah);
          }

          if($sql_simpan_order){
            header('location: order.php');
          }
        }

      }
      ?>


  <script src="js/jquery-3.7.1.min.js"></script>
  <script type="text/javascript">
    function operasi(){
      var pesan = new Array();
      var jumlah = new Array();
      var total = 0;
      for(var a = 0; a < 1000; a++){
        pesan[a] = $("#harga"+a).val();
        jumlah[a] = $("#jumlah"+a).val();
      } 
      for(var a = 0; a < 1000; a++){
        if(pesan[a] == null || pesan[a] == ""){
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
    header('location: keluar.php');
  }
  ob_flush();
?>