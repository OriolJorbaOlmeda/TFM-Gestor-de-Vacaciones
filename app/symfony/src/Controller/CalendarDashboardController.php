<?php

namespace App\Controller;

use App\Modules\Calendar\Infrastucture\Form\SearchByDepartmentType;
use App\Modules\Petition\Application\GetPendingPetitions;
use App\Modules\User\Infrastucture\UserRepository;
use App\Modules\Calendar\Infrastucture\CalendarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarDashboardController extends AbstractController
{

    public function __construct(
        private UserRepository $userRepository,
        private CalendarRepository $calendarRepository,
        private GetPendingPetitions $getPendingPetitions
    ) {}

    #[Route('/employee/calendar', name: 'app_employee_calendar')]
    public function calendar(Request $request): Response
    {
        //Cogemos todos los departamentos de una compañia para mostrarlos en el select
        $allDepartments = $this->getUser()->getDepartment()->getCompany()->getDepartments();
        $result = [];
        foreach ($allDepartments as $department) {
            $result[$department->getName()] = $department->getId();
        }
        $form = $this->createForm(SearchByDepartmentType::class, $result);
        $form->handleRequest($request);

        //Pillamos la información de la compañia para recoger el calendario
        $companyId = $this->getUser()->getDepartment()->getCompany();
        $calendar = $this->calendarRepository->findCurrentCalendar($companyId);


        if (is_null($calendar)){
            return $this->render('empleado/calendario.html.twig', [
                "calendar" => 0,
                "departments" => $allDepartments,
                "formDepar" => $form->createView(),
                "festives" => [],
                'num_petitions' => 0
            ]);
        }

        $festives = $calendar->getFestives();
        $festives_company = [];

        foreach ($festives as $festive) {
            $festives_company[$festive->getId()] = [
                "name" => $festive->getName(),
                "date" => $festive->getDate(),
                "initialdate" => $festive->getDate(),
                "finaldate" => $festive->getDate(),
            ];
        }


        //Para el caso de SUPERVISOR para poner en el panel
        $num_petitions = count($this->getPendingPetitions->getPendingPetitions());


        return $this->render('empleado/calendario.html.twig', [
            "calendar" => 1,
            "departments" => $allDepartments,
            "formDepar" => $form->createView(),
            "festives" => $festives_company,
            'num_petitions' => $num_petitions
        ]);


    }






}
