<?php

namespace App\Controller\Calendar;

use App\Entity\Calendar;
use App\Modules\Calendar\Application\CreateCalendar;
use App\Modules\Calendar\Infrastucture\Form\CalendarType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateCalendarController extends AbstractController
{
    public function __construct(private CreateCalendar $createCalendar){}

    #[Route('/admin/create_calendar', name: 'app_create_calendar')]
    public function createCalendar(Request $request): Response
    {
        $calendar = new Calendar();
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->createCalendar->createCalendar($calendar);

            return $this->redirectToRoute('app_create_festives', ['calendarId' => $calendar->getId()]);

        }

        return $this->render('admin/create_calendar.html.twig', [
            'form' => $form->createView()
        ]);

    }


}