-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Okt 2024 pada 11.50
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kasir_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `id_menu` int(11) NOT NULL,
  `nama_menu` varchar(255) NOT NULL,
  `harga` int(100) NOT NULL,
  `stok` int(100) NOT NULL,
  `status_menu` varchar(255) NOT NULL,
  `gambar_menu` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id_menu`, `nama_menu`, `harga`, `stok`, `status_menu`, `gambar_menu`) VALUES
(1, 'Ayam Goreng', 12000, 90, 'tersedia', 'Ayam Goreng.jpeg'),
(2, 'Ayam Bakar', 12000, 67, 'tersedia', 'Ayam Bakar.jpg'),
(3, 'Nasi Goreng', 10000, 100, 'tersedia', 'Nasi Goreng.jpg'),
(4, 'Mie Goreng', 15000, 48, 'tersedia', 'Mie Goreng.jpg'),
(7, 'Es teh', 5000, 70, 'tersedia', 'Es teh.jpg'),
(8, 'Es kopi', 7000, 89, 'tersedia', 'Es kopi.jpg'),
(9, 'Kentang goreng', 8500, 90, 'tersedia', 'Kentang goreng.jpg'),
(10, 'Macaron', 15000, 44, 'tersedia', 'Macaron.jpeg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `id_order` int(11) NOT NULL,
  `staff` int(11) NOT NULL,
  `waktu_pesan` datetime NOT NULL,
  `total_harga` int(11) NOT NULL,
  `uang_bayar` int(11) NOT NULL,
  `uang_kembali` int(11) NOT NULL,
  `status_order` varchar(155) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pesanan`
--

INSERT INTO `pesanan` (`id_order`, `staff`, `waktu_pesan`, `total_harga`, `uang_bayar`, `uang_kembali`, `status_order`) VALUES
(2, 2, '2024-10-02 00:08:58', 36000, 50000, 14000, 'sudah bayar'),
(4, 2, '2024-10-02 09:42:20', 36000, 50000, 14000, 'sudah bayar'),
(6, 2, '2024-10-02 09:48:09', 24000, 50000, 26000, 'sudah bayar'),
(7, 2, '2024-10-02 09:49:56', 12000, 50000, 38000, 'sudah bayar'),
(8, 2, '2024-10-02 14:06:28', 60000, 100000, 40000, 'sudah bayar'),
(9, 2, '2024-10-02 14:12:57', 36000, 100000, 64000, 'sudah bayar'),
(11, 1, '2024-10-12 23:28:24', 60000, 80000, 20000, 'sudah bayar'),
(12, 1, '2024-10-12 23:32:23', 87000, 100000, 13000, 'sudah bayar'),
(13, 1, '2024-10-12 23:36:54', 42000, 50000, 8000, 'sudah bayar');

-- --------------------------------------------------------

--
-- Struktur dari tabel `role`
--

