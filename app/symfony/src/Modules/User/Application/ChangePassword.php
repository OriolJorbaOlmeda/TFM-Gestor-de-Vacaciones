<?php

namespace App\Modules\User\Application;


use App\Modules\User\Infrastucture\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;

class ChangePassword
{
    public function __construct(
        private UserRepository $userRepository,
        private Security $security,
        private UserPasswordHasherInterface $passwordHasher
    ){}

    public function changePassword($newPass)
    {
        $user = $this->userRepository->findOneBy(['id' => $this->security->getUser()->getId()]);
        $user->setPassword($this->passwordHasher->hashPassword($user, $newPass));
        $this->userRepository->add($user, true);
    }

}