<?php

use App\Entity\Justify;
use App\Entity\Petition;
use PHPUnit\Framework\TestCase;

class JustifyTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->justify = new Justify();
    }

    public function testSettingJustifyTitle(){
        $title = 'title';
        $this->justify->setTitle($title);

        $this->assertEquals($title, $this->justify->getTitle());
    }

    public function testSettingJustifyContent(){
        $content = 'content';
        $this->justify->setContent($content);

        $this->assertEquals($content, $this->justify->getContent());
    }

    public function testSettingJustifyPetition(){
        $petition = new Petition();
        $this->justify->setPetition($petition);

        $this->assertEquals($petition, $this->justify->getPetition());
    }
}