<?php

namespace App\Modules\Petition\Application;

use Doctrine\Common\Collections\Collection;
use Psr\Container\ContainerInterface;

class GetPetitionsJSON
{
    public function __construct(private ContainerInterface $container){}

    public function getAcceptedVacationsJSON(Collection $petitions): array
    {
        $vacations = [];

        foreach ($petitions as $petition) {
            if ($petition->getState() == $this->container->getParameter('accepted') && $petition->getType() == $this->container->getParameter('vacation')) {
                $vacations[$petition->getId()] = [
                    "name" => $petition->getReason(),
                    "date" => $petition->getPetitionDate(),
                    "initialdate" => $petition->getInitialDate(),
                    "finaldate" => $petition->getFinalDate(),
                ];
            }
        }
        return $vacations;
    }

    public function getAcceptedAbsencesJSON(Collection $petitions): array
    {
        $absences = [];

        foreach ($petitions as $petition) {
            if ($petition->getState() == $this->container->getParameter('accepted') && $petition->getType() == $this->container->getParameter('absence')) {
                $absences[$petition->getId()] = [
                    "name" => $petition->getReason(),
                    "date" => $petition->getPetitionDate(),
                    "initialdate" => $petition->getInitialDate(),
                    "finaldate" => $petition->getFinalDate(),
                ];
            }
        }
        return $absences;
    }

}