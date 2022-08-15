<?php

use App\Entity\User;
use App\Modules\User\Infrastucture\UserRepository;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private CourseRepository|MockInterface|null $repository;
    public function testPassword(): void
    {
        $user= new User();
        $user->setPassword('pepito');
        $employeeRepository = $this->createMock(UserRepository::class);
       $user2= $employeeRepository->expects($this->any())
            ->method('find')
            ->willReturn($user);

        $this->assertEquals('pepito', $user2->method());
    }
    public function testName(): void
    {
        $user= new User();
        $user->setPassword('pepito');
        $employeeRepository = $this->createMock(UserRepository::class);
        $employeeRepository->expects($this->any())
            ->method('find')
            ->willReturn($user);

        $this->assertEquals('pepito', $user->getPassword());
    }

}