<?php

namespace App\Controller;

use App\Form\SearchByDepartmentType;
use App\Repository\CalendarRepository;
use App\Repository\PetitionRepository;
use App\Repository\UserRepository;
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
        private PetitionRepository $petitionRepository
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


        //Pillamos la información de la compañia para recoger el calendario
        $companyId = $this->getUser()->getDepartment()->getCompany();
        $calendar = $this->calendarRepository->findCurrentCalendar($companyId);
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

        $form = $this->createForm(SearchByDepartmentType::class, $result);
        $form->handleRequest($request);

        //Para el caso de SUPERVISOR para poner en el panel
        $num_petitions = 0;
        if (in_array("ROLE_SUPERVISOR", $this->getUser()->getRoles())) {
            $petitions = $this->petitionRepository->findBy(['supervisor' => $this->getUser(), 'state' => 'PENDING']);
            $num_petitions = count($petitions);
        }


        return $this->render('empleado/calendario.html.twig', [
            "departments" => $allDepartments,
            "formDepar" => $form->createView(),
            "festives" => $festives_company,
            'num_petitions' => $num_petitions
        ]);
    }

    #[Route('/employee/getUsers', name: 'app_employee_get')]
    public function getUsers(Request $request): Response
    {
        $departmentId = $request->request->get('id');
        $users = $this->userRepository->findBy(['department' => $departmentId]);
        $result = [];
        foreach ($users as $user) {
            $result[$user->getId()] = $user->getName();
        }


        return new JsonResponse(["users" => $result]);
    }


    #[Route('/employee/getCalendar', name: 'app_calendar_get')]
    public function getCalendar(Request $request): Response
    {
        //Pillamos la información del usuario seleccionado
        $userId = $request->request->get('id');
        $user = $this->userRepository->findOneBy(['id' => $userId]);


        $festivos_usuario = $user->getPetitions();


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
        foreach ($festivos_usuario as $festivo_usuario) {
            if (!empty($festivo_usuario) && $festivo_usuario->getState() == "ACCEPTED" && $festivo_usuario->getType()=="VACATION") {
                $vacation[$festivo_usuario->getId()] = [
                    "name" => $festivo_usuario->getReason(),
                    "date" => $festivo_usuario->getPetitionDate(),
                    "initialdate" => $festivo_usuario->getInitialDate(),
                    "finaldate" => $festivo_usuario->getFinalDate(),
                ];
            }
            if (!empty($festivo_usuario) && $festivo_usuario->getState() == "ACCEPTED" && $festivo_usuario->getType()=="ABSENCE") {
                $absence[$festivo_usuario->getId()] = [
                    "name" => $festivo_usuario->getReason(),
                    "date" => $festivo_usuario->getPetitionDate(),
                    "initialdate" => $festivo_usuario->getInitialDate(),
                    "finaldate" => $festivo_usuario->getFinalDate(),
                ];
            }
        }


        return new JsonResponse(["festivo_depar" => $festives_company,"festivo_usuario"=>$vacation,"absence_user"=>$absence]);
    }

}
