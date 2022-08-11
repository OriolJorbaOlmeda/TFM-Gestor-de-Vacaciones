<?php

namespace App\Modules\Festive\Application;

use App\Modules\Festive\Infrastucture\FestiveRepository;
use App\Entity\Festive;
use App\Entity\Calendar;

class CreateFestive
{
    public function __construct(private FestiveRepository $festiveRepository){}

    public function createFestive(Festive $festive, Calendar $calendar)
    {
        $festive->setCalendar($calendar);

        $this->festiveRepository->add($festive, true);
    }
}
