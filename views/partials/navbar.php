<nav>
    <i class="bx bx-menu toggle-sidebar"></i>
    <div class="profile">
        <p><?php echo $r["nama_lengkap"] ?></p>
        <img src="/kasir/assets/img/user/<?php echo $r["gambar_user"] ?>" alt="Profile Icon">
        <ul class="dropdown profile-link">
            <li>
                <a href="http://" target="_blank" rel="noopener noreferrer">
                    <i class="bx bx-info-circle"></i>
                    <?php echo $r["nama_role"] ?>
                </a>
            </li>
            <li>
                <a href="/kasir/keluar.php">
                    <i class="bx bxs-log-out"></i>
                    Keluar
                </a>
            </li>
        </ul>
    </div>
</nav>