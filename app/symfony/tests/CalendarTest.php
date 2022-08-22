<?php

use App\Entity\Calendar;
use App\Entity\Company;
use App\Entity\Petition;
use PHPUnit\Framework\TestCase;

class CalendarTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->calendar = new Calendar();
    }

    public function testSettingCalendarInitialDate(){
        $initial = new DateTime();
        $this->calendar->setInitialDate($initial);

        $this->assertEquals($initial, $this->calendar->getInitialDate());
    }

    public function testSettingCalendarFinalDate(){
        $final = new DateTime();
        $this->calendar->setFinalDate($final);

        $this->assertEquals($final, $this->calendar->getFinalDate());
    }

    public function testSettingCalendarCompany(){
        $company = new Company();
        $this->calendar->setCompany($company);

        $this->assertEquals($company, $this->calendar->getCompany());
    }

    public function testSettingCalendarPetition(){
        $petition = new Petition();
        $this->calendar->addPetition($petition);

        $this->assertTrue($this->calendar->getPetitions()->contains($petition));
    }

    public function testRemovingCalendarPetition(){
        $petition = new Petition();
        $this->calendar->addPetition($petition);
        $this->calendar->removePetition($petition);

        $this->assertEmpty($this->calendar->getPetitions());
    }

    /*public function testSettingCalendarFestive(){
        $festive = new Festive();
        $this->calendar->addFestive($festive);

        $this->assertTrue($this->calendar->getFestives()->contains($festive));
    }*/

    public function testSettingCalendarYear(){
        $year = '2022';
        $this->calendar->setYear($year);

        $this->assertEquals($year, $this->calendar->getYear());
    }
}