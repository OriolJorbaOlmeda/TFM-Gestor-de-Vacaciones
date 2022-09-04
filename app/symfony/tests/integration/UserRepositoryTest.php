<?php

use App\Entity\Festive;
use App\Entity\User;
use App\Modules\User\Domain\UserExample;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    private $entityManager;

    public function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->user = new UserExample();
    }

    public function testUpdateVacationDays(){

        $newUser = $this->user->random();


        $this->entityManager
            ->getRepository(User::class)
            ->add($newUser, true)
        ;

        $this->entityManager
            ->getRepository(User::class)
            ->updateVacationDays($newUser, 2);

        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['name' => 'Mireias'])
        ;

        $this->assertEquals(21, $user->getPendingVacationDays());
    }

    /*public function testSearchByName()
    {
        $newUser = $this->user->random();


        $this->entityManager
            ->getRepository(User::class)
            ->add($newUser, true)
        ;

        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['name' => 'Mireia'])
        ;

        $this->assertEquals('Pepazo', $user->getLastname());
    }*/

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}