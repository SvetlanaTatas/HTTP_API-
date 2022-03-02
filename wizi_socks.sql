-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Хост: 10.0.0.231:3311
-- Время создания: Мар 02 2022 г., 15:43
-- Версия сервера: 10.3.30-MariaDB-log
-- Версия PHP: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `wizi_socks`
--

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id_category` int(11) NOT NULL,
  `name_category` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id_category`, `name_category`) VALUES
(1, 'Женские'),
(2, 'Мужские'),
(3, 'Детские');

-- --------------------------------------------------------

--
-- Структура таблицы `options`
--

CREATE TABLE `options` (
  `id_option` int(11) NOT NULL,
  `name_option` text NOT NULL,
  `code` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `options`
--

INSERT INTO `options` (`id_option`, `name_option`, `code`) VALUES
(3, 'процент хлопка', 'cottonPart'),
(4, 'цвет', 'color'),
(5, 'размер', 'size');

-- --------------------------------------------------------

--
-- Структура таблицы `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `product`
--

INSERT INTO `product` (`id`, `name`, `quantity`) VALUES
(1, 'Женские носки с принтом', 34),
(4, 'Женские спортивные носки', 52),
(5, 'Женские кружевные носки', 15),
(6, 'Классические мужские носки', 125),
(7, 'Мужские носки с оленями', 15),
(8, 'Тренировочные мужские носки', 20),
(9, 'Детские носки с героями', 117),
(10, 'Детские носки теплые', 24),
(11, 'Детские носки для физкультуры', 48),
(98, 'детские носки с мордочкой', 102),
(99, 'шерстяные носки для детей', 14);

-- --------------------------------------------------------

--
-- Структура таблицы `product_category`
--

CREATE TABLE `product_category` (
  `id_pc` int(11) NOT NULL,
  `product` int(11) NOT NULL,
  `category` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `product_category`
--

INSERT INTO `product_category` (`id_pc`, `product`, `category`) VALUES
(3, 11, 3),
(4, 9, 3),
(5, 10, 3),
(6, 5, 1),
(7, 1, 1),
(8, 4, 1),
(9, 6, 2),
(10, 7, 2),
(11, 8, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `product_option_value`
--

CREATE TABLE `product_option_value` (
  `id` int(11) NOT NULL,
  `product` int(11) NOT NULL,
  `options` int(11) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `product_option_value`
--

INSERT INTO `product_option_value` (`id`, `product`, `options`, `value`) VALUES
(15, 11, 3, '80'),
(16, 11, 5, '15'),
(17, 11, 4, 'синие'),
(18, 9, 3, '50'),
(19, 9, 5, '20'),
(20, 9, 4, 'разноцветные'),
(21, 10, 3, '5'),
(22, 10, 5, '25'),
(23, 10, 4, 'черные'),
(24, 5, 3, '5'),
(25, 5, 5, '37'),
(26, 5, 4, 'белые'),
(27, 1, 3, '50'),
(28, 1, 5, '37'),
(29, 1, 4, 'разноцветные'),
(30, 4, 3, '80'),
(31, 4, 5, '37'),
(32, 4, 4, 'голубые'),
(33, 6, 3, '85'),
(34, 6, 5, '43'),
(35, 6, 4, 'черные'),
(36, 7, 3, '75'),
(37, 7, 5, '43'),
(38, 7, 4, 'коричневые'),
(39, 8, 3, '90'),
(40, 8, 5, '45'),
(41, 8, 4, 'синие'),
(45, 98, 3, '45'),
(46, 98, 5, '30'),
(47, 98, 4, 'Красные'),
(48, 99, 3, '2'),
(49, 99, 5, '28'),
(50, 99, 4, 'розовые');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_category`);

--
-- Индексы таблицы `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id_option`);

--
-- Индексы таблицы `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`id_pc`),
  ADD KEY `category` (`category`),
  ADD KEY `product` (`product`);

--
-- Индексы таблицы `product_option_value`
--
ALTER TABLE `product_option_value`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product` (`product`) USING BTREE,
  ADD KEY `options` (`options`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `options`
--
ALTER TABLE `options`
  MODIFY `id_option` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT для таблицы `product_category`
--
ALTER TABLE `product_category`
  MODIFY `id_pc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `product_option_value`
--
ALTER TABLE `product_option_value`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `product_category`
--
ALTER TABLE `product_category`
  ADD CONSTRAINT `product_category_ibfk_1` FOREIGN KEY (`category`) REFERENCES `categories` (`id_category`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_category_ibfk_2` FOREIGN KEY (`product`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `product_option_value`
--
ALTER TABLE `product_option_value`
  ADD CONSTRAINT `product_option_value_ibfk_1` FOREIGN KEY (`product`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_option_value_ibfk_2` FOREIGN KEY (`options`) REFERENCES `options` (`id_option`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
