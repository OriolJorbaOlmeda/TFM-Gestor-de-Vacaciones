<?php

namespace App\Modules\Petition\Application;

use App\Entity\Petition;
use App\Modules\Petition\Infrastucture\PetitionRepository;
use Psr\Container\ContainerInterface;

class AcceptPetition
{

    public function __construct(
        private PetitionRepository $petitionRepository,
        private ContainerInterface $container
    ){}

    public function acceptPetition(string $petitionId): Petition
    {
        $petition = $this->petitionRepository->findOneBy(['id' => $petitionId]);
        $petition->setState($this->container->getParameter('accepted'));
        $this->petitionRepository->add($petition, true);

        return $petition;
    }
}
