<?php

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
 *     принимать она будет один параметр: временной интервал (строка в формате чч:мм-чч:мм)
 *     возвращать boolean
 *
 *
 * Вторая функция должна проверять "наложение интервалов" при попытке добавить новый интервал в список существующих
 *     принимать она будет один параметр: временной интервал (строка в формате чч:мм-чч:мм)
 *  возвращать boolean
 *
 *  "наложение интервалов" - это когда в промежутке между началом и окончанием одного интервала,
 *   встречается начало, окончание или то и другое одновременно, другого интервала
 *
 *  пример:
 *
 *  есть интервалы
 *      "10:00-14:00"
 *      "16:00-20:00"
 *
 *  пытаемся добавить еще один интервал
 *      "09:00-11:00" => произошло наложение
 *      "11:00-13:00" => произошло наложение
 *      "14:00-16:00" => наложения нет
 *      "14:00-17:00" => произошло наложение
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

/**
 *  Проверка интервала на валидность
 *
 *  @param string $interval Временной интервал в виде строки в формате "чч:мм-чч:мм"
 *
 *  @return bool
 */
function checkInterval(string $interval): bool
{
    if (!preg_match("/[0-2][0-9]:[0-5][0-9]-[0-2][0-9]:[0-5][0-9]/", $interval))
        return false;

    [$start, $end] = explode('-', $interval);
    [$startHours, $startMinutes] = array_map('intval', explode(":", $start));
    [$endHours, $endMinutes] = array_map('intval', explode(":", $end));

    if ($startHours < 0 || $startHours > 23 || $startMinutes < 0 || $startMinutes > 59)
        return false;

    if ($endHours < 0 || $endHours > 23 || $endMinutes < 0 || $endMinutes > 59)
        return false;

    if ($startHours > $endHours) 
        return false;

    if ($startHours === $endHours && $startMinutes > $endMinutes)
        return false;

    return true;
}

/**
 *  Проверка интервала на пересечение с другим интервалом
 *
 *  @param string $interval Временной интервал в виде строки в формате "чч:мм-чч:мм"
 *
 *  @return bool
 */
function checkOverlap(string $interval): bool 
{
    $existIntervals = [ "10:00-14:00", "16:00-20:00" ];

    foreach ($existIntervals as $existInterval) {
        [$existIntervalStart, $existIntervalEnd] = array_map('strtotime', explode('-', $existInterval);
        [$addedIntervalStart, $addedIntervalEnd] = array_map('strtotime', explode('-', $interval);

        if ($existIntervalStart < $addedIntervalStart && $addedIntervalStart < $existIntervalEnd)
            return true;

        if ($existIntervalStart < $addedIntervalEnd && $addedIntervalEnd < $existIntervalEnd)
            return true;
    }

    return false;
}

/*
foreach ($list as $interval) {
    var_dump(checkOverlap($interval));
}*/

