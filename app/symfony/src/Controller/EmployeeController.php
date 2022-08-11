<?php

namespace App\Controller;

use App\Modules\Festive\Application\GetFestivesJSON;
use App\Modules\Petition\Application\GetPendingPetitions;
use App\Modules\Calendar\Infrastucture\CalendarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{

    public function __construct(
        private CalendarRepository $calendarRepository,
        private GetPendingPetitions $getPendingPetitions,
        private GetFestivesJSON $getFestivesJSON
    ) {}

    #[Route('/employee/dashboard', name: 'app_employee_dashboard')]
    public function dashboard(): Response
    {
        //Pillamos la información de la compañia para recoger el calendario
        $companyId = $this->getUser()->getDepartment()->getCompany();
        $calendar = $this->calendarRepository->findCurrentCalendar($companyId);
        $user_information = $this->getUser();
        $dias_utilizados = $user_information->getTotalVacationDays() - $user_information->getPendingVacationDays();
        //Para el caso de SUPERVISOR para poner en el panel
        $num_petitions = count($this->getPendingPetitions->getPendingPetitions());

            if (is_null($calendar)) {
                return $this->render('empleado/home.html.twig', [
                    "calendar" => 0,
                    "user_information" => $user_information,
                    "days" => $dias_utilizados,
                    "num_petitions" => $num_petitions
                ]);
            }

            $festives = $calendar->getFestives();
            $festivos_usuario = $this->getUser()->getPetitions();

            $vacation = [];
            $absence = [];

            $festives_company = $this->getFestivesJSON->getFestivesJSON($festives);

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


            return $this->render('empleado/home.html.twig', [
                "calendar" => 1,
                "festivo_depar" => $festives_company,
                "festivo_usuario" => $vacation,
                "absence_usuario" => $absence,
                "user_information" => $user_information,
                "days" => $dias_utilizados,
                "initial_date" => $calendar->getInitialDate()->format('d/m/Y'),
                "final_date" => $calendar->getFinalDate()->format('d/m/Y'),
                "num_petitions" => $num_petitions
            ]);
        }

}
