<?php

namespace App\Controller\Petition;

use App\Modules\Calendar\Application\GetCurrentCalendar;
use App\Modules\Petition\Application\GetPendingPetitions;
use App\Modules\Petition\Application\GetUserPetitions;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PetitionListController extends AbstractController
{
    public function __construct(
        private GetPendingPetitions $getPendingPetitions,
        private GetCurrentCalendar $getCurrentCalendar,
        private GetUserPetitions $getUserPetitions
    ){}

    #[Route('/employee/vacation', name: 'app_employee_vacation')]
    public function vacation(Request $request): Response
    {
        $pagVac = $request->get('pagVac');
        $pagAbs = $request->get('pagAbs');

        $calendar = $this->getCurrentCalendar->getCurrentCalendar($this->getUser()->getDepartment()->getCompany());

        if(is_null($calendar)){
            $vacations=[];
            $absences=[];
            $num_petitions=0;
            $justify=[];


        }else {

            $vacations = $this->getUserPetitions->getUserVacations($this->getUser()->getId(), $calendar, $pagVac);

            $absences = $this->getUserPetitions->getUserAbsences($this->getUser()->getId(), $calendar, $pagAbs);


            $justify = [];
            foreach ($absences as $petition) {
                if ($petition->getJustify()) {
                    $justify [] = $petition->getJustify();
                }
            }

            //Para el caso de SUPERVISOR para poner en el panel
            $num_petitions = count($this->getPendingPetitions->getPendingPetitions());
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
}
