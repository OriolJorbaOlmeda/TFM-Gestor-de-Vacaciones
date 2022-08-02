<?php

namespace App\Controller;

use App\Entity\Petition;
use App\Form\RequestVacationFormType;
use App\Repository\CalendarRepository;
use App\Repository\PetitionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{

    public function __construct(
        private CalendarRepository $calendarRepository,
        private PetitionRepository $petitionRepository) {}

    #[Route('/employee/dashboard', name: 'app_employee_dashboard')]
    public function dashboard(): Response
    {
        //Pillamos la información de la compañia para recoger el calendario
        $companyId = $this->getUser()->getDepartment()->getCompany();
        $calendar = $this->calendarRepository->findCurrentCalendar($companyId);
        $festives = $calendar->getFestives();
        $festivos_usuario = $this->getUser()->getPetitions();
        $user_information = $this->getUser();

        $dias_utilizados= $user_information->getTotalVacationDays()- $user_information->getPendingVacationDays();

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

        //Para el caso de SUPERVISOR para poner en el panel
        $num_petitions = 0;
        if (in_array("ROLE_SUPERVISOR", $this->getUser()->getRoles())) {
            $petitions = $this->petitionRepository->findBy(['supervisor' => $this->getUser(), 'state' => 'PENDING']);
            $num_petitions = count($petitions);
        }


        return $this->render('empleado/home.html.twig',
            ["festivo_depar" => $festives_company,
                "festivo_usuario"=>$vacation,
                "absence_usuario"=>$absence,
                "user_information"=>$user_information,
                "days"=> $dias_utilizados,
                "initial_date"=>$calendar->getInitialDate()->format('d/m/y'),
                "final_date"=>$calendar->getFinalDate()->format('d/m/y'),
                "num_petitions" => $num_petitions],

        );

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
