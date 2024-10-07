<?php
    include "db/koneksi.php";
    session_start();
    ob_start();

    $id = $_SESSION['id_user'];

    if(isset($_SESSION['edit_order'])){
        unset($_SESSION['edit_order']);
    }

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
    <title>Transaksi</title>
</head>
<body>
    <p><?php echo $nama_lengkap?></p>
    <p><?php echo $r['nama_role'];?></p>
    <a href="keluar.php">Log Out</a><br><hr>

    <?php include "template/sidebar.php"?>
  
    <div>
      <h5>Belum Bayar</h5>
    </div>
    <table>
      <tr>
        <td>Staff</td>
        <td>Total Harga</td>
        <td>Aksi</td>
      </tr>
      <?php
        $query_order = "select * from pesanan left join user on pesanan.staff = user.id_user where status_order = 'belum bayar'";
        $sql_order = mysqli_query($koneksi, $query_order);
        while($r_order = mysqli_fetch_array($sql_order)){
      ?>
        <tr>
          <td><?php echo $r_order['nama_lengkap'];?></td>
          <td class="right">Rp. <?php echo $r_order['total_harga'];?></td>
          <td>
            <form action="" method="post">
              <button type="submit" value="<?php echo $r_order['id_order'];?>" name="edit_order">Proses</button>
              <button type="submit" value="<?php echo $r_order['id_order'];?>" name="hapus_order">Hapus</button>
            </form>
          </td>
        </tr>
      <?php }
        if(isset($_REQUEST['edit_order'])){
          $id_order = $_REQUEST['edit_order'];
          $_SESSION['edit_order'] = $id_order;
          header('location: pembayaran.php');
        }

        if(isset($_REQUEST['hapus_order'])){
          $id_order = $_REQUEST['hapus_order'];
          $query_hapus_order = "delete from pesanan where id_order = $id_order";
          $query_hapus_pesan_order = "delete from transaksi where id_order = $id_order";
          $sql_hapus_order = mysqli_query($koneksi, $query_hapus_order);
          $sql_hapus_pesan_order = mysqli_query($koneksi, $query_hapus_pesan_order);
          if($sql_hapus_order){
            header('location: transaksi.php');
          }
        }
      ?>
    </table>
    <div>
      <h5>Transaksi Terdahulu</h5>
    </div>
    <table>
      <tr>
        <td>No.</td>
        <td>Waktu Pesan</td>
        <td>Staff</td>
        <td>Total Harga</td>
        <td>Aksi</td>
      </tr>
      <?php
        $nomor = 1;
        $query_sudah_order = "select * from pesanan left join user on pesanan.staff = user.id_user where status_order = 'sudah bayar' order by id_order desc";
        $sql_sudah_order = mysqli_query($koneksi, $query_sudah_order);
        while($r_sudah_order = mysqli_fetch_array($sql_sudah_order)){
      ?>
        <tr>
          <td><?php echo $nomor++; ?></td>
          <td><?php echo $r_sudah_order['waktu_pesan'];?></td>
          <td><?php echo $r_sudah_order['staff'];?></td>
          <td>Rp. <?php echo $r_sudah_order['total_harga'];?>,-</td>
          <td>
            <form action="" method="post">
              <button type="submit" value="<?php echo $r_sudah_order['id_order'];?>" name="hapus_transaksi" class="btn btn-mini btn-danger">
                Hapus
              </button>
              <a target='_blank' href="print_struk.php?konten=<?php echo $r_sudah_order['id_order'];?>" class="btn btn-mini btn-success">
                Cetak
              </a>
            </form>
          </td>
        </tr>
      <?php }
        if(isset($_REQUEST['hapus_transaksi'])){
          $id_order = $_REQUEST['hapus_transaksi'];
          $query_hapus_transaksi = "delete from pesanan where id_order = $id_order";
          $query_hapus_pesan = "delete from transaksi where id_order = $id_order";
          $sql_hapus_transaksi = mysqli_query($koneksi, $query_hapus_transaksi);
          $sql_hapus_pesan = mysqli_query($koneksi, $query_hapus_pesan);
          if($sql_hapus_transaksi){
            header('location: transaksi.php');
        }
      }
      ?>
    </table>
</body>
</html>
<?php
    }
  } else {
    header('location: keluar.php');
  }
  ob_flush();
?>