<?php

namespace App\Modules\User\Application;

use App\Entity\User;
use App\Events\UserRegistrationEvent;
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

    public function __invoke(User $user, string $password, string $roles) {

        $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        $user->setPendingVacationDays($user->getTotalVacationDays());
        $user->setRoles([$roles]);
        $this->userRepository->add($user, true);
        $this->dispatcher->dispatch(new UserRegistrationEvent($this->security->getUser()->getEmail(),$user->getEmail(), $user->getName(), $password ));

    }

}