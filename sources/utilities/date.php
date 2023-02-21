<?php
date_default_timezone_set($timezone);
$numberMonthObject = [
    1 => "Januari",
    2 => "Februari",
    3 => "Maret",
    4 => "April",
    5 => "Mei",
    6 => "Juni",
    7 => "Juli",
    8 => "Agustus",
    9 => "September",
    10 => "Oktober",
    11 => "November",
    12 => "Desember"
];

function currentYear()
{
    return date("Y");
};

function dateInterval($startDate, $endDate)
{
    $time_interval = (new DateTime($endDate))->diff(new DateTime($startDate));

    if (intval($time_interval->format('%y')) != 0) {
        return $time_interval->format('%y Tahun Yang Lalu');
    } else if (intval($time_interval->format('%m')) != 0) {
        return $time_interval->format('%m Bulan Yang Lalu');
    } else if (intval($time_interval->format('%d')) != 0) {
        return $time_interval->format('%d Hari Yang Lalu');
    } else if (intval($time_interval->format('%h')) != 0) {
        return $time_interval->format('%h Jam Yang Lalu');
    } else if (intval($time_interval->format('%i')) != 0) {
        return $time_interval->format('%i Menit Yang Lalu');
    } else if (intval($time_interval->format('%s')) != 0) {
        return $time_interval->format('%s Detik Yang Lalu');
    } else {
        return "Baru Saja";
    };
};

function numberToMonth($number)
{
    global $numberMonthObject;
    return $numberMonthObject[$number];
};
