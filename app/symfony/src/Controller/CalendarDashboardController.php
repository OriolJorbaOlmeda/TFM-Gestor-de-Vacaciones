<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarDashboardController extends AbstractController
{

    public function __construct(private UserRepository $userRepository){}

    #[Route('/employee/calendar', name: 'app_employee_calendar')]
    public function calendar(): Response
    {
        $allDepartments = $this->getUser()->getDepartment()->getCompany()->getDepartments();

        $department = $this->getUser()->getDepartment();
        $employees = $this->userRepository->findBy(['department_id' => $department->getId()]);


        return $this->render('empleado/calendario.html.twig', [
            "departments" => $allDepartments
        ]);

    }
}
