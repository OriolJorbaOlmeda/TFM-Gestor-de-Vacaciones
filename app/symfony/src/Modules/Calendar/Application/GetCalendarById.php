<?php

namespace App\Modules\Calendar\Application;

use App\Entity\Calendar;
use App\Modules\Calendar\Infrastucture\CalendarRepository;

class GetCalendarById
{
    public function __construct(private CalendarRepository $calendarRepository){}

    public function getCalendarById(string $calendarId): Calendar
    {
        return $this->calendarRepository->findOneBy(['id' => $calendarId]);
    }

}