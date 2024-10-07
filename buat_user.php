<?php
include "db/koneksi.php";

session_start();
ob_start();

$id = $_SESSION['id_user'];

if (isset($_SESSION['username'])) {

    $query = "select * from user natural join role where id_user = $id";
    $sql = mysqli_query($koneksi, $query);

    $nama_lengkap = "";
    $username = "";
    $role = "";
    $status = "";
    $gambar_user = "no_profile.png";

    if (isset($_SESSION['edit_user'])) {

        $id = $_SESSION['edit_user'];
        $query_data_edit = "select * from user where id_user = $id";
        $sql_data_edit = mysqli_query($koneksi, $query_data_edit);
        $result_data_edit = mysqli_fetch_array($sql_data_edit);

        $id_user = $result_data_edit['id_user'];
        $nama_lengkap = $result_data_edit['nama_lengkap'];
        $username = $result_data_edit['username'];
        $role = $result_data_edit['id_role'];
        $status = $result_data_edit['status'];
        $gambar_user = $result_data_edit['gambar_user'];
    }

    while ($r = mysqli_fetch_array($sql)) {
        $nama_user = $r['nama_lengkap'];


?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <title>Buat user</title>
        </head>

        <body>
            <p><?php echo $nama_user; ?></p>
            <p><?php echo $r['nama_role']; ?></p>
            <a href="keluar.php">Log Out</a>
            <hr>

            <?php if ($r['id_role'] == 1) { ?>
                <h5>Buat user</h5>
                <form action="" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                    <input name="nama_lengkap" type="text" placeholder="Nama lengkap" value="<?php echo $nama_lengkap ?>"><br>
                    <input name="username" type="text" placeholder="Username" value="<?php echo $username ?>"><br>
                    <input name="password" type="text" placeholder="Password"><br>
                    <div>
                        <?php if ($role == 2) { ?>
                            <input type="radio" name="role_user" value="2" checked>
                        <?php } else { ?>
                            <input type="radio" name="role_user" value="2">
                        <?php } ?>
                        <label for="role_user">Kasir</label><br>

                        <?php if ($role == 3) { ?>
                            <input type="radio" name="role_user" value="3" checked>
                        <?php } else { ?>
                            <input type="radio" name="role_user" value="3">
                        <?php } ?>
                        <label for="role_user">Pelayan</label><br>

                        <?php if ($role == 4) { ?>
                            <input type="radio" name="role_user" value="4" checked>
                        <?php } else { ?>
                            <input type="radio" name="role_user" value="4">
                        <?php } ?>

                        <label for="role_user">Dapur</label><br>
                    </div>
                    <select name="status">
                        <option selected value="aktif">Aktif</option>
                        <option <?php if ($status == "nonaktif") {
                                    selected;
                                } ?> value="nonaktif">Nonaktif</option>
                    </select><br>
                    <input value="" name="gambar" type="file" accept="image/*" onchange="preview(this,'previewne')"><br>
                    <img src="img/user/<?php echo $gambar_user ?>" id="previewne" style="width:110px;"><br>

                    <?php if (isset($_SESSION['edit_user'])) { ?>
                        <input type="submit" name="ubah_user" value="Simpan Perubahan">
                    <?php } else { ?>
                        <input type="submit" name="tambah_user" value="Tambahkan">
                    <?php } ?>
                    <input type="submit" name="batal_user" value="Batalkan">
                </form>

            <?php
                if (isset($_POST['tambah_user'])) {
                    $nama_lengkap = $_POST['nama_lengkap'];
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    $role = $_POST['role_user'];
                    $status = $_POST['status'];

                    $direktori = "img/user/";

                    $tmp_name = $_FILES["gambar"]["tmp_name"];
                    $name = pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION);
                    $nama_baru = $_POST['nama_lengkap'] . "." . $name;
                    move_uploaded_file($tmp_name, $direktori . "/" . $nama_baru);
                    $gambar = $nama_baru;

                    $query_tambah_user = "insert into user values ('','$username', MD5('$password'),'$nama_lengkap','$role','$status','$gambar')";
                    $sql_tambah_user = mysqli_query($koneksi, $query_tambah_user);

                    if ($sql_tambah_user) {
                        header('location: karyawan.php');
                    }
                }

                if (isset($_REQUEST['batal_user'])) {

                    if (isset($_SESSION['edit_user'])) {
                        unset($_SESSION['edit_user']);
                    }
                    header('location: karyawan.php');
                }

                if (isset($_POST['ubah_user'])) {
                    $nama_lengkap = $_POST['nama_lengkap'];
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    $role = $_POST['role_user'];
                    $status = $_POST['status'];

                    $gbr = $_FILES["gambar"]["name"];
                    $direktori = "img/user/";

                    $tmp_name = $_FILES["gambar"]["tmp_name"];
                    $name = pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION);
                    $nama_baru = $_POST['nama_lengkap'] . "." . $name;
                    unlink('img/user/' . $gambar_user);
                    move_uploaded_file($tmp_name, $direktori . "/" . $nama_baru);
                    $gambar = $nama_baru;

                    $query_ubah_user = "update user set nama_lengkap = '$nama_lengkap', username = '$username', password = MD5('$password'), id_role = '$role', gambar_user = '$gambar', status = '$status' where id_user = '$id_user'";
                    $sql_ubah_user = mysqli_query($koneksi, $query_ubah_user);

                    if ($sql_ubah_user) {
                        unset($_SESSION['edit_user']);
                        header('location: karyawan.php');
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