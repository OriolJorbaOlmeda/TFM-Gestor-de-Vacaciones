<?php

namespace App\Modules\Festive\Application;

use Doctrine\Common\Collections\Collection;

class GetFestivesJSON
{
    public function getFestivesJSON(Collection $festives): array
    {
        $result = [];

        foreach ($festives as $festive) {
            $result[$festive->getId()] = [
                "name" => $festive->getName(),
                "date" => $festive->getDate(),
                "initialdate" => $festive->getDate(),
                "finaldate" => $festive->getDate(),
            ];
        }

        return $result;
    }

}