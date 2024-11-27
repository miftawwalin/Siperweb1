-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 27 Nov 2024 pada 09.42
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webproject`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `account`
--

CREATE TABLE `account` (
  `id_account` int(11) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(225) DEFAULT NULL,
  `akses` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `account`
--

INSERT INTO `account` (`id_account`, `email`, `password`, `akses`) VALUES
(1, 'rekka31@gmail.com', 'e00cf25ad42683b3df678c61f42c6bda', 2),
(2, 'ryan2@gmail.com', 'ryan123', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbldata_buku`
--

CREATE TABLE `tbldata_buku` (
  `id_buku` int(11) NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `penulis` varchar(255) DEFAULT NULL,
  `penerbit` varchar(255) DEFAULT NULL,
  `tahun_terbit` varchar(4) DEFAULT NULL,
  `fk_induk` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbldata_buku`
--

INSERT INTO `tbldata_buku` (`id_buku`, `judul`, `penulis`, `penerbit`, `tahun_terbit`, `fk_induk`) VALUES
(1, 'Matematika Cermat', 'Handoko Siswanto', 'Fiki Nur Sabani', '2020', 12919191),
(2, 'Fisika', 'Johan Cruyf', 'Malika', '2020', 1021901011),
(3, 'Siksa Kubur', 'Muhammad Ali', 'Rojak', '2012', 2147483647),
(5, 'Si Kancil yang Licik', 'Upin', 'Ipin', '2021', 23111),
(6, 'Jalan yang jauh', 'Jay', 'Stefan', '2019', 839219191),
(7, 'awdawdaw', 'wrerqwaw', 'awd', '1323', 839219191);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbldata_sekolah`
--

CREATE TABLE `tbldata_sekolah` (
  `no_induk` int(20) NOT NULL,
  `nm_sekolah` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbldata_sekolah`
--

INSERT INTO `tbldata_sekolah` (`no_induk`, `nm_sekolah`, `alamat`, `email`) VALUES
(23111, 'BINA KARYA', 'krw', 'bk@aaa.caa'),
(12919191, 'SMA', 'Jl Manunggal VII', 'akak@kaka.coa'),
(29119191, 'smk 1 krw', 'krw', 'smk1@krw.com'),
(98129891, 'smk2 krw', 'krw', 'smk2@krw.com'),
(200192188, 'SMKN 1 KARAWANG', 'JL BARU 2', 'smkn1@krw.com'),
(839219191, 'smk 3 krw', 'krw', 'smk3@krw.com'),
(888188181, 'SMK PERTANIAN', 'jl ahmad yani', 'smkpert@krw.com'),
(1021901011, 'SMAN 2 KARAWANG', 'Jl MANUNGGAL VII LAMARAN', 'sman2.krw@gmail.com'),
(1981928191, 'sman 1 krw', 'krw', 'sman1@krw.com'),
(2147483647, 'Taruna Karya', 'TK 2 tk 1', 'tktkt@alal.com');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tblpeminjaman`
--

CREATE TABLE `tblpeminjaman` (
  `pk_id` int(11) NOT NULL,
  `fk_induk_sekolah` int(11) DEFAULT NULL,
  `fk_buku` int(11) DEFAULT NULL,
  `tgl_peminjam` timestamp NULL DEFAULT NULL,
  `tgl_kembali` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tblpeminjaman`
--

INSERT INTO `tblpeminjaman` (`pk_id`, `fk_induk_sekolah`, `fk_buku`, `tgl_peminjam`, `tgl_kembali`) VALUES
(1, 1021901011, 2, '2024-11-24 17:00:00', '2024-12-30 17:00:00');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id_account`);

--
-- Indeks untuk tabel `tbldata_buku`
--
ALTER TABLE `tbldata_buku`
  ADD PRIMARY KEY (`id_buku`),
  ADD KEY `fk_buku_sekolah` (`fk_induk`);

--
-- Indeks untuk tabel `tbldata_sekolah`
--
ALTER TABLE `tbldata_sekolah`
  ADD PRIMARY KEY (`no_induk`);

--
-- Indeks untuk tabel `tblpeminjaman`
--
ALTER TABLE `tblpeminjaman`
  ADD PRIMARY KEY (`pk_id`),
  ADD KEY `fk_induk_sekolah` (`fk_induk_sekolah`),
  ADD KEY `fk_buku` (`fk_buku`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `account`
--
ALTER TABLE `account`
  MODIFY `id_account` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tbldata_buku`
--
ALTER TABLE `tbldata_buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `tblpeminjaman`
--
ALTER TABLE `tblpeminjaman`
  MODIFY `pk_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tbldata_buku`
--
ALTER TABLE `tbldata_buku`
  ADD CONSTRAINT `fk_buku_sekolah` FOREIGN KEY (`fk_induk`) REFERENCES `tbldata_sekolah` (`no_induk`);

--
-- Ketidakleluasaan untuk tabel `tblpeminjaman`
--
ALTER TABLE `tblpeminjaman`
  ADD CONSTRAINT `fk_buku` FOREIGN KEY (`fk_buku`) REFERENCES `tbldata_buku` (`id_buku`),
  ADD CONSTRAINT `fk_induk_sekolah` FOREIGN KEY (`fk_induk_sekolah`) REFERENCES `tbldata_sekolah` (`no_induk`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
