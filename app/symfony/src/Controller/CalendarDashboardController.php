<?php

namespace App\Controller;

use App\Form\SearchByDepartmentType;
use App\Repository\CalendarRepository;
use App\Repository\DepartmentRepository;
use App\Repository\FestiveRepository;
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
        private FestiveRepository $festiveRepository
    ) {
    }

    #[Route('/employee/calendar', name: 'app_employee_calendar')]
    public function calendar(Request $request): Response
    {
        $allDepartments = $this->getUser()->getDepartment()->getCompany()->getDepartments();

        // $department = $this->getUser()->getDepartment();

        // var_dump($allDepartments[0]->getName());
        // $employees = $this->userRepository->findBy(['department' => $department->getId()]);
        $result = [];
        foreach ($allDepartments as $department) {
            $result[$department->getName()] = $department->getId();
        }


        $form = $this->createForm(SearchByDepartmentType::class, $result);
        $form->handleRequest($request);


        return $this->render('empleado/calendario.html.twig', [
            "departments" => $allDepartments,
            "formDepar" => $form->createView()
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
        $userId = $request->request->get('id');
        $users = $this->userRepository->findBy(['id' => $userId]);
        $companyId = $this->getUser()->getDepartment()->getCompany();
        $calendar = $this->calendarRepository->findOneBy(['company' => $companyId]);
        //$content= $this->getCalendar()->getContent();
        $festives = $calendar->getFestives();


        $result = [];
        foreach ($festives as $festive) {
            $result[$festive->getId()] = [
                "name" => $festive->getName(),
                "date" => $festive->getDate()
            ];
        }


        return new JsonResponse(["festives" => $result]);
    }

}
