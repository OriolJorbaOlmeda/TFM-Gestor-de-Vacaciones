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
    ) {
    }

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
        if (!is_null($calendar)) {
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
        } else {
            return $this->render('empleado/calendario.html.twig', [
                "calendar" => 0,
                "departments" => $allDepartments,
                "formDepar" => $form->createView(),
                "festives" => [],
                'num_petitions' => 0
            ]);
        }
    }

    #[Route('/employee/getUsers', name: 'app_employee_get')]
    public function getUsers(Request $request): Response
    {
        $departmentId = $request->request->get('id');
        $users = $this->userRepository->findBy(['department' => $departmentId]);
        $result = [];
        foreach ($users as $user) {
            $result[$user->getId()] = $user->getName() . " " . $user->getLastname();
        }


        return new JsonResponse(["users" => $result]);
    }


    #[Route('/employee/getCalendar', name: 'app_calendar_get')]
    public function getCalendar(Request $request): Response
    {
        //Pillamos la información del usuario seleccionado
        $userId = $request->request->get('id');
        //Pillamos la información de la compañia para recoger el calendario
        $companyId = $this->getUser()->getDepartment()->getCompany();
        $calendar = $this->calendarRepository->findCurrentCalendar($companyId);
        $festives = $calendar->getFestives();
        //Nos guardamos los festivos del calendario

        $festives_company = [];
        $vacation = [];
        $absence = [];
        foreach ($festives as $festive) {
            $festives_company[$festive->getId()] = [
                "name" => $festive->getName(),
                "date" => $festive->getDate(),
                "initialdate" => $festive->getDate(),
                "finaldate" => $festive->getDate(),
            ];
        }

        if ($userId != 0) {
            $user = $this->userRepository->findOneBy(['id' => $userId]);
            $festivos_usuario = $user->getPetitions();


            foreach ($festivos_usuario as $festivo_usuario) {
                if (!empty($festivo_usuario) && $festivo_usuario->getState() == $this->getParameter(
                        'accepted'
                    ) && $festivo_usuario->getType() == $this->getParameter('vacation')) {
                    $vacation[$festivo_usuario->getId()] = [
                        "name" => $festivo_usuario->getReason(),
                        "date" => $festivo_usuario->getPetitionDate(),
                        "initialdate" => $festivo_usuario->getInitialDate(),
                        "finaldate" => $festivo_usuario->getFinalDate(),
                    ];
                }
                if (!empty($festivo_usuario) && $festivo_usuario->getState() == $this->getParameter(
                        'accepted'
                    ) && $festivo_usuario->getType() == $this->getParameter('absence')) {
                    $absence[$festivo_usuario->getId()] = [
                        "name" => $festivo_usuario->getReason(),
                        "date" => $festivo_usuario->getPetitionDate(),
                        "initialdate" => $festivo_usuario->getInitialDate(),
                        "finaldate" => $festivo_usuario->getFinalDate(),
                    ];
                }
            }
        }


        return new JsonResponse(
            ["festivo_depar" => $festives_company, "festivo_usuario" => $vacation, "absence_user" => $absence]
        );
    }

}
