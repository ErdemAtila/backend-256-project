-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 20, 2024 at 03:05 PM
-- Server version: 10.11.7-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u310488741_avDb`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `district` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `address` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `remember` varchar(200) DEFAULT NULL,
  `image` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `name`, `city`, `district`, `address`, `type`, `remember`, `image`) VALUES
(1007, 'migros@gmail.com', '$2y$10$YvdjjkX5vXyD7lu7YyeRX.ZpcssqXs1R7RjgxjczFxQYFsaqTW3kO', 'Migros ', 'İstanbul', 'Beşiktaş', 'Yıldız Mahallesi, Dolmabahçe Caddesi No:10', 'market', NULL, NULL),
(1006, 'a101@gmail.com', '$2y$10$YvdjjkX5vXyD7lu7YyeRX.ZpcssqXs1R7RjgxjczFxQYFsaqTW3kO', 'A101', 'İzmir', 'Konak', 'Cennet Mahallesi, Güzelbahçe Sokak No:7', 'market', NULL, NULL),
(1005, 'carrefoursa@gmail.com', '$2y$10$YvdjjkX5vXyD7lu7YyeRX.ZpcssqXs1R7RjgxjczFxQYFsaqTW3kO', 'CarrefourSA', 'Bursa', 'Osmangazi', 'Kavacık Mahallesi, Doru Caddesi No:15', 'market', NULL, NULL),
(1008, 'sokucuz@gmail.com', '$2y$10$YvdjjkX5vXyD7lu7YyeRX.ZpcssqXs1R7RjgxjczFxQYFsaqTW3kO', 'ŞOK', 'Kütahya', 'Tavşanlı', 'Orta Mahallesi,Cumhuriyet Sokak No:5', 'market', NULL, NULL),
(1009, 'bim@gmail.com', '$2y$10$YvdjjkX5vXyD7lu7YyeRX.ZpcssqXs1R7RjgxjczFxQYFsaqTW3kO', 'BİM', 'Antalya', 'Muratpaşa', 'Güleryüz Mahallesi, Liman Caddesi No:5', 'market', NULL, NULL),
(1010, 'serkangenc@gmail.com', '$2y$10$YvdjjkX5vXyD7lu7YyeRX.ZpcssqXs1R7RjgxjczFxQYFsaqTW3kO', 'Serkan Genç', 'Ankara', 'Çankaya', 'Bilkent Üniversitesi, Doğu Kampüs C Binası', 'consumer', NULL, NULL),
(1011, 'erdematilla@gmail.com', '$2y$10$YvdjjkX5vXyD7lu7YyeRX.ZpcssqXs1R7RjgxjczFxQYFsaqTW3kO', 'Erdem Atilla', 'Ankara', 'Bahçelievler', 'Bahçelievler Mahallesi, Atatürk Bulvarı No:30', 'consumer', NULL, NULL),
(1001, 'erdematila07@hotmail.com', '$2y$10$SY68ZW4L6e89H9ya.CZxh.bVNmWANSx9zCDwGudyMBqchdU4qVCZO', 'Erdem 3M', 'Ankara', 'Çankaya', 'cumhuriyet mahallesi , akasya sokak , çevre yolu üzeri', 'market', '', NULL),
(1013, 'erdemit07@gmail.com', '$2y$10$3r1DgrVlmi6pmdg4pbCCXOUv/mv39fsd2XDNzbKrBkHPGJU2QLK1W', 'Erdem Atila', 'Ankara', 'Çankaya', 'Üniversiteler Bilkent Kampüsü No 92 06800 Çankaya Ankara', 'consumer', '', NULL),
(1014, 'ardaduyar2003@hotmail.com', '$2y$10$bmtQYJMAmPwRGrwL8MZCZ.Cj65JM2KKv.xLr/ecmYAENGjPARcm6K', 'Arda Duyar', 'Ankara', 'Çankaya', 'Üniversiteler Bilkent Kampüsü No 92 06800 Çankaya Ankara', 'market', NULL, NULL),
(1015, 'merveatila93@gmail.com', '$2y$10$WZeyoUf1ShMwsr30H4d0zu/0B2O/sA2w5NFfC6IX/v.95IJZLwhyy', 'merve', 'Ankara', 'Çankaya', 'Yenimahalle No:73', 'consumer', NULL, NULL),
(1017, 'erdematila07@gmail.com', '$2y$10$Mu0DMKF4BISRk0StpkrXKu1/O4Y9F/glkctCdrQbHNeeLrFwIR4Su', 'Erdem Atila', 'İzmir', 'Bayraklı', 'cumhuriyet mahallesi , akasya sokak , çevre yolu üzeri', 'consumer', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1018;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
