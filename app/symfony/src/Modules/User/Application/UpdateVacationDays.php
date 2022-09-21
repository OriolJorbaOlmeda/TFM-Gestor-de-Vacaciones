<?php

namespace App\Modules\User\Application;

use App\Entity\User;
use App\Modules\User\Infrastucture\UserRepository;

class UpdateVacationDays
{

    public function __construct(private UserRepository $userRepository){}

    public function updateVacationDays(User $employee, int $duration)
    {
        $this->userRepository->updateVacationDays($employee, $duration);
    }
}