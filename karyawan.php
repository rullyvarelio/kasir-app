<?php
    include "db/koneksi.php";

    session_start();
    ob_start();

    $id = $_SESSION['id_user'];

    if (isset($_SESSION['edit_user'])) {
        echo $_SESSION['edit_user'];
        unset($_SESSION['edit_user']);
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
    <title>User</title>
</head>
<body>
    <p><?php echo $nama_lengkap;?></p>
    <p><?php echo $r['nama_role'];?></p>
    <a href="keluar.php">Log Out</a><hr>

    <?php include "template/sidebar.php"?>

    <?php if ($r['id_role'] == 1) { ?>
        <h5>Karyawan</h5>
        <a href="buat_user.php">Tambah user</a> <hr>

        <?php
            $query_data_user = "select * from user natural join role order by id_user";
            $sql_data_user = mysqli_query($koneksi, $query_data_user);

            while($r_dt_user = mysqli_fetch_array($sql_data_user)) {
        ?>        
            <img src="img/user/<?php echo $r_dt_user['gambar_user']?>" alt="gambar user" style="width:200px">
            <p><?php echo $r_dt_user['nama_lengkap'];?></p>
            <p><?php echo $r_dt_user['nama_role'];?></p>
            <p><?php echo $r_dt_user['status'];?></p>
            <form action="" method="post">
                <button type="submit" value="<?php echo $r_dt_user['id_user']?>" name="edit_user">Edit</button>
                <button type="submit" value="<?php echo $r_dt_user['id_user']?>" name="hapus_user">Hapus</button>
            </form><hr>

        <?php }
            if (isset($_REQUEST['hapus_user'])) {
                $id_user = $_REQUEST['hapus_user'];
  
                $query_lihat = "select * from user where id_user = $id_user";
                $sql_lihat = mysqli_query($koneksi, $query_lihat);
                $result_lihat = mysqli_fetch_array($sql_lihat);

                if (file_exists('img/user/'.$result_lihat['gambar_user'])) {
                    unlink('img/user/'.$result_lihat['gambar_user']);
                }

                $query_hapus_user = "delete from user where id_user = $id_user";
                $sql_hapus_user = mysqli_query($koneksi, $query_hapus_user);

                $qid = "alter table user auto_increment = $id_user";
                mysqli_query($koneksi, $qid);

                if($sql_hapus_user){
                    header('location: karyawan.php');
                }
            }

            if (isset($_REQUEST['edit_user'])) {
                $id_user = $_REQUEST['edit_user'];
                $_SESSION['edit_user'] = $id_user;

                header('location: buat_user.php');
            }
        ?>
    <?php } ?>
</body>
</html>
<?php
}
    } else {
        header('location: keluar.php');
    }
    ob_flush();
?>