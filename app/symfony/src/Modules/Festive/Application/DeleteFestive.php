<?php

namespace App\Modules\Festive\Application;

use App\Modules\Festive\Infrastucture\FestiveRepository;

class DeleteFestive
{

    public function __construct(private FestiveRepository $festiveRepository){}

    public function deleteFestive(string $festiveId)
    {
        $festive = $this->festiveRepository->findOneBy(['id' => $festiveId]);

        $this->festiveRepository->remove($festive, true);
    }
}