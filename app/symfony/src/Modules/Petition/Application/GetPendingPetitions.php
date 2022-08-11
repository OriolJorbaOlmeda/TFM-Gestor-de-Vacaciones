<?php

namespace App\Modules\Petition\Application;

use App\Modules\Petition\Infrastucture\PetitionRepository;
use Psr\Container\ContainerInterface;
use Symfony\Component\Security\Core\Security;


class GetPendingPetitions
{

    public function __construct(
        private PetitionRepository $petitionRepository,
        private ContainerInterface $container,
        private Security $security
    ){}

    public function getPendingPetitions(): array
    {
        return $this->petitionRepository->findBy(['supervisor' => $this->security->getUser(), 'state' => $this->container->getParameter('pending')]);

    }

}