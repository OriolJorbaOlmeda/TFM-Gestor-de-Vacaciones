<?php

namespace App\Controller;

use App\Modules\Petition\Application\GetPendingPetitions;
use App\Repository\CalendarRepository;
use App\Modules\Petition\Infrastucture\PetitionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VacationListController extends AbstractController
{
    public function __construct(
        private PetitionRepository $petitionRepository,
        private CalendarRepository $calendarRepository,
        private GetPendingPetitions $getPendingPetitions
    ){}

    #[Route('/employee/vacation', name: 'app_employee_vacation')]
    public function vacation(Request $request): Response
    {
        $pagVac = $request->get('pagVac');
        $pagAbs = $request->get('pagAbs');

        $calendar = $this->calendarRepository->findCurrentCalendar($this->getUser()->getDepartment()->getCompany());
        if(is_null($calendar)){
            $vacations=[];
            $absences=[];
            $num_petitions=0;
            $justify=[];


        }else {
            $calendar_id = $calendar->getId();

            $vacations = $this->petitionRepository->findVacationsByUserAndCalendar(
                $this->getUser()->getId(),
                $calendar_id,
                $pagVac
            );

            $absences = $this->petitionRepository->findAbsencesByUserAndCalendar(
                $this->getUser()->getId(),
                $calendar_id,
                $pagAbs
            );

            $justify = [];
            foreach ($absences as $petition) {
                if ($petition->getJustify()) {
                    $justify [] = $petition->getJustify();
                }
            }

            //Para el caso de SUPERVISOR para poner en el panel
            $num_petitions = $this->getPendingPetitions->__invoke();
        }


        return $this->render('empleado/mis_vacaciones.html.twig', [
            'vacations' => $vacations,
            'absences' => $absences,
            'num_petitions' => $num_petitions,
            'justify' => $justify,
            'pagVac' => $pagVac,
            'pagAbs' => $pagAbs
        ]);
    }

    #[Route('/employee/delete_petition', name: 'app_employee_delete_petition')]
    public function deletePetition(Request $request): Response
    {
        $petitionId = $request->get('petitionId');
        $pagVac = $request->get('pagVac');
        $pagAbs = $request->get('pagAbs');

        $petition = $this->petitionRepository->findOneBy(['id' => $petitionId]);
        $this->petitionRepository->remove($petition, true);

        return $this->redirectToRoute('app_employee_vacation', ['pagVac' => $pagVac, 'pagAbs' => $pagAbs]);
    }
}
