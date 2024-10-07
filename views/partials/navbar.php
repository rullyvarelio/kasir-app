<nav>
    <i class="bx bx-menu toggle-sidebar"></i>
    <div class="profile">
        <p><?php echo $r["nama_lengkap"] ?></p>
        <img src="assets/img/user/<?php echo $r["gambar_user"] ?>" alt="Profile Icon">
        <ul class="profile-link">
            <li>
                <i class="bx bx-info-circle"></i>
                <?php echo $r["nama_role"] ?>
            </li>
            <li>
                <a href="keluar.php">
                    <i class="bx bxs-log-out"></i>
                    Keluar
                </a>
            </li>
        </ul>
    </div>
</nav>