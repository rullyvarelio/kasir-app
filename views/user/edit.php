<?php
include "/xampp/htdocs/kasir/config/database.php";
session_start();
ob_start();

$id = $_SESSION['id_user'];

if (isset($_SESSION['username'])) {

    $query = "select * from user natural join role where id_user = $id";
    $sql = mysqli_query($koneksi, $query);

    $direktori = "/kasir/assets/img/user/";
    $gambar_user = $direktori . "no_profile.png";

    if (isset($_SESSION["edit_user"])) {

        $id = $_SESSION["edit_user"];
        $query_data_edit = "SELECT * FROM user WHERE id_user = $id";
        $sql_data_edit = mysqli_query($koneksi, $query_data_edit);
        $result_data_edit = mysqli_fetch_array($sql_data_edit);

        $id_user = $result_data_edit["id_user"];
        $nama_lengkap = $result_data_edit["nama_lengkap"];
    }

    while ($r = mysqli_fetch_array($sql)) {
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <link rel="stylesheet" href="/kasir/assets/css/crud.css">
            <link rel="shortcut icon" href="/kasir/assets/img/favicon/favicon.ico" type="image/x-icon">
            <title>Tambah akun</title>
        </head>

        <body>
            <!-- CONTENT -->
            <div class="container">
                <div class="crud">
                    <h1>Update akun</h1>
                    <form action="/kasir/controllers/userControllers.php" method="post" enctype="multipart/form-data">
                        <div class="input-group">
                            <label for="nama_lengkap">Nama lengkap</label>
                            <input type="text" name="nama_lengkap" class="input-field" value="<?php echo $nama_lengkap ?>" readonly>
                        </div>
                        <div class="input-group">
                            <label for="status">Status</label>
                            <select name="status" class="input-field">
                                <option value="aktif" selected>Aktif</option>
                                <option value="tidak aktif">Tidak aktif</option>
                            </select>
                        </div>
                        <div class="input-group role-group">
                            <label>Pilih Role</label>
                            <div class="flex-role">
                                <div class="role">
                                    <input type="radio" name="role_user" value=2>
                                    <label for="role_user">Kasir</label>
                                </div>
                                <div class="role">
                                    <input type="radio" name="role_user" value=3>
                                    <label for="role_user">Pelayan</label>
                                </div>
                                <div class="role">
                                    <input type="radio" name="role_user" value=4>
                                    <label for="role_user">Dapur</label>
                                </div>
                            </div>
                        </div>
                        <div class="btn-group">
                            <input type="submit" name="ubah_user" value="Simpan Perubahan">
                            <input type="submit" name="batal_user" value="Batalkan">
                        </div>
                    </form>
                </div>
            </div>
            <!-- CONTENT -->

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
<?php }
} else {
    header("location: /xampp/htdocs/kasir/keluar.php");
}
ob_flush();
?>