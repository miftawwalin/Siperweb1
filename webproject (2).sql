-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Des 2024 pada 15.50
-- Versi server: 10.4.24-MariaDB
-- Versi PHP: 7.4.29

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `account`
--

INSERT INTO `account` (`id_account`, `email`, `password`, `akses`) VALUES
(1, 'rekka31@gmail.com', 'e00cf25ad42683b3df678c61f42c6bda', 2),
(2, 'ryan2@gmail.com', 'ryan123', 2),
(3, 'Mifta3@gmail.com', 'b5d54b3729c0a52fea8c0f84208065a9', 2),
(4, 'fiki12@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbldata_admin`
--

CREATE TABLE `tbldata_admin` (
  `id_admin` int(11) NOT NULL,
  `nip` int(20) DEFAULT NULL,
  `nm_lengkap` varchar(255) DEFAULT NULL,
  `no_hp` int(15) DEFAULT NULL,
  `nm_jabatan` varchar(255) DEFAULT NULL,
  `fk_account` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tbldata_admin`
--

INSERT INTO `tbldata_admin` (`id_admin`, `nip`, `nm_lengkap`, `no_hp`, `nm_jabatan`, `fk_account`) VALUES
(1, 10012001, 'Reka Uhuy', 2147483647, 'Direktur', 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `email_sekolah` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tbldata_sekolah`
--

INSERT INTO `tbldata_sekolah` (`no_induk`, `nm_sekolah`, `alamat`, `email_sekolah`) VALUES
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tblpeminjaman`
--

INSERT INTO `tblpeminjaman` (`pk_id`, `fk_induk_sekolah`, `fk_buku`, `tgl_peminjam`, `tgl_kembali`) VALUES
(1, 1021901011, 2, '2024-11-05 17:00:00', '2024-12-03 17:00:00'),
(6, 200192188, 6, '2024-12-11 17:00:00', '2024-12-11 17:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tblpengembalian`
--

CREATE TABLE `tblpengembalian` (
  `id_pengembalian` int(11) NOT NULL,
  `fk_peminjaman` int(11) DEFAULT NULL,
  `nilai_denda` int(11) DEFAULT 0,
  `tgl_kembali` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tblpengembalian`
--

INSERT INTO `tblpengembalian` (`id_pengembalian`, `fk_peminjaman`, `nilai_denda`, `tgl_kembali`) VALUES
(1, 1, 400000, '2024-12-12 13:45:54'),
(2, 6, 400000, '2024-12-12 13:44:22');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id_account`);

--
-- Indeks untuk tabel `tbldata_admin`
--
ALTER TABLE `tbldata_admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD KEY `fk_account` (`fk_account`);

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
-- Indeks untuk tabel `tblpengembalian`
--
ALTER TABLE `tblpengembalian`
  ADD PRIMARY KEY (`id_pengembalian`),
  ADD KEY `fk_peminjaman` (`fk_peminjaman`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `account`
--
ALTER TABLE `account`
  MODIFY `id_account` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tbldata_admin`
--
ALTER TABLE `tbldata_admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `tbldata_buku`
--
ALTER TABLE `tbldata_buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `tblpeminjaman`
--
ALTER TABLE `tblpeminjaman`
  MODIFY `pk_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `tblpengembalian`
--
ALTER TABLE `tblpengembalian`
  MODIFY `id_pengembalian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tbldata_admin`
--
ALTER TABLE `tbldata_admin`
  ADD CONSTRAINT `fk_account` FOREIGN KEY (`fk_account`) REFERENCES `account` (`id_account`) ON DELETE CASCADE ON UPDATE CASCADE;

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

--
-- Ketidakleluasaan untuk tabel `tblpengembalian`
--
ALTER TABLE `tblpengembalian`
  ADD CONSTRAINT `tblpengembalian_ibfk_1` FOREIGN KEY (`fk_peminjaman`) REFERENCES `tblpeminjaman` (`pk_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
