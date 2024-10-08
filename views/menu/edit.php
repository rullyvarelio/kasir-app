<?php
include "/xampp/htdocs/kasir/config/database.php";
session_start();
ob_start();

$id = $_SESSION["id_user"];

$direktori = "/kasir/assets/img/menu/";

if (isset($_SESSION["username"])) {

    $query = "SELECT * FROM user NATURAL JOIN role WHERE id_user = $id";
    $sql = mysqli_query($koneksi, $query);

    if (isset($_SESSION["edit_menu"])) {

        $id = $_SESSION["edit_menu"];
        $query_edit = "SELECT * FROM menu WHERE id_menu = $id";
        $sql_edit = mysqli_query($koneksi, $query_edit);
        $result_edit = mysqli_fetch_array($sql_edit);

        $id_menu = $result_edit['id_menu'];
        $nama_menu = $result_edit['nama_menu'];
        $harga = $result_edit['harga'];
        $stok = $result_edit['stok'];
        $gambar_menu = $direktori . $result_edit['gambar_menu'];
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
            <title>Buat menu</title>
        </head>

        <body>
            <!-- CONTENT -->
            <div class="container">
                <div class="crud">
                    <?php if ($r['id_role'] == 1) { ?>
                        <h1>Update menu</h1>
                        <form action="/kasir/controllers/crudControllers.php" method="post" enctype="multipart/form-data">
                            <div class="input-group">
                                <label for="nama_menu">Nama menu</label>
                                <input type="text" name="nama_menu" class="input-field" value="<?php echo $nama_menu ?>">
                            </div>
                            <div class="input-group">
                                <label for="harga">Harga</label>
                                <input type="number" name="harga" class="input-field" inputmode="numeric" onkeypress="return restrictAlphabet(event)" value="<?php echo $harga ?>">
                            </div>
                            <div class="input-group">
                                <label for="stok">Stok</label>
                                <input type="number" name="stok" class="input-field" inputmode="numeric" onkeypress="return restrictAlphabet(event)" value="<?php echo $stok ?>">
                            </div>
                            <div class="input-group">
                                <label for="gambar_menu">Gambar</label>
                                <input type="file" accept="image/*" name="gambar_menu" class="input-field" onchange="preview(this,'previewne')" value="<?php echo $gambar_menu ?>">
                                <img src="<?php echo $gambar_menu ?>" id="previewne"><br>
                            </div>
                            <div class="btn-group">
                                <input type="submit" name="ubah_menu" value="Simpan Perubahan">
                                <input type="submit" name="batal_menu" value="Batalkan">
                            </div>
                        </form>
                    <?php } ?>
                </div>
            </div>
            <!-- CONTENT -->

            <script type="text/javascript">
                function restrictAlphabet(e) {
                    var x = e.which || e.keycode;
                    if (x >= 48 && x <= 57) {
                        return true
                    } else {
                        return false
                    }

                }

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
    header('location: keluar.php');
}
ob_flush();
?>