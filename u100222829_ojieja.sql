-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 25 2025 г., 22:27
-- Версия сервера: 10.11.10-MariaDB
-- Версия PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `u100222829_ojieja`
--

-- --------------------------------------------------------

--
-- Структура таблицы `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(2, 'Oleh', '$2y$10$vY5JVIbLmM9nIYL9Hey3FOqIcxZHWOcIN5QVmbkPYKWJZqJfG8xk6', '2025-03-16 13:38:38'),
(3, 'user', '$2y$10$bzTMjEwVbSK5APZZpnmka.K6TY1w0/r1TJjaV7Ffq/2auIeD27l5u', '2025-03-16 13:45:12'),
(4, 'user2', '$2y$10$E2/v.f3j2CjpbXsXc1QqxORLldtycvLtfQojKQGlFuXZGnijuUA1q', '2025-03-17 18:50:52');

-- --------------------------------------------------------

--
-- Структура таблицы `cars_rental`
--

CREATE TABLE `cars_rental` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL COMMENT 'Название автомобиля',
  `description` text NOT NULL COMMENT 'Описание автомобиля',
  `version` varchar(50) DEFAULT NULL COMMENT 'Версия автомобиля (необязательная)',
  `rental_price` decimal(10,2) NOT NULL,
  `deposit_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp() COMMENT 'Дата добавления',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Дата последнего обновления',
  `images` text NOT NULL DEFAULT '[]',
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `mileage` varchar(255) DEFAULT NULL,
  `transmission` varchar(255) DEFAULT NULL,
  `fuel` varchar(255) DEFAULT NULL,
  `consumption` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `cars_rental`
--

