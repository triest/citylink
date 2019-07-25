﻿<?php

/**
 * @charset UTF-8
 *
 * Задание 2. Работа с массивами и строками.
 *
 * Есть список временных интервалов (интервалы записаны в формате чч:мм-чч:мм).
 *
 * Необходимо написать две функции:
 *
 *
 * Первая функция должна проверять временной интервал на валидность
 *    принимать она будет один параметр: временной интервал (строка в формате
 *    чч:мм-чч:мм) возвращать boolean
 *
 *
 * Вторая функция должна проверять "наложение интервалов" при попытке добавить
 * новый интервал в список существующих принимать она будет один параметр:
 * временной интервал (строка в формате чч:мм-чч:мм) возвращать boolean
 *
 *  "наложение интервалов" - это когда в промежутке между началом и окончанием
 *  одного интервала, встречается начало, окончание или то и другое
 *  одновременно, другого интервала
 *
 *  пример:
 *
 *  есть интервалы
 *    "10:00-14:00"
 *    "16:00-20:00"
 *
 *  пытаемся добавить еще один интервал
 *    "09:00-11:00" => произошло наложение
 *    "11:00-13:00" => произошло наложение
 *    "14:00-16:00" => наложения нет
 *    "14:00-17:00" => произошло наложение
 */

# Можно использовать список:

$list = array(
    '09:00-11:00',
    '11:00-13:00',
    '15:00-16:00',
    '17:00-20:00',
    '20:30-21:30',
    '21:30-22:30',
);

?>