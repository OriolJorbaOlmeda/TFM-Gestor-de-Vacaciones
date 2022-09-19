<?php

use App\Modules\User\Domain\UserExample;
use PHPUnit\Framework\TestCase;


class UserTest extends TestCase
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->userExample = new UserExample();
    }

    public function testRegisterUser(){
        $user = $this->userExample->random();
        $newUser = $user;
        $user->setRoles(["ADMIN"]);
        $user->setPendingVacationDays($user->getTotalVacationDays());

        $newUser = $newUser->registerUser($newUser, ["ADMIN"]);
        $user->setPassword($newUser->getPassword());

        $this->assertEquals($user, $newUser);
    }

    public function testWrongRegisterUser(){
        $user = $this->userExample->random();
        $newUser = $user;
        $user->setRoles(["ADMIN"]);
        $user->setPendingVacationDays($user->getTotalVacationDays());

        $newUser = $newUser->registerUser($newUser, ["EMPLOYEE"]);
        $user->setPassword($newUser->getPassword());

        $this->assertEquals($user, $newUser);
    }


}