INSERT INTO `cars_rental` (`id`, `name`, `description`, `version`, `rental_price`, `deposit_price`, `created_at`, `updated_at`, `images`, `is_available`, `mileage`, `transmission`, `fuel`, `consumption`) VALUES
(1, 'Kia Rio', 'Description of Kia Rio', '2021', 1500.00, 1500.00, '2025-03-16 01:57:23', '2025-03-18 22:23:35', '[\"..\\/uploads_img\\/67d6eeb74d975_Flux_Dev_Create_a_cyberpunkthemed_banner_in_a_futuristic_hight_2.jpeg\",\"..\\/uploads_img\\/67d6eec785457_Volkswagen-Polo-Volkswagen-Polo-1-2-United.jpg\",\"..\\/uploads_img\\/67d6eee67e6fa_Flux_Dev_Create_a_cyberpunkthemed_banner_in_a_futuristic_hight_1.jpeg\",\"..\\/uploads_img\\/67d6eee67eb28_Flux_Dev_Create_a_cyberpunkthemed_banner_in_a_futuristic_hight_2.jpeg\",\"..\\/uploads_img\\/67d6eee67eeb2_olehbilenkyi.jpeg\",\"..\\/uploads_img\\/67d6eee67f1b0_images.jpg\",\"..\\/uploads_img\\/67d6eee67f2b3_Hyundai_Solaris_(HC).jpg\",\"..\\/uploads_img\\/67d6eee67f978_Volkswagen-Polo-Volkswagen-Polo-1-2-United.jpg\",\"..\\/uploads_img\\/67d6eee67fb1c_images (1).jpg\",\"..\\/uploads_img\\/67d6eee67fc10_62ba60d0_medium.webp\",\"..\\/uploads_img\\/67d6eee67fdb8_images.jpg\",\"..\\/uploads_img\\/67d6eee67fea4_Hyundai_Solaris_(HC).jpg\",\"..\\/uploads_img\\/67d6eee6804fb_Volkswagen-Polo-Volkswagen-Polo-1-2-United.jpg\",\"..\\/uploads_img\\/67d6eee68069e_images (1).jpg\",\"..\\/uploads_img\\/67d6eee680794_62ba60d0_medium.webp\",\"..\\/uploads_img\\/67d6eee680937_Flux_Dev_Create_a_cyberpunkthemed_banner_in_a_futuristic_hight_1.jpeg\",\"..\\/uploads_img\\/67d6eee680c78_Flux_Dev_Create_a_cyberpunkthemed_banner_in_a_futuristic_hight_2.jpeg\",\"..\\/uploads_img\\/67d6eee680ff3_olehbilenkyi.jpeg\",\"..\\/uploads_img\\/67d6eee68126f_Hyundai_Solaris_(HC).jpg\",\"..\\/uploads_img\\/67d6eee6818b7_Volkswagen-Polo-Volkswagen-Polo-1-2-United.jpg\",\"..\\/uploads_img\\/67d6eee681a39_images (1).jpg\",\"..\\/uploads_img\\/67d6eee681b26_62ba60d0_medium.webp\"]', 1, '2', '2', '2', '2'),
(2, 'Hyundai Solaris', 'Description of Hyundai Solaris', '2020', 1600.00, 1600.00, '2025-03-16 01:57:23', '2025-03-18 22:26:45', '[\"..\\/uploads_img\\/67d6eec214d14_Volkswagen-Polo-Volkswagen-Polo-1-2-United.jpg\",\"..\\/uploads_img\\/67d6eec214ef6_images (1).jpg\",\"..\\/uploads_img\\/67d6eec214fd0_Flux_Dev_Create_a_cyberpunkthemed_banner_in_a_futuristic_hight_1.jpeg\",\"..\\/uploads_img\\/67d6eec2152f2_Flux_Dev_Create_a_cyberpunkthemed_banner_in_a_futuristic_hight_2.jpeg\",\"..\\/uploads_img\\/67d6eec215638_olehbilenkyi.jpeg\",\"..\\/uploads_img\\/67d6eec2158d7_images.jpg\",\"..\\/uploads_img\\/67d6eec2159a8_Hyundai_Solaris_(HC).jpg\"]', 1, '1', '1', '1', '1'),
(3, 'Volkswagen Polo', 'Description of Volkswagen Polo', '2019', 1700.00, 1700.00, '2025-03-16 01:57:23', '2025-03-18 22:31:53', '[\"..\\/uploads_img\\/67d6fde892661_images.jpg\"]', 1, '1', '2', '3', '4'),
(4, 'Kia Rio', 'Description of Kia Rio', '2021', 1500.00, 1500.00, '2025-03-16 02:20:55', '2025-03-18 22:32:14', '[\"..\\/uploads_img\\/67d6fdee2aa14_images (1).jpg\"]', 1, '5', '6', '7', '8'),
(5, 'Hyundai Solaris', 'Description of Hyundai Solaris', '2020', 1600.00, 1600.00, '2025-03-16 02:20:55', '2025-03-18 22:32:33', '[\"..\\/uploads_img\\/67d6fdf44169a_62ba60d0_medium.webp\"]', 1, '3', '4', '5', '6'),
(6, 'Volkswagen Polo', 'Description of Volkswagen Polo', '2019', 1700.00, 1700.00, '2025-03-16 02:20:55', '2025-03-18 22:32:47', '[\"..\\/uploads_img\\/67d6fe13dbe2f_images.jpg\"]', 1, '1', '1', '1', '1'),
(7, 'Toyota Corolla Hybrid', 'Экономичный автомобиль с гибридным двигателем', '1.8 Hybrid CVT', 1200.00, 5000.00, '2025-03-16 16:20:52', '2025-03-18 22:32:58', '[\"..\\/uploads_img\\/67d6fe1e947ca_Hyundai_Solaris_(HC).jpg\"]', 1, '1', '1', '1', '1');

-- --------------------------------------------------------

--
-- Структура таблицы `partners`
--

CREATE TABLE `partners` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL COMMENT 'Имя партнера',
  `logo` varchar(255) DEFAULT NULL COMMENT 'Ссылка на логотип партнера',
  `created_at` timestamp NULL DEFAULT current_timestamp() COMMENT 'Дата добавления',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Дата последнего обновления'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `partners`
--

INSERT INTO `partners` (`id`, `name`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'Partner 1', 'logo1.jpg', '2025-03-16 01:58:18', '2025-03-16 01:58:18'),
(2, 'Partner 2', 'logo2.jpg', '2025-03-16 01:58:18', '2025-03-16 01:58:18'),
(3, 'Partner 3', 'logo3.jpg', '2025-03-16 01:58:18', '2025-03-16 01:58:18');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Индексы таблицы `cars_rental`
--
ALTER TABLE `cars_rental`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `cars_rental`
--
ALTER TABLE `cars_rental`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `partners`
--
ALTER TABLE `partners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
