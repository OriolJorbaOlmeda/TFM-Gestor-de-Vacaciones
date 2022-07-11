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

class CreateCalendarController1 extends AbstractController
{

    public function __construct(
        private CompanyRepository $companyRepository,
        private CalendarRepository $calendarRepository
    ){}

    #[Route('/admin/create_calendar1', name: 'app_create_calendar_2')]
    public function createCalendar2(Request $request): Response
    {
        $calendar = new Calendar();
        $formCalendar = $this->createForm(CalendarType::class, $calendar);
        $formCalendar->handleRequest($request);


        if ($formCalendar->isSubmitted() && $formCalendar->isValid()) {
            // setear los campos que faltan
            $calendar->setCompany($this->companyRepository->findOneBy(['id' => 1]));


            return new Response("CALENDAR SUBMITTED");

        }


        return $this->render('admin/crear_calendario1.html.twig', [
            'formCalendar' => $formCalendar->createView()
        ]);
    }

    public function save() {
        return new Response("CALENDAR SUBMITTED");
    }


}
