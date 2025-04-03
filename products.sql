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
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `market_id` int(11) NOT NULL,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `stock` int(11) NOT NULL,
  `normal_price` decimal(10,2) NOT NULL,
  `discounted_price` decimal(10,2) NOT NULL,
  `expiration_date` date NOT NULL,
  `image` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `market_id`, `title`, `stock`, `normal_price`, `discounted_price`, `expiration_date`, `image`) VALUES
(1, 1005, 'Milka Oreo', 299, '65.00', '43.00', '2025-02-28', 'b881764c2a0ca975a89443bb4cfdd3fcd9b3c749.jpeg'),
(2, 1005, 'Toblerone', 30, '150.00', '139.00', '2025-12-31', 'e8dcd9e55b186efa1c5f51b95097a250041aab86.jpeg'),
(3, 1005, 'Cappy Meyve Suyu', 120, '39.99', '35.99', '2024-05-12', 'd888713e5e13239b95eb2ab073d656a052959062.jpeg'),
(4, 1005, 'Domestos Temizleyici', 100, '57.25', '50.25', '2026-07-10', '74a25994247163b23c232b5597c23c37217cb0c7.jpeg'),
(5, 1005, 'Filiz Makarna', 200, '22.75', '20.75', '2024-06-22', 'd9ba7ccf4a56713103c710cb76b0a9c59409d7fd.jpeg'),
(6, 1005, 'Lays Cips', 150, '15.00', '12.00', '2024-09-30', '38505b70af758e96f6417c9c1d7d2a5569fd03af.jpeg'),
(7, 1005, 'Mis Sut', 80, '35.50', '31.50', '2024-03-12', '5ff02f4015cdf3ce106163e8dc5f4ddf4452f1bf.jpeg'),
(8, 1005, 'Selva Un', 97, '10.00', '5.00', '2024-08-17', 'e0d195f4190d4b2dec3daa431effa763976fe32d.jpeg'),
(9, 1005, 'Komili Ayçiçek Yağı', 90, '260.90', '250.90', '2023-04-30', 'f5f5b077089874ee6c998ffdf21143119a7bdd95.jpg'),
(10, 1005, 'Tat Domates Salcası', 50, '15.00', '10.00', '2025-09-30', '1b8950bfc04636dbd004fc030fb4a6febe3eb94c.jpeg'),
(17, 1014, 'minecraft figür', 6, '1000.00', '1000.00', '2024-10-16', '9bdfb2db42fc1bf7f862fe923d1c790d8ac167ff.png'),
(18, 1006, 'Uno Tost Ekmeği', 150, '30.00', '25.00', '2024-05-31', 'ef5696f40fa836539baf545f64d3075ea5f12baf.jpg'),
(19, 1006, 'Cornetto Clasic', 97, '40.00', '35.00', '2024-07-02', '651315b2d81b94d6b4cb63435bca4ebe42a684ec.jpg'),
(20, 1006, 'Çokça Mayonez', 250, '65.90', '55.90', '2025-09-05', 'd75b61ee4d5a458bfae9b3043e995a38eb704465.jpg'),
(21, 1006, 'Çokça Ketçap', 195, '60.50', '50.50', '2025-04-01', '8629a67d9191b090a5e7ae9143a0a1a9a4c6be83.jpg'),
(22, 1006, 'Coca Cola ', 350, '15.25', '10.25', '2025-06-13', '2cd59bc20a5bc7d8b3283bee2fda32bc934469c5.jpg'),
(23, 1006, 'Sprite', 242, '65.00', '50.00', '2025-10-31', '4a22a7981e4b42aed447f75594bda8a2675fe498.jpg'),
(24, 1006, 'Eti Puf', 95, '7.50', '5.00', '2025-02-02', 'fb4d5a65c79f319ee85d1a311815816d8a6e2e60.jpg'),
(25, 1006, 'Banvit Tavuk', 50, '100.50', '85.50', '2024-05-29', 'adb1e3252a7accf10ade9e402ee9167d45b7ccee.png'),
(26, 1006, 'Torku Kaşar Peyniri', 165, '249.90', '199.90', '2024-06-20', '3c2a40d34ecc945f2da41ee3b9bc2efda3b1a32e.jpg'),
(27, 1006, 'Ekici Tulum Peyniri', 346, '99.50', '85.00', '2024-05-23', '66f3e02ce56393cfd4feabb446e1c314c35f306c.jpg'),
(28, 1007, 'Doritos Cips', 450, '37.95', '31.50', '2025-11-07', '4f8d11213e08e2cdfb398e5cf010df8c75016d6e.jpg'),
(29, 1007, 'Fuse Tea Limon', 275, '30.00', '25.00', '2025-01-17', '4203843deea0662df1db036cd54223524d2a3038.jpg'),
(30, 1007, 'By İzzet Antep Fıstığı', 35, '199.95', '159.95', '2024-06-19', 'ecb8a74d58a084aaa29f83eebe4944ef147ee66c.jpg'),
(31, 1007, 'Bahçıvan Süzme Peynir', 180, '87.90', '69.90', '2024-07-06', '84a8d44648c2405102f75161aec9da72ca0340a6.jpg'),
(32, 1007, 'Tadım Çekirdek', 275, '40.00', '30.00', '2024-05-30', '2eeab89f3c77a8134e920d64b450043c72ca0223.jpg'),
(33, 1007, 'Lipton Ice Tea', 350, '35.00', '31.50', '2024-08-29', 'bebfa01b6bb5ad8f83c7d8bde9fc987c47fa3f05.jpg'),
(34, 1007, 'Migros Yoğurt', 120, '90.00', '80.00', '2024-06-18', '14460b31a643d30560eeffbab9599453ed10e634.jpg'),
(35, 1007, 'Ülker Çubuk Kraker', 400, '4.75', '2.50', '2024-11-13', '6f8518f0bdfb98455099458870eb5467186fef07.jpg'),
(36, 1007, 'Felix Kedi Maması', 75, '100.00', '91.95', '2024-07-03', '28f1763ed7ebb5a79d9b88f66391b9e1dc87f625.jpg'),
(37, 1007, 'Master Potato Cips', 85, '55.00', '50.75', '2025-09-04', '5aaa59633b1954c4b04bd251e627c1b3d91d4802.jpg'),
(38, 1008, 'Mis Beyaz Peynir', 140, '63.50', '55.50', '2024-03-08', '844f9d5f5b55e930e0c55e7c19d4eff1bbc0b3ee.png'),
(39, 1008, 'Activia Yoğurt', 60, '55.00', '44.00', '2024-06-15', '1316ae00bcf2fa5893382b6aeb57d4eb33ed0358.png'),
(40, 1008, 'Nescafe Gold', 185, '119.90', '109.90', '2026-05-13', '543d7b5f19ab8e5f5c1752146a3075ad4ecaceaf.png'),
(41, 1008, 'Bisto Un Kurabiyesi', 37, '44.00', '41.50', '2024-05-22', '18015f894ffd864c3a093385b41b96e673a20500.png'),
(42, 1008, 'Amigo Tuzlu Fıstık', 285, '34.50', '27.90', '2024-12-23', '5eaab3a5ec698e983d83cb4cf34998349ae7248b.png'),
(43, 1008, 'Ülker Bebe Bisküvisi', 220, '21.50', '16.50', '2025-03-11', 'e648edbe4d2b625cd944155adf9b2aabee9c28a9.png'),
(44, 1008, 'Eti Browni Gold', 150, '8.00', '5.95', '2025-04-08', '3d9b47eee88adef25a3876cecee1d3fffeffe92e.png'),
(45, 1008, 'İçim Meyve Suyu', 390, '39.90', '25.00', '2025-02-11', '635c3cf950edda60c1f2c3eed5daa6d5c13cc549.png'),
(47, 1008, 'Lezita Piliç Köfte', 75, '50.00', '42.50', '2024-06-05', 'f5702c29517484ecfa1aa97200beb0f8b03fd4bd.png'),
(48, 1009, 'Serel Helva', 200, '54.50', '47.50', '2024-06-01', '49bb27f8cf044aaf3a80e4096c02a2f0bd4da96d.jpg'),
(49, 1009, 'Mutfağım Mantı', 155, '23.50', '19.50', '2025-02-16', 'a994e459208238b0b224a52680c5e604fcbbac73.jpg'),
(50, 1009, 'Superfresh Patates Kızartması', 205, '100.00', '75.00', '2025-03-18', 'eb633bc92a34329157d617b998660794bc0e3775.jpg'),
(51, 1009, 'Eti Canga', 230, '10.50', '7.25', '2024-12-25', 'fcd177e64ee1ff33d8f899d94c53896844e38167.png'),
(52, 1009, 'Mezze Marin Uskumru', 65, '45.50', '36.50', '2024-10-18', '9b8030c71c742262fff1430fcd2368892d583f72.png'),
(53, 1009, 'Danet Sucuk', 779, '750.00', '150.00', '2024-08-21', '043dcb434e9ea12ef920f6c0eeecabc43a6d62df.jpg'),
(54, 1009, 'Fıçı Salatalık Turşusu', 400, '27.50', '22.50', '2025-02-09', '60c336c74381b590fe9c43784cb30594e98a4e8e.jpg'),
(55, 1009, 'Bol Bol Acı Sos', 295, '25.25', '21.50', '2025-03-01', '6a87ca66ed1a8c7df2df95b7834bbbb1992d4c04.png'),
(56, 1009, 'Fanta', 40, '31.75', '25.75', '2025-05-23', '88274fe0f6ecdb090ea2e3bd442f4c5cb5253290.jpg'),
(57, 1009, 'Connex Lavaş', 85, '25.00', '20.00', '2024-05-30', '8716bc528029c7d4bb94aca3d804a69211936193.jpg'),
(61, 1001, 'Milka Oreo', 120, '65.00', '55.00', '2023-11-11', '70eb27412a3d770020b801ac2b3fc8b30d8ecbf9.jpeg'),
(62, 1001, 'Milka Choco', 97, '55.00', '35.00', '2024-11-11', 'dfed62eafa4e89077288c11273d86da2cd3e26d2.jpeg'),
(65, 1001, 'Uno Scandæle', 20, '450.00', '50.00', '2024-08-31', '82d8f2d605bbdded4a03d17a7af3132b3b09bc09.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
