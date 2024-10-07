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
    $sql = mysqli_query($koneksi, $query);

    while($r = mysqli_fetch_array($sql)){
        $nama_lengkap = $r['nama_lengkap'];
        $uang = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Laporan</title>
</head>
<body>
    <p><?php echo $nama_lengkap?></p>
    <p><?php echo $r['nama_role'];?></p>
    <a href="keluar.php">Log Out</a><br><hr>

    <?php include "template/sidebar.php"?>

        <?php if($r['id_role'] == 1){ ?>
            <div>
                <h5>Laporan Hari Ini</h5>
            </div>
            <table class="table table-bordered table-invoice-full">
                <tr>
                    <td>No.</td>
                    <td>Nama Menu</td>
                    <td>Sisa Stok</td>
                    <td>Jumlah Terjual</td>
                    <td>Harga</td>
                    <td>Total Masukan</td>
                </tr>
                <?php
                    $no = 1;
                    $query_lihat_menu = "select * from menu";
                    $sql_lihat_menu = mysqli_query($koneksi, $query_lihat_menu);

                ?>
                <?php while($r_lihat_menu = mysqli_fetch_array($sql_lihat_menu)){ ?>
                    <tr>
                        <td><?php echo $no++;?></td>
                        <td><?php echo $r_lihat_menu['nama_menu'];?></td>
                        <td><?php echo $r_lihat_menu['stok'];?></td>
                        <td>
                            <?php
                                $id_masakan = $r_lihat_menu['id_menu'];
                                $query_lihat_stok = "select * from stok_out left join transaksi on stok_out.id_transaksi = transaksi.id_transaksi left join menu on transaksi.id_menu = menu.id_menu where status_cetak = 'belum cetak'";
                                $query_jumlah = "select sum(jumlah_terjual) as jumlah_terjual from stok_out left join transaksi on stok_out.id_transaksi = transaksi.id_transaksi where id_menu = $id_masakan and status_cetak = 'belum cetak'";
                                $sql_jumlah = mysqli_query($koneksi, $query_jumlah);
                                $result_jumlah = mysqli_fetch_array($sql_jumlah);

                                $jml = 0;

                            if($result_jumlah['jumlah_terjual'] != 0 || $result_jumlah['jumlah_terjual'] != null || $result_jumlah['jumlah_terjual'] != ""){
                            $jml = $result_jumlah['jumlah_terjual'];
                            echo $jml;
                            } else {
                            $jml = 0;
                            echo $jml;
                            }
                        ?>
                        </center>
                    </td>
                    <td>Rp. <?php echo $r_lihat_menu['harga'];?></td>
                    <td>Rp. 
                        <?php
                            $id_masakan = $r_lihat_menu['id_menu'];
                            $query_lihat_stok = "select * from stok_out left join transaksi on stok_out.id_transaksi = transaksi.id_transaksi left join menu on transaksi.id_menu = menu.id_menu where status_cetak = 'belum cetak'";
                            $query_jumlah = "select sum(jumlah_terjual) as jumlah_terjual from stok_out left join transaksi on stok_out.id_transaksi = transaksi.id_transaksi where id_menu = $id_masakan and status_cetak = 'belum cetak'";
                            $sql_jumlah = mysqli_query($koneksi, $query_jumlah);
                            $result_jumlah = mysqli_fetch_array($sql_jumlah);

                            $jml = 0;

                            if($result_jumlah['jumlah_terjual'] != 0 || $result_jumlah['jumlah_terjual'] != null || $result_jumlah['jumlah_terjual'] != ""){
                            //echo $result_jumlah['jumlah_terjual'];
                            $jml = $result_jumlah['jumlah_terjual'] * $r_lihat_menu['harga'];
                            echo $jml;
                            } else {
                            $jml = $result_jumlah['jumlah_terjual'] * $r_lihat_menu['harga'];
                            echo $jml;
                            }
                            $uang += $jml;
                        ?>
                        
                    </td>
                    </tr>
                </table>
            <?php } ?>
        <?php } ?>
        <h4>Uang Masuk Rp. <?php echo $uang;?></h4>
</body>
</html>
<?php }
    } else {
    header('location: keluar.php');
    }
    ob_flush();
?>