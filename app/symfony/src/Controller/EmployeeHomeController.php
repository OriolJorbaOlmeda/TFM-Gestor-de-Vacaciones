<?php

namespace App\Controller;

use App\Modules\Calendar\Application\GetCurrentCalendar;
use App\Modules\Festive\Application\GetFestivesJSON;
use App\Modules\Petition\Application\GetPendingPetitions;
use App\Modules\Petition\Application\GetPetitionsJSON;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeHomeController extends AbstractController
{

    public function __construct(
        private GetPendingPetitions $getPendingPetitions,
        private GetFestivesJSON $getFestivesJSON,
        private GetCurrentCalendar $getCurrentCalendar,
        private GetPetitionsJSON $getPetitionsJSON
    ) {}

    #[Route('/employee/dashboard', name: 'app_employee_dashboard')]
    public function dashboard(): Response
    {
        //Pillamos el calendario actual
        $company = $this->getUser()->getDepartment()->getCompany();
        $calendar = $this->getCurrentCalendar->getCurrentCalendar($company);

        $dias_utilizados = $this->getUser()->getTotalVacationDays() - $this->getUser()->getPendingVacationDays();

        //Para el caso de SUPERVISOR para poner en el panel
        $num_petitions = count($this->getPendingPetitions->getPendingPetitions());

        if (is_null($calendar)) {
            return $this->render('empleado/home.html.twig', [
                "calendar" => 0,
                "user_information" => $this->getUser(),
                "days" => $dias_utilizados,
                "num_petitions" => $num_petitions
            ]);
        }

        $userPetitions = $this->getUser()->getPetitions();

        $festives_company = $this->getFestivesJSON->getFestivesJSON($calendar->getFestives());

        $vacations = $this->getPetitionsJSON->getAcceptedVacationsJSON($userPetitions);

        $absences = $this->getPetitionsJSON->getAcceptedAbsencesJSON($userPetitions);


        return $this->render('empleado/home.html.twig', [
            "calendar" => 1,
            "festivo_depar" => $festives_company,
            "festivo_usuario" => $vacations,
            "absence_usuario" => $absences,
            "user_information" => $this->getUser(),
            "days" => $dias_utilizados,
            "initial_date" => $calendar->getInitialDate()->format('d/m/Y'),
            "final_date" => $calendar->getFinalDate()->format('d/m/Y'),
            "num_petitions" => $num_petitions
        ]);
    }

}
