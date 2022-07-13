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
        private CompanyRepository $companyRepository,
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
            $calendar->setCompany($this->companyRepository->findOneBy(['id' => 1]));
            $this->calendarRepository->add($calendar);

            return new Response("CALENDAR SUBMITTED");
        }

        $festive = new Festive();
        $formFestive = $this->createForm(FestiveType::class, $festive);
        $formFestive->handleRequest($request);

        if ($formFestive->isSubmitted() && $formFestive->isValid()) {
            //$festive->addCalendar($calendar);
            $festive->setName('prueba');
            $festive->setDate(new \DateTime());

            $this->festiveRepository->add($festive);

            //return new Response("Festive SUBMITTED");
           // return $this->redirectToRoute('app_login');
        }

        return $this->render('admin/crear_calendario1.html.twig', [
            'formCalendar' => $formCalendar->createView(),
            'formFestive' => $formFestive->createView()
        ]);
    }

    public function save()
    {
        return new Response("CALENDAR SUBMITTED");
    }


}
