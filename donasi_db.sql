-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 30 Des 2024 pada 14.53
-- Versi server: 8.0.30
-- Versi PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `donasi_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `donasi`
--

CREATE TABLE `donasi` (
  `id_donasi` int NOT NULL,
  `id_donatur` int NOT NULL,
  `id_program` int DEFAULT NULL,
  `total` int NOT NULL,
  `tanggal` date NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `donasi`
--

INSERT INTO `donasi` (`id_donasi`, `id_donatur`, `id_program`, `total`, `tanggal`, `deskripsi`) VALUES
(8, 3, 7, 230000000, '2024-12-30', 'asfsgfsgfsdg'),
(9, 2, 11, 4000000, '2024-12-30', 'nih buat jamu'),
(11, 1, 11, 500000000, '2024-12-30', 'no komen');

--
-- Trigger `donasi`
--
DELIMITER $$
CREATE TRIGGER `after_donasi_update` AFTER INSERT ON `donasi` FOR EACH ROW BEGIN
  UPDATE program_donasi
  SET status = CASE
      WHEN (SELECT SUM(total) FROM donasi WHERE donasi.id_program = NEW.id_program) >= program_donasi.target_dana
      THEN 'Tercapai'
      ELSE 'Belum Tercapai'
  END
  WHERE id_program = NEW.id_program;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `donatur`
--

CREATE TABLE `donatur` (
  `id_donatur` int NOT NULL,
  `nama_donatur` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email_donatur` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `no_telp_donatur` varchar(13) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `donatur`
--

INSERT INTO `donatur` (`id_donatur`, `nama_donatur`, `email_donatur`, `no_telp_donatur`, `alamat`) VALUES
(1, 'Nazar', 'nazar@gmail.com', '9876587698787', 'jl.Uciha No23'),
(2, 'fahmi', 'fahmi@gmail.com', '98712346', 'Jakarta'),
(3, 'aji', 'aji@gmail.com', '0989876567843', 'dimana ajalah yang penting idup'),
(5, 'julian', 'julian@gmail.com', '0987890964567', 'di hati kamuv');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penerima`
--

CREATE TABLE `penerima` (
  `id_penerima` int NOT NULL,
  `nama_penerima` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penerima`
--

INSERT INTO `penerima` (`id_penerima`, `nama_penerima`, `alamat`) VALUES
(2, 'agustian', 'Konoha uchiha'),
(4, 'Sunade', 'Konoha'),
(5, 'agustiani', 'butuh ponakan ');

-- --------------------------------------------------------

--
-- Struktur dari tabel `petugas`
--

CREATE TABLE `petugas` (
  `id_petugas` int NOT NULL,
  `email_admin` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_petugas` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `no_telp_admin` varchar(13) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `petugas`
--

INSERT INTO `petugas` (`id_petugas`, `email_admin`, `nama_petugas`, `no_telp_admin`, `password`) VALUES
(1, 'admin1@gmail.com', 'Lord_Fahmi', '987677676', '$2y$10$KWL7nTouxkL7V1/LpH2io.yNTiW39514ynzsQWwuOTvkDLT02x10q'),
(2, 'admin2@gmail.com', 'Nazar_Jas', '989876545', '$2y$10$ldM9v8ZkSXuu.AmnvxfFMe0Xswnv9XezGrk8RBRps8PFFxzq3Ggpu'),
(4, 'admin3@gmail.com', 'Aji kartikok', '98767989', '$2y$10$iX6XUXl.1n2/2IALMpHxd.BQQYfc845b/l9QdzrTnbuAlqx9vWvaS'),
(9, 'admin4@gmail.com', 'julian', '98776534', '$2y$10$I6aiRGCqIGqEWzKEkPDKUO98/aGf6eWTt8rKhYLiRZKAzO6zTHgH6');

-- --------------------------------------------------------

--
-- Struktur dari tabel `program_donasi`
--

CREATE TABLE `program_donasi` (
  `id_program` int NOT NULL,
  `id_petugas` int NOT NULL,
  `id_penerima` int DEFAULT NULL,
  `id_donasi` int DEFAULT NULL,
  `nama_program` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `target_dana` int NOT NULL,
  `alamat` text COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `tanggal` date NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'Belum Tercapai'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `program_donasi`
--

INSERT INTO `program_donasi` (`id_program`, `id_petugas`, `id_penerima`, `id_donasi`, `nama_program`, `target_dana`, `alamat`, `deskripsi`, `tanggal`, `status`) VALUES
(7, 2, NULL, NULL, 'Donasi Agus', 230000000, 'negara api', 'bacot gustian', '2025-01-11', 'Tercapai'),
(10, 2, 4, NULL, 'Donasi  Mata Uciha Sisui', 400000034, 'Konoha Gakure', 'Membagun infrastruktur yang hancur', '2025-03-26', 'Belum Tercapai'),
(11, 2, 4, NULL, 'Donasi  bencana Paint', 500000000, 'Konoha', 'untuk membangun kembali konoha', '2024-12-30', 'Tercapai');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `donasi`
--
ALTER TABLE `donasi`
  ADD PRIMARY KEY (`id_donasi`),
  ADD KEY `id_donatur` (`id_donatur`),
  ADD KEY `fk_donasi_program` (`id_program`);

--
-- Indeks untuk tabel `donatur`
--
ALTER TABLE `donatur`
  ADD PRIMARY KEY (`id_donatur`);

--
-- Indeks untuk tabel `penerima`
--
ALTER TABLE `penerima`
  ADD PRIMARY KEY (`id_penerima`);

--
-- Indeks untuk tabel `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`id_petugas`);

--
-- Indeks untuk tabel `program_donasi`
--
ALTER TABLE `program_donasi`
  ADD PRIMARY KEY (`id_program`),
  ADD KEY `id_petugas` (`id_petugas`),
  ADD KEY `id_donasi` (`id_donasi`),
  ADD KEY `fk_penerima` (`id_penerima`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `donasi`
--
ALTER TABLE `donasi`
  MODIFY `id_donasi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `donatur`
--
ALTER TABLE `donatur`
  MODIFY `id_donatur` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `penerima`
--
ALTER TABLE `penerima`
  MODIFY `id_penerima` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `petugas`
--
ALTER TABLE `petugas`
  MODIFY `id_petugas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `program_donasi`
--
ALTER TABLE `program_donasi`
  MODIFY `id_program` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `donasi`
--
ALTER TABLE `donasi`
  ADD CONSTRAINT `donasi_ibfk_1` FOREIGN KEY (`id_donatur`) REFERENCES `donatur` (`id_donatur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_donasi_program` FOREIGN KEY (`id_program`) REFERENCES `program_donasi` (`id_program`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `program_donasi`
--
ALTER TABLE `program_donasi`
  ADD CONSTRAINT `fk_penerima` FOREIGN KEY (`id_penerima`) REFERENCES `penerima` (`id_penerima`) ON DELETE CASCADE,
  ADD CONSTRAINT `program_donasi_ibfk_1` FOREIGN KEY (`id_petugas`) REFERENCES `petugas` (`id_petugas`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `program_donasi_ibfk_2` FOREIGN KEY (`id_donasi`) REFERENCES `donasi` (`id_donasi`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
