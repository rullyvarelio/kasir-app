<?php
include "../config/database.php";

session_start();
ob_start();

// GET CURRENT PAGE NAME
$current_page = basename($_SERVER["PHP_SELF"]); //WITH EXTENSION (.php)
$page_name = pathinfo($current_page, PATHINFO_FILENAME); //WITHOUT EXTENSION

// UNSET SESSION
if (isset($_SESSION["edit_menu"])) {
    unset($_SESSION["edit_menu"]);
}
if (isset($_SESSION["add_stok"])) {
    unset($_SESSION["add_stok"]);
}

$id = $_SESSION["id_user"];

if (isset($_SESSION["username"])) {
    $query = "SELECT * FROM user NATURAL JOIN role WHERE id_user = $id";
    $sql = mysqli_query($koneksi, $query);

    while ($r = mysqli_fetch_array($sql)) {

?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <link rel="stylesheet" href="/kasir/assets/css/main.css">
            <link rel="stylesheet" href="/kasir/assets/css/sidebar.css">
            <link rel="stylesheet" href="/kasir/assets/css/navbar.css">
            <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
            <link rel="shortcut icon" href="/kasir/assets/img/favicon/favicon.ico" type="image/x-icon">
            <title><?php echo ucfirst($page_name) ?></title>
        </head>

        <body>
            <!-- SIDEBAR -->
            <?php include "../views/partials/sidebar.php" ?>
            <!-- SIDEBAR -->

            <!-- CONTENT -->
            <section id="content">
                <!-- NAVBAR -->
                <?php include "../views/partials/navbar.php" ?>
                <!-- NAVBAR -->

                <!-- MAIN -->
                <main>
                    <div class="menu-head">
                        <h1 class="title"><?php echo $page_name ?></h1>

                        <?php if ($r["id_role"] == 1) { ?>
                            <a href="menu/create.php">
                                <button>Tambah menu</button>
                            </a>
                        <?php } ?>
                    </div>
                    <div class="info-data center">
                        <?php
                        $query_menu = "SELECT * FROM menu ORDER BY id_menu";
                        $sql_menu = mysqli_query($koneksi, $query_menu);

                        while ($r_menu = mysqli_fetch_array($sql_menu)) {
                            $nama_menu = $r_menu["nama_menu"];
                            $harga_menu = "Rp. " . number_format($r_menu["harga"], 0, ",", ".");
                            $stok_menu = $r_menu["stok"];
                        ?>
                            <div class="card">
                                <?php if ($r["id_role"] == 1) { ?>
                                    <form action="/kasir/controllers/menuControllers.php" method="post" class="more">
                                        <i class="bx bx-dots-horizontal-rounded more-ic"></i>
                                        <ul class="dropdown more-link">
                                            <li>
                                                <button type="submit" value="<?php echo $r_menu["id_menu"] ?>" name="add_stok">Update Stok</button>
                                            </li>
                                            <li>
                                                <button type="submit" value="<?php echo $r_menu["id_menu"] ?>" name="edit_menu">Edit Menu</button>
                                            </li>
                                            <li>
                                                <button type="submit" value="<?php echo $r_menu["id_menu"] ?>" name="hapus_menu">Hapus Menu</button>
                                            </li>
                                        </ul>
                                    </form>
                                <?php } ?>
                                <img src="/kasir/assets/img/menu/<?php echo $r_menu["gambar_menu"] ?>" alt="gambar menu">
                                <div class="card-info">
                                    <h5><?php echo $nama_menu ?></h5>
                                    <h3><?php echo $harga_menu; ?></h3>
                                    <span><?php echo $stok_menu ?> Porsi</span>
                                </div>
                            </div>

                        <?php } ?>
                    </div>
                </main>
                <!-- MAIN -->
            </section>

            <script src="/kasir/assets/js/script.js"></script>
        </body>


        </html>
<?php
    }
} else {
    header("location: /kasir/keluar.php");
}
ob_flush();
?>