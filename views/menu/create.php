<?php
include "/xampp/htdocs/kasir/config/database.php";
session_start();
ob_start();

$id = $_SESSION["id_user"];


if (isset($_SESSION["username"])) {
    $query = "SELECT * FROM user NATURAL JOIN role WHERE id_user = $id";
    $sql = mysqli_query($koneksi, $query);

    $direktori = "/kasir/assets/img/menu/";
    $gambar_menu = $direktori . "no_image.png";

    while ($r = mysqli_fetch_array($sql)) {
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <link rel="stylesheet" href="/kasir/assets/css/crud.css">
            <link rel="shortcut icon" href="/kasir/assets/img/favicon/favicon.ico" type="image/x-icon">
            <title>Tambah menu</title>
        </head>

        <body>
            <!-- CONTENT -->
            <div class="container">

                <div class="crud">
                    <h1>Tambah menu</h1>
                    <form action="/kasir/controllers/menuController.php" method="post" enctype="multipart/form-data">
                        <div class="input-group">
                            <label for="nama_menu">Nama menu</label>
                            <input type="text" name="nama_menu" class="input-field">
                        </div>
                        <div class="input-group">
                            <label for="harga">Harga</label>
                            <input type="number" name="harga" class="input-field" inputmode="numeric" onkeypress="return restrictAlphabet(event)">
                        </div>
                        <div class="input-group">
                            <label for="stok">Stok</label>
                            <input type="number" name="stok" class="input-field" inputmode="numeric" onkeypress="return restrictAlphabet(event)">
                        </div>
                        <div class="input-group">
                            <label for="gambar">Gambar</label>
                            <input type="file" accept="image/*" name="gambar_menu" class="input-field" onchange="preview(this,'previewne')" value="<?php echo $gambar_menu ?>">
                            <img src="<?php echo $gambar_menu ?>" id="previewne" style="width:110px;"><br>
                        </div>
                        <div class="btn-group">
                            <input type="submit" name="tambah_menu" value="Tambahkan">
                            <input type="submit" name="batal_menu" value="Batalkan">
                        </div>
                    </form>
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
                            alert("Tipe gambar tidak sesuai (.png, .jpg, .jpeg, etc)");
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