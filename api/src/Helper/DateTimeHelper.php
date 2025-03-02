<?php

namespace App\Helper;

class DateTimeHelper
{
    public static function dateTimeFromStrings(string $date, string $time) : \DateTime
    {
        $dateTimeString = new \DateTime($date);
        [$hours, $minutes] = explode(':', $time);

        return $dateTimeString->setTime((int) $hours, (int) $minutes);
    }
}