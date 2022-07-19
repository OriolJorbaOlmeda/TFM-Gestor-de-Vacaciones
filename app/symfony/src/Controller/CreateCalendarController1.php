<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Entity\Festive;
use App\Form\CalendarType;

use App\Form\FestiveType;
use App\Repository\CalendarRepository;
use App\Repository\CompanyRepository;
use App\Repository\FestiveRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class CreateCalendarController1 extends AbstractController
{

    public function __construct(
        private CalendarRepository $calendarRepository,
        private FestiveRepository $festiveRepository

    ) {
    }

    #[Route('/admin/create_calendar1', name: 'app_create_calendar_2')]
    public function createCalendar2(Request $request): Response
    {
        $calendar = new Calendar();
        $formCalendar = $this->createForm(CalendarType::class, $calendar);
        $formCalendar->handleRequest($request);


        if ($formCalendar->isSubmitted() && $formCalendar->isValid()) {
            // setear los campos que faltan
            $calendar->setCompany($this->getUser()->getDepartment()->getCompany());

            //$this->calendarRepository->add($calendar);

            //return new Response("CALENDAR SUBMITTED");
        }

        $festive = new Festive();
        $formFestive = $this->createForm(FestiveType::class, $festive);
        $formFestive->handleRequest($request);

        if ($formFestive->isSubmitted() && $formFestive->isValid()) {


            var_dump($calendar->getInitialDate());
            $calendar->addFestive($festive);
            $this->calendarRepository->add($calendar);


            /*$festive->setName('prueba');
            $festive->setDate(new \DateTime());
            $this->festiveRepository->add($festive);*/

            //return new Response("FESTIVE SUBMITTED");
           // return $this->redirectToRoute('app_login');
        }

        return $this->render('admin/crear_calendario1.html.twig', [
            'formCalendar' => $formCalendar->createView(),
            'formFestive' => $formFestive->createView()
        ]);
    }


    #[Route('/admin/create_calendar_prueba', name: 'app_create_calendar_prueba')]
    public function createCalendarPrueba(Request $request): Response
    {
        $calendar = new Calendar();
        $calendar->setYear("2022");
        $calendar->setInitialDate(new \DateTime("2022-01-01"));
        $calendar->setFinalDate(new \DateTime("2023-02-20"));
        $calendar->setCompany($this->getUser()->getDepartment()->getCompany());


        $festive = new Festive();
        $festive->setDate(new \DateTime("2022-01-06"));
        $festive->setName("dia festivo");

        $festive1 = new Festive();
        $festive1->setDate(new \DateTime("2022-03-10"));
        $festive1->setName("dia festivo 2");

        $calendar->addFestive($festive);
        $calendar->addFestive($festive1);

        $this->calendarRepository->add($calendar);

        return new Response("OK");

    }


}
