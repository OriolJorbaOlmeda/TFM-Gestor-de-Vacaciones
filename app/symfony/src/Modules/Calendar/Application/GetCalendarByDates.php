<?php

namespace App\Modules\Calendar\Application;

use App\Entity\Calendar;
use App\Modules\Calendar\Infrastucture\CalendarRepository;

class GetCalendarByDates
{
    public function __construct(private CalendarRepository $calendarRepository){}

    public function getCalendarByDates($initialDate, $finalDate): ?Calendar
    {
        return $this->calendarRepository->findCalendarByDates($initialDate, $finalDate);
    }
}