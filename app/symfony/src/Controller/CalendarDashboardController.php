<?php

namespace App\Controller;

use App\Form\SearchByDepartmentType;
use App\Repository\CalendarRepository;
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
        private CalendarRepository $calendarRepository) {
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
        $calendar = $this->calendarRepository->findOneBy(['company' => $companyId]);
        $festives = $calendar->getFestives();
        $festives_company = [];

        foreach ($festives as $festive) {
            $festives_company[$festive->getId()] = [
                "name" => $festive->getName(),
                "date" => $festive->getDate(),
                "initialdate"=>$festive->getDate(),
                "finaldate"=>$festive->getDate(),
            ];
        }

        $form = $this->createForm(SearchByDepartmentType::class, $result);
        $form->handleRequest($request);


        return $this->render('empleado/calendario.html.twig', [
            "departments" => $allDepartments,
            "formDepar" => $form->createView(),
            "festives" => $festives_company
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
        $festivos_usuario= $user->getPetition();


        //Pillamos la información de la compañia para recoger el calendario
        $companyId = $this->getUser()->getDepartment()->getCompany();
        $calendar = $this->calendarRepository->findOneBy(['company' => $companyId]);
        $festives = $calendar->getFestives();

        //Nos guardamos los festivos del calendario
        $result = [];
        foreach ($festives as $festive) {
            $result[$festive->getId()] = [
                "name" => $festive->getName(),
                "date" => $festive->getDate(),
                "initialdate"=>$festive->getDate(),
                "finaldate"=>$festive->getDate(),
            ];
        }

        //Nos guardamos los festivos del usuario
        //foreach ($festivos_usuario as $festivo_usuario) {
        if(!empty($festivos_usuario) && $festivos_usuario->getState()=="ACCEPTED") {
            $result[$festivos_usuario->getId()] = [
                "name" => $festivos_usuario->getReason(),
                "date" => $festivos_usuario->getPetitionDate(),
                "initialdate" => $festivos_usuario->getInitialDate(),
                "finaldate" => $festivos_usuario->getFinalDate(),
            ];
        }
      //  }


        return new JsonResponse(["festives" => $result]);
    }

}