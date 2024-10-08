<?php
include "db/koneksi.php";
session_start();
ob_start();

$id = $_SESSION['id_user'];

if (isset($_SESSION['username'])) {

    $query = "select * from user natural join role where id_user = $id";
    $sql = mysqli_query($koneksi, $query);

    $nama_menu = "";
    $harga = "";
    $stok = "";
    $gambar_menu = "no_image.png";

    if (isset($_SESSION['edit_menu'])) {

        $id = $_SESSION['edit_menu'];
        $query_data_edit = "select * from menu where id_menu = $id";
        $sql_data_edit = mysqli_query($koneksi, $query_data_edit);
        $result_data_edit = mysqli_fetch_array($sql_data_edit);

        $id_menu = $result_data_edit['id_menu'];
        $nama_menu = $result_data_edit['nama_menu'];
        $harga = $result_data_edit['harga'];
        $stok = $result_data_edit['stok'];
        $gambar_menu = $result_data_edit['gambar_menu'];
    }

    while ($r = mysqli_fetch_array($sql)) {
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
            <p><?php echo $nama_lengkap; ?></p>
            <p><?php echo $r['nama_role']; ?></p>
            <a href="keluar.php">Log Out</a>
            <hr>

            <?php if ($r['id_role'] == 1) { ?>
                <h5>Buat menu</h5>
                <form action="" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                    <input name="nama_menu" type="text" placeholder="Nama menu" value="<?php echo $nama_menu ?>"><br>
                    <input name="harga" type="text" placeholder="Rupiah" value="<?php echo $harga ?>"><br>
                    <input name="stok" type="number" placeholder="Jumlah Stok" value="<?php echo $stok ?>"><br>
                    <input value="" name="gambar" type="file" accept="image/*" onchange="preview(this,'previewne')" value="<?php echo $gambar_menu ?>"><br>
                    <img src="img/menu/<?php echo $gambar_menu ?>" id="previewne" style="width:110px;"><br>

                    <?php if (isset($_SESSION['edit_menu'])) { ?>
                        <input type="submit" name="ubah_menu" value="Simpan Perubahan">
                    <?php } else { ?>
                        <input type="submit" name="tambah_menu" value="Tambahkan">
                    <?php } ?>
                    <input type="submit" name="batal_menu" value="Batalkan">
                </form>

            <?php
                if (isset($_POST['tambah_menu'])) {
                    $nama_menu = $_POST['nama_menu'];
                    $harga = $_POST['harga'];
                    $stok = $_POST['stok'];

                    $direktori = "img/menu/";

                    $tmp_name = $_FILES["gambar"]["tmp_name"];
                    $name = pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION);
                    $nama_baru = $_POST['nama_menu'] . "." . $name;
                    move_uploaded_file($tmp_name, $direktori . "/" . $nama_baru);
                    $gambar = $nama_baru;

                    if ($stok > 0) {
                        $status_menu = 'tersedia';
                    } else {
                        $status_menu = 'habis';
                    }

                    $query_tambah_menu = "insert into menu values ('','$nama_menu','$harga','$stok','$status_menu','$gambar')";
                    $sql_tambah_menu = mysqli_query($koneksi, $query_tambah_menu);

                    if ($sql_tambah_menu) {
                        header('location: menu.php');
                    }
                }

                if (isset($_REQUEST['batal_menu'])) {

                    if (isset($_SESSION['edit_menu'])) {
                        unset($_SESSION['edit_menu']);
                    }
                    header('location: menu.php');
                }

                if (isset($_POST['ubah_menu'])) {
                    $nama_menu = $_POST['nama_menu'];
                    $harga = $_POST['harga'];
                    $stok = $_POST['stok'];

                    if ($stok > 0) {
                        $status_menu = 'tersedia';
                    } else {
                        $status_menu = 'habis';
                    }

                    $gbr = $_FILES["gambar"]["name"];
                    $direktori = "img/menu/";

                    $tmp_name = $_FILES["gambar"]["tmp_name"];
                    $name = pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION);
                    $nama_baru = $_POST['nama_menu'] . "." . $name;
                    unlink('img/menu/' . $gambar_menu);
                    move_uploaded_file($tmp_name, $direktori . "/" . $nama_baru);
                    $gambar = $nama_baru;

                    $query_ubah_menu = "update menu set nama_menu = '$nama_menu', harga = '$harga', stok = '$stok', status_menu = '$status_menu', gambar_menu = '$gambar' where id_menu = '$id_menu'";
                    $sql_ubah_menu = mysqli_query($koneksi, $query_ubah_menu);

                    if ($sql_ubah_menu) {
                        unset($_SESSION['edit_menu']);
                        header('location: menu.php');
                    }
                }
            }
            ?>
        <?php } ?>

        <script type="text/javascript">
            function preview(gambar, idpreview) {
                var gb = gambar.files;

                for (var i = 0; i < gb.length; i++) {
                    var gbPreview = gb[i];
                    var imageType = /image.*/;
                    var preview = document.getElementById(idpreview);
                    var reader = new FileReader();

                    if (gbPreview.type.match(imageType)) {
                        preview.file = gbPreview;

                        reader.onload = (function(element) {
                            return function(e) {
                                element.src = e.target.result;
                            };
                        })(preview);

                        reader.readAsDataURL(gbPreview);
                    } else {
                        alert("Type file tidak sesuai. Khusus image.");
                    }

                }
            }
        </script>

        </body>

        </html>
    <?php
} else {
    header('location: keluar.php');
}
ob_flush();
    ?>