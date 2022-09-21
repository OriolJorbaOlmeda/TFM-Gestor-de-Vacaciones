<?php

namespace App\Modules\User\Application;

use App\Entity\User;
use App\Modules\User\Domain\UserRegistrationEvent;
use App\Modules\User\Infrastucture\UserRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;

class CreateUser
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private EventDispatcherInterface $dispatcher,
        private UserRepository $userRepository,
        private Security $security
    ){}

    public function createUser(User $user, string $password, string $roles)
    {
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        $newUser = User::registerUser($user, $roles);
        $this->userRepository->add($newUser, true);
        $this->dispatcher->dispatch(new UserRegistrationEvent($this->security->getUser()->getEmail(),$newUser->getEmail(), $newUser->getName(), $password ));

    }

}