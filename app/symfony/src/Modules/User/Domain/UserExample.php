<?php

namespace App\Modules\User\Domain;

use App\Entity\User;

class UserExample
{
    public static function random(): User
    {
        $user = new User();
        $user->setName('Mireias');
        $user->setLastname('Pepazo');
        $user->setDirection('Pepazo');
        $user->setCity('Pepazo');
        $user->setProvince('Pepazo');
        $user->setPostalcode('Pepazo');
        $user->setTotalVacationDays(23);
        $user->setPendingVacationDays(23);
        $user->setEmail('Pepazo99@gmail.com');
        $user->setPassword('vgyuoberijlfbeijrbfijenfje');

        return $user;
    }
}