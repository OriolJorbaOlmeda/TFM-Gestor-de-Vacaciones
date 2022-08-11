<?php

namespace App\Modules\Petition\Application;

use App\Entity\Calendar;
use App\Modules\Petition\Infrastucture\PetitionRepository;

class GetUserPetitions
{

    public function __construct(private PetitionRepository $petitionRepository){}


    public function getUserVacations(string $userId, Calendar $calendar, int $pagVac): array
    {
        return $this->petitionRepository->findVacationsByUserAndCalendar($userId, $calendar->getId(), $pagVac);

    }


    public function getUserAbsences(string $userId, Calendar $calendar, int $pagVac): array
    {
        return $this->petitionRepository->findAbsencesByUserAndCalendar($userId, $calendar->getId(), $pagVac);

    }
}