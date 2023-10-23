-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Cze 15, 2023 at 09:34 PM
-- Wersja serwera: 10.4.28-MariaDB
-- Wersja PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eventsdb`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` smallint(6) NOT NULL,
  `price` decimal(6,2) NOT NULL,
  `state` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `cart_event`
--

CREATE TABLE `cart_event` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `amount` smallint(6) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(300) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `location_id`, `name`, `description`, `price`, `image`, `date`) VALUES
(1, 1, 'Opener Festival', 'Największy i najbardziej zróżnicowany festiwal w Polsce.', 479.00, 'img/opener.jpg', '2023-06-28'),
(2, 2, 'SBM FFestival', 'Siódma edycja festiwalu hip-hopowego organizowanego przez wytwórnię SBM.', 199.00, 'img/sbm.jpg', '2023-08-23'),
(3, 3, 'Sunrise Festival', 'Największy festiwal muzyki elektronicznej w Polsce.', 299.00, 'img/sunrise.jpg', '2023-07-21'),
(4, 4, 'Harry Styles', 'Love On Tour to 19 nowo ogłoszonych koncertów w Europie, w tym w Polsce.', 495.00, 'img/styles.jpg', '2023-07-02'),
(5, 4, 'The Weeknd', 'Kanadyjski muzyk i producent zagra 9 sierpnia na Stadionie Narodowym.', 299.00, 'img/weeknd.jpg', '2023-08-09'),
(6, 4, 'Beyoncé', 'Beyoncé powraca na światową scenę w ramach Renaissance World Tour', 161.00, 'img/beyonce.jpg', '2023-06-27');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`) VALUES
(1, 'Lotnisko Gdynia-Kosakowo'),
(2, 'Lotnisko Bemowo, Warszawa'),
(3, 'Lotnisko Kołobrzeg Podczele'),
(4, 'PGE Narodowy, Warszawa');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `login` varchar(250) NOT NULL,
  `name` varchar(50) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `password` varchar(250) NOT NULL,
  `uuid` varchar(250) NOT NULL,
  `state` smallint(6) NOT NULL,
  `type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `login`, `name`, `last_name`, `password`, `uuid`, `state`, `type_id`) VALUES
(2, 'Admin', 'Admin', 'Admin', '$2y$10$w7CrdM3sQ6cg5NlZAdWktOeAhlpACf9Y1oWfv/bM87zStefwT3IeC', '', 200, 1),
(3, 'Moderator', 'Moderator', 'Moderator', '$2y$10$39LQ79ifEfy6I4G64i8gkOXfYwqLn8G2vu9G9OZkIWEaGzkIFXZLq', '', 200, 2),
(5, 'User', 'User', 'User', '$2y$10$ayv9ZpslVZ2j5qauVKV2TuMev1S6qxrrCE2hFX6LvwxsTjEFtMHoy', '', 200, 3);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_permissions`
--

CREATE TABLE `user_permissions` (
  `id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user_permissions`
--

INSERT INTO `user_permissions` (`id`, `type`) VALUES
(1, 'admin'),
(2, 'moderator'),
(3, 'user');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `cart_event`
--
ALTER TABLE `cart_event`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_id` (`cart_id`,`event_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indeksy dla tabeli `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indeksy dla tabeli `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_id` (`type_id`),
  ADD KEY `type_id` (`type_id`);

--
-- Indeksy dla tabeli `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `cart_event`
--
ALTER TABLE `cart_event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=259;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `user_permissions`
--
ALTER TABLE `user_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_event`
--
ALTER TABLE `cart_event`
  ADD CONSTRAINT `cart_event_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_event_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `user_permissions` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
