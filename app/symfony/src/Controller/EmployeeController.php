<?php

namespace App\Controller;

use App\Entity\Petition;
use App\Form\RequestVacationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    private $security;

    public function __construct(SecurityController $security)
    {
        $this->security = $security;
    }

    #[Route('/employee/dashboard', name: 'app_employee_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('empleado/home.html.twig');

    }
    /*#[Route('/employee/calendar', name: 'app_employee_calendar')]
    public function calendar(): Response
    {
        return $this->render('empleado/calendario.html.twig');

    }*/

    #[Route('/employee/vacation', name: 'app_employee_vacation')]
    public function vacation(): Response
    {
        return $this->render('empleado/mis_vacaciones.html.twig');

    }

    #[Route('/employee/request-vacation', name: 'app_employee_request-vacation')]
    public function requestVacation(Request $request,  EntityManagerInterface $entityManager ): Response
    {
        $petition = new Petition();
        $form = $this->createForm(RequestVacationFormType::class, $petition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           var_dump("hools");
           //pendiente hacer formulario date

           // $entityManager->persist($petition);
            //$entityManager->flush();
        }

        return $this->render('empleado/solicitar_vacaciones.html.twig');

    }

    #[Route('/employee/request-absence', name: 'app_employee_request-absence')]
    public function requestAbsence(): Response
    {
        return $this->render('empleado/solicitar_ausencia.html.twig');

    }
}
