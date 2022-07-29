<?php

namespace App\Controller;

use App\Repository\CalendarRepository;
use App\Repository\PetitionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VacationListController extends AbstractController
{
    public function __construct(
        private PetitionRepository $petitionRepository,
        private CalendarRepository $calendarRepository
    ){}

    #[Route('/employee/vacation', name: 'app_employee_vacation')]
    public function vacation(): Response
    {
        $calendar = $this->calendarRepository->findCurrentCalendar($this->getUser()->getDepartment()->getCompany());
        $calendar_id = $calendar->getId();

        $vacations = $this->petitionRepository->findVacationsByUserAndCalendar($this->getUser()->getId(), $calendar_id);

        $absences = $this->petitionRepository->findAbsencesByUserAndCalendar($this->getUser()->getId(), $calendar_id);


        return $this->render('empleado/mis_vacaciones.html.twig', [
            'vacations' => $vacations,
            'absences' => $absences
        ]);
    }

    #[Route('/employee/delete_petition', name: 'app_employee_delete_petition')]
    public function deletePetition(Request $request): Response
    {
        $petitionId = $request->get('petitionId');
        $petition = $this->petitionRepository->findOneBy(['id' => $petitionId]);
        $this->petitionRepository->remove($petition, true);

        return $this->redirectToRoute('app_employee_vacation');
    }
}
