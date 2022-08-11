<?php

namespace App\Controller\Calendar;

use App\Modules\Calendar\Infrastucture\CalendarRepository;
use App\Modules\User\Infrastucture\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarAPIController extends AbstractController
{

    public function __construct(
        private UserRepository $userRepository,
        private CalendarRepository $calendarRepository
    ) {}

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