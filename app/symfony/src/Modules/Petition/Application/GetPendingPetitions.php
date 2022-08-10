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

    public function __invoke(): int
    {
        $num_petitions = 0;
        if (in_array($this->container->getParameter('role_supervisor'), $this->security->getUser()->getRoles())) {
            $petitions = $this->petitionRepository->findBy(
                ['supervisor' => $this->security->getUser(), 'state' => $this->container->getParameter('pending')]
            );
            $num_petitions = count($petitions);
        }
        return $num_petitions;

    }

}