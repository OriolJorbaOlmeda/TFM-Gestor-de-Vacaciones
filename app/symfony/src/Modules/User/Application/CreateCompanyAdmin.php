<?php

namespace App\Modules\User\Application;

use App\Entity\Department;
use App\Entity\User;
use App\Modules\User\Infrastucture\UserRepository;
use Psr\Container\ContainerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateCompanyAdmin
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private ContainerInterface $container,
        private UserRepository $userRepository
    ){}

    public function createCompanyAdmin(User $user, Department $adminDepartment)
    {
        // Rellenamos lo necesario
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
        $user->setRoles([$this->container->getParameter('role_admin')]);
        $user->setDepartment($adminDepartment);

        // Rellenamos los demás datos vacíos
        $user->setName("");
        $user->setLastname("");
        $user->setDirection("");
        $user->setCity("");
        $user->setProvince("");
        $user->setPostalcode("");
        $user->setTotalVacationDays(0);
        $user->setPendingVacationDays(0);

        $this->userRepository->add($user, true);
    }

}