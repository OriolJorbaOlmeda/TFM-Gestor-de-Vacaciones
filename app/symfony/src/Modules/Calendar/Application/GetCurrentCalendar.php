<?php

namespace App\Modules\Calendar\Application;

use App\Entity\Calendar;
use App\Entity\Company;
use App\Modules\Calendar\Infrastucture\CalendarRepository;

class GetCurrentCalendar
{

    public function __construct(private CalendarRepository $calendarRepository){}

    public function getCurrentCalendar(Company $company): ?Calendar
    {
        return $this->calendarRepository->findCurrentCalendar($company);

    }

}