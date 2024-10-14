<?php
include "config/database.php";

session_start();
ob_start();

$id = $_SESSION["id_user"];

if (isset($_SESSION["username"])) {
	$query = "SELECT * FROM user NATURAL JOIN role WHERE id_user = $id";
	$sql = mysqli_query($koneksi, $query);

	while ($r = mysqli_fetch_array($sql)) {

?>
		<!DOCTYPE html>
		<html lang="en">

		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
			<title>Struk</title>
			<style>
				* {
					margin: 0;
					padding: 0;
				}

				@page {
					size: auto;
				}

				body {
					background: rgb(204, 204, 204);
				}

				page {
					background: white;
					display: block;
					margin: 0 auto;
					margin-bottom: 0.5cm;
					box-shadow: 0 0 0.1cm rgba(0, 0, 0, 0.5);
				}

				page[size="dipakai"][layout="landscape"] {
					width: 20cm;
					height: 20cm;
				}

				#remove {
					padding: 1em;
					cursor: pointer;
				}

				.margin {
					margin: 1em 2em;
				}

				.row2 {
					width: 100%;
					display: flex;
					align-items: center;
					column-gap: 2em;

				}

				.column {
					display: flex;
					justify-content: space-between;
				}

				.column.center {
					justify-content: center;
				}

				.row {
					margin: 0;
					padding: 0;
					display: flex;
					column-gap: 1em;
				}

				.column p {
					font-size: 1.2em;
				}

				@media print {

					body,
					page {
						margin: 0 1em;
						box-shadow: 0;
					}
				}
			</style>
		</head>

		<body>
			<page size="dipakai" layout="landscape">
				<br>
				<span id="remove">
					<a id="ct">
						<i class="bx bxs-printer"></i>
					</a>
				</span>

				<?php
				$id_order = $_REQUEST['konten'];
				$query_order = "select * from pesanan left join user on pesanan.staff = user.id_user where id_order = $id_order";
				$sql_order = mysqli_query($koneksi, $query_order);
				$result_order = mysqli_fetch_array($sql_order);
				//echo $id_order
				?>

				<center>
					<h1>
						RestoPro
					</h1>
					<span>
						(561) 498-9452 | restopro.7@restaurant.com<br>
						82206 Coralie Stravenue, Brigitteton, CA 43961-1921
					</span>
				</center>

				<br>
				<hr>

				<div class="margin row2">
					<h2>Waktu Pesan : </h2>
					<h3>
						<?php
						$date = date_create($result_order["waktu_pesan"]);
						echo date_format($date, "d-m-Y H:i");
						?>
					</h3>
				</div>

				<hr>

				<div class="margin info">
					<?php
					$query_order_fiks = "SELECT * FROM transaksi NATURAL JOIN menu WHERE id_order = $id_order";
					$sql_order_fiks = mysqli_query($koneksi, $query_order_fiks);
					while ($r_order_fiks = mysqli_fetch_array($sql_order_fiks)) {
					?>
						<div class="column">
							<div class="row">
								<p><?php echo $r_order_fiks['jumlah']; ?></p>
								<p>
									<?php echo $r_order_fiks['nama_menu']; ?>
								</p>
							</div>
							<div class="row">
								<p>
									<?php
									$hasil = $r_order_fiks["harga"] * $r_order_fiks["jumlah"];
									echo "Rp. " . number_format($hasil, 0, ",", ".");
									?>
								</p>
							</div>
						</div>
					<?php } ?>
				</div>
				<hr>
				<div class="margin info">
					<?php
					$query_harga = "SELECT * FROM pesanan WHERE id_order = $id_order";
					$sql_harga = mysqli_query($koneksi, $query_harga);
					$result_harga = mysqli_fetch_array($sql_harga);
					?>
					<div class="column">
						<p>Total</p>
						<strong>
							<p>
								<?php
								echo "Rp. " . number_format($result_harga['total_harga'], 0, ",", ".");
								?>
							</p>
						</strong>
					</div>
					<div class="column">
						<p>Bayar</p>
						<strong>
							<div>
								<p>
									<?php
									echo "Rp. " . number_format($result_harga['uang_bayar'], 0, ",", ".");
									?>
								</p>
							</div>
						</strong>
					</div>
					<div class="column">
						<p>Kembali</p>
						<strong>
							<div>
								<p>
									<?php
									echo "Rp. " . number_format($result_harga['uang_kembali'], 0, ",", ".");
									?>
								</p>
							</div>
						</strong>
					</div>
				</div>
				<hr>
				<div class="margin info">
					<div class="column center">
						<p>- Terima Kasih -</p>
					</div>
				</div>

			</page>

			<script src="assets/js/jquery-3.7.1.min.js"></script>
			<script type="text/javascript">
				document.getElementById('ct').onclick = function() {
					$("#remove").remove();
					window.print();
				}
				$(document).ready(function() {
					$("remove").remove();

				});
			</script>
		</body>

		</html>
<?php }
} else {
	header("location: /kasir/keluar.php");
}
ob_flush();
?>