<?php

namespace App\Modules\Petition\Application;

use App\Modules\Petition\Infrastucture\PetitionRepository;

class DeletePetition
{

    public function __construct(private PetitionRepository $petitionRepository){}

    public function deletePetition(string $petitionId): void
    {
        $petition = $this->petitionRepository->findOneBy(['id' => $petitionId]);
        $this->petitionRepository->remove($petition, true);

    }

}