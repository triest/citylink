-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 25 2019 г., 00:47
-- Версия сервера: 5.6.41
-- Версия PHP: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `publishing`
--

-- --------------------------------------------------------

--
-- Структура таблицы `magazine`
--

CREATE TABLE `magazine` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `magazine`
--

INSERT INTO `magazine` (`id`, `name`) VALUES
(1, 'Мурзилка'),
(5, 'Крокодил');

-- --------------------------------------------------------

--
-- Структура таблицы `reader`
--

CREATE TABLE `reader` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date_of_birth` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `reader`
--

INSERT INTO `reader` (`id`, `name`, `date_of_birth`) VALUES
(1, 'Иванов', '1989-07-30'),
(2, 'Петров', '1909-07-25'),
(3, 'Сидоров', '1989-07-30'),
(4, 'Тест', '2019-07-25'),
(5, 'Харконнен', '2019-07-31'),
(6, 'Атрейдес', '1915-07-10'),
(7, 'выы', '1919-02-05'),
(8, 'ав', '2019-02-05');

-- --------------------------------------------------------

--
-- Структура таблицы `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(255) NOT NULL,
  `reader_id` int(255) DEFAULT NULL,
  `magazine_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `reader_id`, `magazine_id`) VALUES
(1, 6, 1),
(2, 2, 1),
(3, 3, 1),
(4, 3, 5);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `magazine`
--
ALTER TABLE `magazine`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `reader`
--
ALTER TABLE `reader`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reader_id` (`id`);

--
-- Индексы таблицы `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `magazine_id` (`magazine_id`),
  ADD KEY `reader_id` (`reader_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `magazine`
--
ALTER TABLE `magazine`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `reader`
--
ALTER TABLE `reader`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`magazine_id`) REFERENCES `magazine` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `subscriptions_ibfk_2` FOREIGN KEY (`reader_id`) REFERENCES `reader` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
