<?php
include "db/koneksi.php";

session_start();

if (isset($_SESSION['username'])) {
    header('location:beranda.php');
} else {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/login.css">
        <link rel="shortcut icon" href="img/favicon/favicon.ico" type="image/x-icon">
        <title>Masuk</title>
    </head>

    <body>
        <div class="wrapper">
            <h1>Selamat Datang</h1>
            <form method="post">
                <div class="input-group">
                    <input type="text" name="username" autocomplete="off" required="" class="field-box">
                    <label for="username">Username</label>
                </div>

                <div class="input-group">
                    <input type="password" name="password" autocomplete="off" required="" class="field-box">
                    <label for="password">Password</label>
                </div>
                <input type="submit" name="login" value="Masuk">

                <?php if (isset($_SESSION['error'])) { ?>
                    <div class="alert">
                        <?php echo $_SESSION['error'] ?>
                    </div>
                <?php }
                unset($_SESSION['error']) ?>
            </form>
        </div>
    </body>

    </html>
<?php
    if (isset($_REQUEST['login'])) {
        $role_a = array();
        $role_q = mysqli_query($koneksi, "SELECT * FROM role");

        while ($r = mysqli_fetch_array($role_q)) {
            array_push($role_a, $r['nama_role']);
        }

        $username = $_REQUEST['username'];
        $password = $_REQUEST['password'];

        $akun = mysqli_query($koneksi, "SELECT * FROM user NATURAL JOIN role");
        echo mysqli_error($koneksi);

        while ($r = mysqli_fetch_array($akun)) {

            if ($r['username'] == $username and $r['password'] == md5($password) and $r['status'] == 'aktif') {
                $_SESSION['username'] = $username;
                $_SESSION['id_user'] = $r['id_user'];
                $_SESSION['role'] = $r['id_role'];

                header('location: beranda.php');

                if (isset($_SESSION['error'])) {
                    unset($_SESSION['error']);
                }

                break;
            } else {
                $_SESSION['error'] = "Error: akun tidak bisa masuk";
                header('location:index.php');
            }
        }
    }
}
?>