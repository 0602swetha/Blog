-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2025 at 09:29 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `user_id`, `title`, `subtitle`, `image`, `description`, `created_at`, `updated_at`, `status`) VALUES
(1, 1, 'Technology', 'AI-Powered Assistants with Human-Like Reasoning', 'tech 1.jpeg', 'AI is evolving beyond simple chatbots into more sophisticated digital assistants that can understand context, emotions, and complex reasoning. These AI models are now capable of handling tasks like coding, content creation, and even medical diagnoses.', '2025-03-26 10:10:03', '2025-04-10 11:07:49', 1),
(2, 1, 'Technology', 'Brain-Computer Interfaces (BCIs)', 'tech 2 brain.jpeg', 'Companies like Neuralink are developing BCIs that connect the human brain directly to computers, potentially allowing people to control devices with their thoughts and even help those with disabilities regain mobility and communication.', '2025-03-26 10:24:19', '2025-04-10 11:07:52', 1),
(3, 1, 'Technology', 'Flying Cars & Urban Air Mobility', 'tech3 fly car.jpeg', 'Companies like Joby Aviation and Hyundai are working on electric vertical takeoff and landing (eVTOL) vehicles, making flying taxis a reality. This could redefine urban transport and reduce congestion on the roads.', '2025-03-26 10:26:24', '2025-04-10 11:07:55', 1),
(4, 1, 'Technology', '6G Networks and Ultra-Fast Connectivity', '6g.jpeg', 'While 5G is still expanding, researchers are already working on 6G, which promises even faster speeds, lower latency, and better AI-driven communication, making smart cities and autonomous vehicles more efficient.', '2025-03-26 10:27:14', '2025-04-10 11:11:05', 1),
(5, 2, 'Food', 'The King of Flavors, Served on a Plate', 'briyani.jpeg', 'Biryani holds a special place in South Asian cuisine, often associated with festive occasions, celebrations, and communal gatherings. Its widespread popularity has led to its adaptation in various regions, each adding its unique touch to this beloved dish.', '2025-03-26 10:48:12', '2025-04-10 10:48:51', 1),
(7, 2, 'Food', 'Between the Bread: The Art and Evolution of the Sandwich', 'sandwich.jpeg', 'A sandwich is a versatile culinary creation that typically consists of various fillings—such as meats, cheeses, vegetables, or spreads—placed between two slices of bread. This simple yet ingenious concept has become a staple in diets worldwide, offering convenience and endless possibilities for customization.', '2025-03-26 10:50:19', '2025-04-10 11:07:40', 1),
(8, 2, 'Food', '\"Soft and Fluffy: The Delight of Homemade Potato Rolls', 'potatorole.jpeg', 'Potato rolls are soft, tender bread rolls that incorporate mashed potatoes into the dough, resulting in a moist and fluffy texture. The addition of potatoes not only enhances the softness but also imparts a subtle richness to the flavor. These rolls are a popular choice for holiday dinners and make excellent bases for sandwiches. Traditional recipes often include ingredients like eggs, sugar, butter, and yeast, combined with all-purpose flour and mashed potatoes to create the dough.', '2025-03-26 10:51:24', '2025-04-10 11:07:41', 1),
(9, 3, 'Mauritius', 'Tropical Paradise with Pristine Beaches and Rich Culture', 'place1.jpeg', 'Mauritius, an island nation in the Indian Ocean, is renowned for its crystal-clear waters, white sandy beaches, and vibrant coral reefs. Beyond its natural beauty, the island boasts a multicultural heritage, offering a blend of Creole, Indian, Chinese, and French influences evident in its cuisine, festivals, and architecture.', '2025-03-26 10:56:03', '2025-04-10 11:07:44', 1),
(10, 3, 'Bali, Indonesia', 'Island of Gods with Lush Landscapes and Spiritual Heritage', 'p2.jpeg', 'Bali captivates visitors with its terraced rice paddies, volcanic mountains, and serene beaches. The island is also a hub for spiritual exploration, featuring numerous temples, traditional dance performances, and yoga retreats. Its vibrant arts scene and warm hospitality make it a perennial favorite among travelers.', '2025-03-26 10:57:03', '2025-04-10 11:07:45', 1),
(11, 3, 'Maldives', 'Luxury Overwater Villas Amidst Turquoise Lagoons', 'download (1).jpeg', 'The Maldives are comprised of 1,190 Coral Islands each surrounded by crystal-blue lagoons and grouped in a double chain of 27 atolls and spread over roughly 90,000 square kilometers making the country one of the most unique destinations in the world. 192 of the Islands are inhabited and of these 105 are resorts.', '2025-03-26 11:04:33', '2025-04-10 11:07:46', 1),
(12, 3, 'Kathmandu, Nepal', 'Gateway to the Himalayas with Rich Cultural Heritage', 'p4.jpeg', 'Steeped in history, the rich cultural kaleidoscope of Nepal can clearly be seen in Kathmandu with streets lined with temples, stupas and palaces showcasing a distinct fusion of Hindu and Buddhist styles and architecture. Durbar Square, a UNESCO World Heritage Site, stands as a magnificent reminder of the Malla dynasty.', '2025-03-26 11:07:15', '2025-04-10 11:07:48', 1);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `blog_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `blog_id`, `user_id`, `parent_id`, `comment`, `created_at`, `status`) VALUES
(11, 4, 3, 0, 'nyc post', '2025-03-26 11:07:48', 1),
(13, 4, 1, 11, 'tq', '2025-03-26 11:09:01', 1),
(15, 10, 1, 0, 'nyc place', '2025-03-26 11:09:39', 1),
(18, 10, 3, 15, 'tq', '2025-03-26 11:26:22', 1),
(22, 10, 1, 0, 'good', '2025-03-31 07:05:16', 1),
(23, 10, 1, 22, 'tq', '2025-03-31 08:13:46', 1),
(24, 7, 1, 0, 'good ', '2025-04-08 05:03:10', 1),
(25, 8, 1, 0, 'supr', '2025-04-08 05:04:08', 1),
(26, 5, 4, 0, 'good', '2025-04-10 05:37:00', 1),
(27, 12, 4, 0, 'good', '2025-04-10 07:00:49', 1),
(28, 12, 4, 0, 'nyc', '2025-04-10 07:02:48', 1),
(29, 12, 3, 0, 'supr', '2025-04-10 07:07:37', 1),
(30, 12, 1, 27, 'tq', '2025-05-09 11:14:45', 0),
(31, 5, 1, 26, 'tq', '2025-05-09 11:15:31', 0),
(32, 12, 1, 28, 'tq', '2025-05-09 11:16:38', 0),
(33, 10, 1, 15, 'tq', '2025-05-09 11:21:41', 0),
(34, 8, 1, 25, 'tq', '2025-05-09 11:21:53', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user','reader') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`, `status`) VALUES
(1, 'swetha', '$2y$10$iqptymqps/j426e0wkE46uv1LHqNBvNZWJkIk8olILtN96n5qT9vK', 'user', '2025-03-22 11:38:02', 1),
(2, 'kavinkumar', '$2y$10$UoR./d4eKy/O7EHurav7YOLOBFSnUeluEmMhCdUuaFondKi8sIeJy', 'user', '2025-03-22 11:49:29', 1),
(3, 'Sakthi', '$2y$10$KncDcjkUZjxzc7kqrPQNOer31QXTmNNETV4/t1I9n37IUw.lDnJG2', 'user', '2025-03-24 04:44:51', 1),
(4, 'Jeeva_Jeeva', '$2y$10$OHF/IVjLTfvynKKRVYfnduL.Oxy.Jv0VLbyetYWjdCF8jdi3HUZ6m', 'user', '2025-04-10 05:34:08', 1),
(5, 'Admin_Admin', '$2y$10$dWXePx7o6nSNVwidOsv6Iu6zqLBKIFGaFHxRG6iW5e00VrEmiJS4W', 'admin', '2025-04-10 06:57:09', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_id` (`blog_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blogs`
--
ALTER TABLE `blogs`
  ADD CONSTRAINT `blogs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
