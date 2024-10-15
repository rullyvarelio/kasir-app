<?php
include "../config/database.php";

session_start();
ob_start();

// GET CURRENT PAGE NAME
$current_page = basename($_SERVER["PHP_SELF"]); //WITH EXTENSION (.php)
$page_name = pathinfo($current_page, PATHINFO_FILENAME); //WITHOUT EXTENSION

if (isset($_SESSION["edit_order"])) {
	unset($_SESSION["edit_order"]);
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
					</div>
					<div class="transaction">
						<div class="before-payment">
							<h2>Belum Bayar</h2>
							<table>
								<tr>
									<th>No.</th>
									<th>Staff</th>
									<th>Total Harga</th>
									<th></th>
								</tr>
								<?php
								$no = 1;

								$query_order = "SELECT * FROM pesanan LEFT JOIN user ON pesanan.staff = user.id_user WHERE status_order = 'belum bayar'";
								$sql_order = mysqli_query($koneksi, $query_order);
								while ($r_order = mysqli_fetch_array($sql_order)) {

								?>
									<tr>
										<td><?php echo $no++ ?></td>
										<td><?php echo $r_order["nama_lengkap"]; ?></td>
										<td>
											<?php echo "Rp. " . number_format($r_order["total_harga"], 0, ",", "."); ?>
										</td>
										<td class="action">
											<form action="/kasir/controllers/transactionController.php" method="post">
												<center>
													<button type="submit" value="<?php echo $r_order["id_order"]; ?>" name="edit_order" class="process-order">Proses</button>
													<i class="bx bx-dots-vertical-rounded more-ic"></i>
													<ul class="dropdown more-link">
														<li>
															<button type="submit" value="<?php echo $r_order["id_order"]; ?>" name="hapus_order">Hapus</button>
														</li>
													</ul>
												</center>
											</form>
										</td>
									</tr>
								<?php } ?>
							</table>
						</div>
						<div class="previous-transaction">
							<h2>Transaksi Terdahulu</h2>

							<table>
								<tr>
									<th>No.</th>
									<th>Waktu Pesan</th>
									<th>Total Harga</th>
									<th>Bayar</th>
									<th>Kembali</th>
									<th></th>
								</tr>
								<?php
								$nomor = 1;
								$query_sudah_order = "SELECT * FROM pesanan LEFT JOIN user ON pesanan.staff = user.id_user WHERE status_order = 'sudah bayar' ORDER BY id_order DESC";
								$sql_sudah_order = mysqli_query($koneksi, $query_sudah_order);
								while ($r_sudah_order = mysqli_fetch_array($sql_sudah_order)) {
									$date = date_create($r_sudah_order["waktu_pesan"]);
								?>
									<tr>
										<td><?php echo $nomor++; ?></td>
										<td><?php echo date_format($date, "d-m H:i") ?></td>
										<td>
											<?php echo "Rp. " . number_format($r_sudah_order["total_harga"], 0, ",", "."); ?>
										</td>
										<td>
											<?php echo "Rp. " . number_format($r_sudah_order["uang_bayar"], 0, ",", "."); ?>
										</td>
										<td>
											<?php echo "Rp. " . number_format($r_sudah_order["uang_kembali"], 0, ",", "."); ?>
										</td>
										<td class="action">
											<form action="/kasir/controllers/transactionController.php" method="post">
												<a target="_blank" href="/kasir/print_struk.php?konten=<?php echo $r_sudah_order['id_order']; ?>">
													<i class="bx bxs-printer icon"></i>
												</a>
												<i class="bx bx-dots-vertical-rounded more-ic"></i>
												<ul class="dropdown more-link">
													<li>
														<button type="submit" value="<?php echo $r_sudah_order['id_order']; ?>" name="hapus_transaksi">
															Hapus
														</button>
													</li>
												</ul>
											</form>
										</td>
									</tr>
								<?php } ?>
							</table>
						</div>
					</div>
				</main>
				<!-- MAIN -->
			</section>
			<!-- CONTENT -->
			<script src="/kasir/assets/js/script.js"></script>

		</body>

		</html>
<?php }
} else {
	header("location: /kasir/keluar.php");
}
ob_flush();
?>