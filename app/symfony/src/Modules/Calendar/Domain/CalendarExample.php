<?php

use App\Entity\Calendar;

class CalendarExample
{
    public static function random(): Calendar
    {
        $calendar = new Calendar();
        $calendar->setYear('2022');
        $calendar->setInitialDate(new DateTime('2023-01-01'));
        $calendar->setFinalDate(new DateTime('2023-12-31'));

        return $calendar;
    }
}