<?php

namespace App\Modules\Petition\Application;

use App\Modules\Petition\Infrastucture\PetitionRepository;
use Psr\Container\ContainerInterface;

class DenyPetition
{
    public function __construct(
        private PetitionRepository $petitionRepository,
        private ContainerInterface $container
    ){}

    public function denyPetition(string $petitionId)
    {
        $petition = $this->petitionRepository->findOneBy(['id' => $petitionId]);
        $petition->setState($this->container->getParameter('denied'));
        $this->petitionRepository->add($petition, true);

    }
}