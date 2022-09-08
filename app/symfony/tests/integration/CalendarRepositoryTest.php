<?php

use App\Entity\Calendar;
use App\Entity\Company;
use App\Modules\Company\Domain\CompanyExample;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CalendarRepositoryTest extends KernelTestCase
{
    private $entityManager;

    public function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->calendar = new CalendarExample();
        $this->company = new CompanyExample();
    }

    public function testFindCalendarByDates()
    {
        $newCalendar = $this->calendar->random();

        $company = $this->company->random();

        $this->entityManager
            ->getRepository(Company::class)
            ->add($company, true);

        $newCalendar->setCompany($company);

        $this->entityManager
            ->getRepository(Calendar::class)
            ->add($newCalendar, true)
        ;

        $calendar = $this->entityManager
            ->getRepository(Calendar::class)
            ->findCalendarByDates($newCalendar->getInitialDate(), $newCalendar->getFinalDate())
        ;

        $this->assertEquals($calendar, $newCalendar);
    }
}