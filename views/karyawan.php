<?php
include "../config/database.php";

session_start();
ob_start();

// GET CURRENT PAGE NAME
$current_page = basename($_SERVER["PHP_SELF"]); //WITH EXTENSION (.php)
$page_name = pathinfo($current_page, PATHINFO_FILENAME); //WITHOUT EXTENSION

if (isset($_SESSION["edit_user"])) {
    unset($_SESSION["edit_user"]);
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
                            <a href="user/create.php">
                                <button>Buat user</button>
                            </a>
                        <?php } ?>
                    </div>
                    <table class="user-data">
                        <tr>
                            <th>No.</th>
                            <th>Nama lengkap</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                        <?php
                        $no = 1;
                        $query_user = "SELECT * FROM user NATURAL JOIN role ORDER BY id_user";
                        $sql_user = mysqli_query($koneksi, $query_user);

                        while ($r_user = mysqli_fetch_array($sql_user)) {
                        ?>
                            <tr>
                                <td><?php echo $no++ ?></td>
                                <td><?php echo $r_user["nama_lengkap"] ?></td>
                                <td><?php echo $r_user["username"] ?></td>
                                <td><?php echo $r_user["nama_role"] ?></td>
                                <td><?php echo $r_user["status"] ?></td>
                                <td>
                                    <form action="/kasir/controllers/userController.php" method="post">
                                        <button type="submit" value="<?php echo $r_user["id_user"] ?>" name="edit_user">Edit</button>
                                        <button type="submit" value="<?php echo $r_user["id_user"] ?>" name="hapus_user">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </main>
                <!-- MAIN -->
            </section>
            <!-- CONTENT -->

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