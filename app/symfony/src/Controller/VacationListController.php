<?php

namespace App\Controller;

use App\Repository\PetitionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VacationListController extends AbstractController
{
    public function __construct(private PetitionRepository $petitionRepository){}

    #[Route('/employee/vacation', name: 'app_employee_vacation')]
    public function vacation(): Response
    {
        $calendar_id = "100"; // cambiar calendario

        $vacations = $this->petitionRepository->findVacationsByUserAndCalendar($this->getUser()->getId(), $calendar_id);

        $absences = $this->petitionRepository->findAbsencesByUserAndCalendar($this->getUser()->getId(), $calendar_id);


        return $this->render('empleado/mis_vacaciones.html.twig', [
            'vacations' => $vacations,
            'absences' => $absences
        ]);

    }
}