CREATE TABLE `role` (
  `id_role` int(11) NOT NULL,
  `nama_role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `role`
--

INSERT INTO `role` (`id_role`, `nama_role`) VALUES
(1, 'admin'),
(2, 'kasir'),
(3, 'pelayan'),
(4, 'dapur');

-- --------------------------------------------------------

--
-- Struktur dari tabel `stok_in`
--

CREATE TABLE `stok_in` (
  `id_stok_in` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `jumlah` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `stok_in`
--

INSERT INTO `stok_in` (`id_stok_in`, `id_menu`, `tanggal_masuk`, `jumlah`) VALUES
(1, 1, '2024-10-02', 13),
(2, 1, '2024-10-02', 50),
(5, 1, '2024-10-02', 12),
(6, 1, '2024-10-08', 31),
(7, 2, '2024-10-10', 57),
(8, 3, '2024-10-10', 20);

--
-- Trigger `stok_in`
--
DELIMITER $$
CREATE TRIGGER `tambahstok` AFTER INSERT ON `stok_in` FOR EACH ROW BEGIN
	UPDATE menu SET stok = stok + new.jumlah
    WHERE id_menu = new.id_menu;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `stok_out`
--

CREATE TABLE `stok_out` (
  `id_stok_out` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `jumlah_terjual` int(11) NOT NULL,
  `status_cetak` varchar(155) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `stok_out`
--

INSERT INTO `stok_out` (`id_stok_out`, `id_transaksi`, `jumlah_terjual`, `status_cetak`) VALUES
(3, 3, 3, 'belum cetak'),
(4, 4, 3, 'belum cetak'),
(5, 5, 2, 'belum cetak'),
(6, 6, 1, 'belum cetak'),
(7, 7, 5, 'belum cetak'),
(8, 8, 5, 'belum cetak'),
(9, 9, 5, 'belum cetak'),
(10, 10, 1, 'belum cetak'),
(11, 11, 1, 'belum cetak'),
(12, 12, 2, 'belum cetak'),
(13, 13, 2, 'belum cetak'),
(14, 14, 1, 'belum cetak'),
(15, 15, 1, 'belum cetak'),
(16, 16, 2, 'belum cetak'),
(17, 17, 1, 'belum cetak'),
(18, 18, 1, 'belum cetak'),
(19, 19, 2, 'belum cetak'),
(20, 20, 2, 'belum cetak'),
(21, 21, 2, 'belum cetak'),
(22, 22, 1, 'belum cetak'),
(23, 23, 2, 'belum cetak'),
(24, 24, 2, 'belum cetak'),
(25, 25, 1, 'belum cetak'),
(26, 26, 2, 'belum cetak'),
(27, 27, 1, 'belum cetak'),
(28, 28, 3, 'belum cetak'),
(29, 29, 2, 'belum cetak'),
(30, 9, 5, 'belum cetak'),
(31, 10, 1, 'belum cetak'),
(32, 11, 1, 'belum cetak'),
(33, 12, 2, 'belum cetak'),
(34, 13, 2, 'belum cetak'),
(35, 14, 1, 'belum cetak'),
(36, 15, 1, 'belum cetak'),
(37, 16, 2, 'belum cetak'),
(38, 17, 1, 'belum cetak'),
(39, 18, 1, 'belum cetak'),
(40, 19, 2, 'belum cetak'),
(41, 20, 2, 'belum cetak'),
(42, 21, 2, 'belum cetak'),
(43, 22, 1, 'belum cetak'),
(44, 8, 5, 'belum cetak'),
(45, 9, 5, 'belum cetak'),
(46, 10, 1, 'belum cetak'),
(47, 11, 1, 'belum cetak'),
(48, 12, 2, 'belum cetak'),
(49, 13, 2, 'belum cetak');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_order` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `status_transaksi` varchar(155) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_user`, `id_order`, `id_menu`, `jumlah`, `status_transaksi`) VALUES
(3, 2, 2, 1, 3, 'sudah'),
(4, 2, 4, 1, 3, 'sudah'),
(5, 2, 6, 1, 2, 'sudah'),
(6, 2, 7, 1, 1, 'sudah'),
(7, 2, 8, 1, 5, 'sudah'),
(8, 1, 11, 2, 5, 'sudah'),
(9, 1, 12, 4, 5, 'sudah'),
(10, 1, 12, 1, 1, 'sudah'),
(11, 1, 13, 2, 1, 'sudah'),
(12, 1, 13, 4, 2, 'sudah');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `id_role` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `gambar_user` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `nama_lengkap`, `id_role`, `status`, `gambar_user`) VALUES
(1, 'admin1', '202cb962ac59075b964b07152d234b70', 'Syahirah Majid', 1, 'aktif', 'no_profile.png'),
(2, 'sri2', '202cb962ac59075b964b07152d234b70', 'Sri Mega', 2, 'aktif', 'Sri Mega.jpg'),
(7, 'faisal', '81dc9bdb52d04dc20036dbd8313ed055', 'Faisal Guntur', 4, 'aktif', 'Faisal Guntur.jpg'),
(8, 'bagus3', '81dc9bdb52d04dc20036dbd8313ed055', 'Bagus Bulan', 3, 'aktif', 'Bagus Bulan.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indeks untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_order`);

--
-- Indeks untuk tabel `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- Indeks untuk tabel `stok_in`
--
ALTER TABLE `stok_in`
  ADD PRIMARY KEY (`id_stok_in`);

--
-- Indeks untuk tabel `stok_out`
--
ALTER TABLE `stok_out`
  ADD PRIMARY KEY (`id_stok_out`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `id_role` (`id_role`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_order` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `stok_in`
--
ALTER TABLE `stok_in`
  MODIFY `id_stok_in` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `stok_out`
--
ALTER TABLE `stok_out`
  MODIFY `id_stok_out` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
