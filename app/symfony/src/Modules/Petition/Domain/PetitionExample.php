<?php

namespace App\Modules\Petition\Domain;

use App\Entity\Petition;

class PetitionExample
{
    public static function random(): Petition
    {
        $petition = new Petition();
        $petition->setInitialDate(new \DateTime('2023-01-01'));
        $petition->setFinalDate(new \DateTime('2023-01-02'));
        $petition->setDuration(1);
        $petition->setState('PENDING');
        $petition->setType('VACATION');
        $petition->setReason('vacation');
        $petition->setPetitionDate(new \DateTime('2023-01-01'));

        return $petition;

    }
}