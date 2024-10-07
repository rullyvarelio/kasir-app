<?php
    include "db/koneksi.php";

    session_start();
    ob_start();

    $id = $_SESSION['id_user'];

    if (isset($_SESSION['edit_menu'])) {
        unset($_SESSION['edit_menu']);
    }
    
    if (isset($_SESSION['add_stok'])) {
        unset($_SESSION['add_stok']);
    }

    if (isset($_SESSION['username'])) {
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
    <title>Menu</title>
</head>
<body>
    <p><?php echo $nama_lengkap;?></p>
    <p><?php echo $r['nama_role'];?></p>
    <a href="keluar.php">Log Out</a><hr>

    <?php include "template/sidebar.php"?>

    <h5>Menu</h5>
    <?php if ($r['id_role'] == 1) { ?>
        <a href="buat_menu.php">Tambah menu</a> <hr>
    <?php } ?>

    <?php
        $query_data_makanan = "select * from menu order by id_menu";
        $sql_data_makanan = mysqli_query($koneksi, $query_data_makanan);

        while($r_dt_makanan = mysqli_fetch_array($sql_data_makanan)) {
    ?>        
        <img src="img/menu/<?php echo $r_dt_makanan['gambar_menu']?>" alt="gambar menu" style="width:200px">
        <p><?php echo $r_dt_makanan['nama_menu'];?></p>
        <p>Rp. <?php echo $r_dt_makanan['harga'];?></p>
        <p><?php echo $r_dt_makanan['stok'];?> Porsi</p>

        <form action="" method="post">
            <button type="submit" value="<?php echo $r_dt_makanan['id_menu']?>" name="add_stok">Stok</button>
            <button type="submit" value="<?php echo $r_dt_makanan['id_menu']?>" name="edit_menu">Edit</button>
            <button type="submit" value="<?php echo $r_dt_makanan['id_menu']?>" name="hapus_menu">Hapus</button>
        </form><hr>

    <?php }
        if (isset($_REQUEST['hapus_menu'])) {
            $id_masakan = $_REQUEST['hapus_menu'];
  
            $query_lihat = "select * from menu where id_menu = $id_masakan";
            $sql_lihat = mysqli_query($koneksi, $query_lihat);
            $result_lihat = mysqli_fetch_array($sql_lihat);

            if (file_exists('img/menu/'.$result_lihat['gambar_menu'])) {
                unlink('img/menu/'.$result_lihat['gambar_menu']);
            }

            $query_hapus_masakan = "delete from menu where id_menu = $id_masakan";
            $sql_hapus_masakan= mysqli_query($koneksi, $query_hapus_masakan);

            $qid = "alter table menu auto_increment = $id_masakan";
            mysqli_query($koneksi, $qid);

            if($sql_hapus_masakan){
                header('location: menu.php');
            }
        }

        if (isset($_REQUEST['edit_menu'])) {
            $id_masakan = $_REQUEST['edit_menu'];
            $_SESSION['edit_menu'] = $id_masakan;

            header('location: buat_menu.php');
        }

        if (isset($_REQUEST['add_stok'])) {
            $id_masakan = $_REQUEST['add_stok'];
            $_SESSION['add_stok'] = $id_masakan;

            header('location: stok_in.php');
        }
    ?>

</body>
</html>
<?php
}
    } else {
        header('location: keluar.php');
    }
    ob_flush();
?>