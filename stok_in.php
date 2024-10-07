<?php
    include "db/koneksi.php";
    session_start();
    ob_start();

    $id = $_SESSION['id_user'];

    if (isset ($_SESSION['username'])) {
        
        $query = "select * from user natural join role where id_user = $id";
        $sql = mysqli_query($koneksi, $query);
        
        $nama_menu = "";
        $stok = "";
        $gambar_menu = "no_image.png";

        if (isset($_SESSION['add_stok'])){

            $id = $_SESSION['add_stok'];
            $query_data_edit = "select * from menu where id_menu = $id";
            $sql_data_edit = mysqli_query($koneksi, $query_data_edit);
            $result_data_edit = mysqli_fetch_array($sql_data_edit);

            $id_menu = $result_data_edit['id_menu'];
            $nama_menu = $result_data_edit['nama_menu'];
            $stok = $result_data_edit['stok'];
            $gambar_menu = $result_data_edit['gambar_menu'];
        }

        while($r = mysqli_fetch_array($sql)) {
            $nama_lengkap = $r['nama_lengkap'];


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Buat menu</title>
</head>
<body>
    <p><?php echo $nama_lengkap;?></p>
    <p><?php echo $r['nama_role'];?></p>
    <a href="keluar.php">Log Out</a><hr>

    <?php if ($r['id_role'] == 1) { ?>
        <h5>Buat menu</h5>
        <form action="" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
            <input name="nama_menu" type="text" placeholder="Nama menu" value="<?php echo $nama_menu?>" disabled><br>
            <input name="stok" type="number" placeholder="Jumlah Stok"><br>
            <img src="img/menu/<?php echo $gambar_menu?>" style="width:110px;"><br>

            <?php if (isset($_SESSION['add_stok'])) {?>
                <input type="submit" name="ubah_stok" value="Simpan Perubahan">
            <?php }?>
            <input type="submit" name="batal_stok" value="Batalkan">
        </form>

        <?php
            if (isset($_REQUEST['batal_stok'])) {

                if (isset($_SESSION['add_stok'])) {
                    unset($_SESSION['add_stok']);
                }
                header('location: menu.php');
            }

            if(isset($_POST['ubah_stok'])){
                $stok = $_POST['stok'];

                if ($stok > 0) {
                    $status_menu = 'tersedia';
                } else {
                    $status_menu = 'habis';
                }

                $now = date("Y-m-d");

                $query_ubah_stok = "insert into stok_in values('', '$id_menu', '$now', '$stok')";
                $sql_ubah_stok = mysqli_query($koneksi, $query_ubah_stok);

                if($sql_ubah_stok){
                    unset($_SESSION['add_stok']);
                    header('location: menu.php');
                }
            }
        }
        ?>
    <?php } ?>

</body>
</html>
<?php
    } else {
        header('location: keluar.php');
    }
    ob_flush();
?>