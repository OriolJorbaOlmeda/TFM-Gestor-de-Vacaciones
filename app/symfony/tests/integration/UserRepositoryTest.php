<?php

use App\Entity\User;
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
    }

    /*public function testUpdateVacationDays(){

        $user = $this->entityManager
            ->getRepository(User::class)
            ->updateVacationDays(new User(), 24);

        $this->assertSame(24, $user->getPendingVacationDays());
    }*/

    public function testSearchByName()
    {
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['name' => 'MireiaAdmin'])
        ;

        $this->assertSame('Pepazo', $user->getLastname());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}