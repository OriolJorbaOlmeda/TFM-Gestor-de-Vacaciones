<?php

namespace App\Controller\Calendar;

use App\Modules\Calendar\Application\GetCurrentCalendar;
use App\Modules\Festive\Application\GetFestivesJSON;
use App\Modules\Petition\Application\GetPetitionsJSON;
use App\Modules\User\Application\SearchUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarAPIController extends AbstractController
{

    public function __construct(
        private GetCurrentCalendar $getCurrentCalendar,
        private GetFestivesJSON $getFestivesJSON,
        private GetPetitionsJSON $getPetitionsJSON,
        private SearchUser $searchUser
    ) {}

    #[Route('/employee/getCalendar', name: 'app_calendar_get')]
    public function getCalendar(Request $request): Response
    {
        //Pillamos la información del usuario seleccionado
        $userId = $request->request->get('id');

        //Pillamos el calendario actual
        $company = $this->getUser()->getDepartment()->getCompany();
        $calendar = $this->getCurrentCalendar->getCurrentCalendar($company);


        //Nos guardamos los festivos del calendario
        $festives_company = $this->getFestivesJSON->getFestivesJSON($calendar->getFestives());

        $vacation = [];
        $absence = [];


        if ($userId != 0) {
            $user = $this->searchUser->searchUserById($userId);

            $userPetitions = $user->getPetitions();

            $vacation = $this->getPetitionsJSON->getAcceptedVacationsJSON($userPetitions);

            $absence = $this->getPetitionsJSON->getAcceptedAbsencesJSON($userPetitions);

        }


        return new JsonResponse(
            ["festivo_depar" => $festives_company, "festivo_usuario" => $vacation, "absence_user" => $absence]
        );
    }

}