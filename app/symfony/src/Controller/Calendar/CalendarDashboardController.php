<?php

namespace App\Controller\Calendar;

use App\Modules\Calendar\Application\GetCurrentCalendar;
use App\Modules\Calendar\Infrastucture\Form\SearchByDepartmentType;
use App\Modules\Festive\Application\GetFestivesJSON;
use App\Modules\Petition\Application\GetPendingPetitions;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarDashboardController extends AbstractController
{

    public function __construct(
        private GetPendingPetitions $getPendingPetitions,
        private GetFestivesJSON $getFestivesJSON,
        private GetCurrentCalendar $getCurrentCalendar
    ) {}

    #[Route('/employee/calendar', name: 'app_employee_calendar')]
    public function calendar(Request $request): Response
    {
        $form = $this->createForm(SearchByDepartmentType::class, []);
        $form->handleRequest($request);

        //Pillamos el calendario
        $calendar = $this->getCurrentCalendar->getCurrentCalendar($this->getUser()->getDepartment()->getCompany());


        if (is_null($calendar)){
            return $this->render('empleado/calendar.html.twig', [
                "calendar" => 0,
                "formDepar" => $form->createView(),
                "festives" => [],
                'num_petitions' => 0
            ]);
        }

        $festives = $calendar->getFestives();
        $festives_json = $this->getFestivesJSON->getFestivesJSON($festives);

        //Para el caso de SUPERVISOR para poner en el panel
        $num_petitions = count($this->getPendingPetitions->getPendingPetitions());


        return $this->render('empleado/calendar.html.twig', [
            "calendar" => 1,
            "formDepar" => $form->createView(),
            "festives" => $festives_json,
            'num_petitions' => $num_petitions
        ]);


    }






}
