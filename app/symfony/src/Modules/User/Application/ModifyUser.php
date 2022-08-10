<?php

namespace App\Modules\User\Application;

use App\Entity\User;
use App\Modules\User\Infrastucture\UserRepository;

class ModifyUser
{

    public function __construct(
        private UserRepository $userRepository
    ) {}


    public function __invoke(User $user, string $roles) {

        $user->setRoles([$roles]);
        $this->userRepository->add($user, true);

    }


    public function getUserById(string $userid): User
    {
        return $this->userRepository->findOneBy(array('id' => $userid));
    }
}