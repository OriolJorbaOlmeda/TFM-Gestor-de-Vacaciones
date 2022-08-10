<?php

namespace App\Modules\User\Application;

use App\Modules\User\Infrastucture\UserRepository;
use Psr\Container\ContainerInterface;

class SearchUser
{
    public function __construct(
        private UserRepository $userRepository,
        private ContainerInterface $container
    ) {}

    public function searchUsersByDepartment(string $departmentId): array
    {
        $users = $this->userRepository->findBy(['department' => $departmentId]);
        $result = [];
        foreach ($users as $user) {
            if($user->getRoles()[0]!=$this->container->getParameter('role_admin')) {
                $result[$user->getId()] = $user->getName() . " " . $user->getLastname();
            }

        }
        return $result;
    }

    public function searchSupervisorsByDepartment(string $departmentId): array
    {
        $users = $this->userRepository->findBy(['department' => $departmentId]);
        $result = [];
        foreach ($users as $user) {
            if (in_array($this->container->getParameter('role_supervisor'), $user->getRoles())) {
                $result[$user->getId()] = $user->getName() . " " . $user->getLastname();
            }

        }
        return $result;
    }

}