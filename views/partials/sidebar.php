<section id="sidebar">
    <div class="brand">
        <i class="bx bxs-basket icon"></i>
        RestoPro
    </div>
    <ul class="side-menu">
        <li>
            <a href="../dashboard.php" class="<?= ($current_page == "dashboard.php") ? "active" : "" ?>">
                <i class="bx bxs-dashboard icon"></i>
                Dashboard
            </a>
        </li>
        <li>
            <a href="/kasir/views/menu.php" class="<?= ($current_page == "menu.php") ? "active" : "" ?>">
                <i class="bx bxs-food-menu icon"></i>
                Menu
            </a>
        </li>
        <?php if ($r["id_role"] == 1) { ?>
            <li>
                <a href="/kasir/views/karyawan.php" class="<?= ($current_page == "karyawan.php") ? "active" : "" ?>">
                    <i class="bx bxs-user icon"></i>
                    Karyawan
                </a>
            </li>
        <?php } ?>
        <li>
            <a href="/kasir/views/order.php" class="<?= ($current_page == "order.php") ? "active" : "" ?>">
                <i class="bx bxs-book-content icon"></i>
                Order
            </a>
        </li>
        <li>
            <a href="/kasir/views/transaksi.php" class="<?= ($current_page == "transaksi.php") ? "active" : "" ?>">
                <i class="bx bxs-wallet icon"></i>
                Pembayaran
            </a>
        </li>
        <li>
            <a href="/kasir/views/laporan.php" class="<?= ($current_page == "laporan.php") ? "active" : "" ?>">
                <i class="bx bxs-report icon"></i>
                Laporan
            </a>
        </li>
    </ul>
</section>