<?php

namespace App\Modules\Calendar\Application;

use App\Entity\Calendar;
use App\Modules\Calendar\Infrastucture\CalendarRepository;
use Symfony\Component\Security\Core\Security;

class CreateCalendar
{

    public function __construct(
        private CalendarRepository $calendarRepository,
        private Security $security
    ) {}

    public function createCalendar(Calendar $calendar)
    {
        $calendar->setCompany($this->security->getUser()->getDepartment()->getCompany());

        $this->calendarRepository->add($calendar);
    }
}