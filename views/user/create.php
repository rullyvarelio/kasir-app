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
                    <h1>Buat akun</h1>
                    <form action="/kasir/controllers/userController.php" method="post" enctype="multipart/form-data">
                        <div class="input-group">
                            <label for="nama_lengkap">Nama lengkap</label>
                            <input type="text" name="nama_lengkap" class="input-field">
                        </div>
                        <div class="input-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="input-field">
                        </div>
                        <div class="input-group">
                            <label for="password">Password</label>
                            <input type="text" name="password" class="input-field">
                        </div>
                        <div class="input-group">
                            <label for="gambar_user">Gambar</label>
                            <input name="gambar_user" type="file" accept="image/*" onchange="preview(this,'previewne')" value="<?php echo $gambar_user ?>" class="input-field">
                            <img src="<?php echo $gambar_user ?>" id="previewne" style="aspect-ratio: 1/1; width: 100px;">
                        </div>
                        <div class="btn-group">
                            <input type="submit" name="tambah_user" value="Tambahkan">
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