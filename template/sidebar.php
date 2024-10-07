<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- SIDEBAR -->
<aside id="sidebar">
    <!-- !change color -->
    <img class="logo" src="../kasir/img/logo.svg" alt="Logo">
    <ul>
        <a href="beranda.php">
            <li class="<?= ($current_page == 'beranda.php') ? 'active' : '' ?>">
                <span class="material-symbols-outlined">home</span>
                Beranda
            </li>
        </a>

        <?php if ($r['id_role'] == 1 || $r['id_role'] == 2 || $r['id_role'] == 3) { ?>
            <a href="menu.php">
                <li class="<?= ($current_page == 'menu.php') ? 'active' : '' ?>">
                    <span class="material-symbols-outlined">fork_spoon</span>
                    Menu
                </li>
            </a>
        <?php } ?>

        <?php if ($r['id_role'] == 1) { ?>
            <a href="karyawan.php">
                <li class="<?= ($current_page == 'karyawan.php') ? 'active' : '' ?>">
                    <span class="material-symbols-outlined">badge</span>
                    Karyawan
                </li>
            </a>
        <?php } ?>

        <?php if ($r['id_role'] == 2 || $r['id_role'] == 3 || $r['id_role'] == 4) { ?>
            <a href="order.php">
                <li class="<?= ($current_page == 'order.php') ? 'active' : '' ?>">
                    <span class="material-symbols-outlined">menu_book</span>
                    Order
                </li>
            </a>
            <a href="transaksi.php">
                <li class="<?= ($current_page == 'transaksi.php') ? 'active' : '' ?>">
                    <span class="material-symbols-outlined">payments</span>
                    Pembayaran
                </li>
            </a>
        <?php } ?>
        <?php if ($r['id_role'] == 1) { ?>
            <a href="laporan.php">
                <li class="<?= ($current_page == 'laporan.php') ? 'active' : '' ?>">
                    <span class="material-symbols-outlined">summarize</span>
                    Laporan
                </li>
            </a>
        <?php } ?>
        <a class="log-out" href="keluar.php">
            <li>
                <span class="material-symbols-outlined">logout</span>
                Keluar
            </li>
        </a>
    </ul>
</aside>
<!-- SIDEBAR -->