<?php

use App\Entity\Calendar;
use App\Entity\Petition;
use App\Entity\User;
use App\Modules\User\Domain\UserExample;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Modules\Petition\Domain\PetitionExample;

class PetitionRepositoryTest extends KernelTestCase
{
    private $entityManager;

    public function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->petition = new PetitionExample();
        $this->employee = new UserExample();
        $this->calendar = new CalendarExample();
    }

    public function testFindVacationsByUserAndCalendar()
    {
        $petition = $this->petition->random();
        $employee = $this->employee->random();
        $calendar = $this->calendar->random();

        $this->entityManager
            ->getRepository(User::class)
            ->add($employee, true)
        ;

        $this->entityManager
            ->getRepository(Calendar::class)
            ->add($calendar, true)
        ;

        $petition->setEmployee($employee);
        $petition->setSupervisor($employee);
        $petition->setCalendar($calendar);

        $this->entityManager
            ->getRepository(Petition::class)
            ->add($petition);

        $petitionFound = $this->entityManager
            ->getRepository(Petition::class)
            ->findVacationsByUserAndCalendar($employee->getId(), $calendar->getId(), 2);

        $this->assertContains($petition, $petitionFound);
    }

}