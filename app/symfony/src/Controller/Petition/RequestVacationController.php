<?php

namespace App\Controller\Petition;

use App\Entity\Petition;
use App\Modules\Calendar\Application\GetCalendarByDates;
use App\Modules\Calendar\Application\GetCurrentCalendar;
use App\Modules\Petition\Application\GetPendingPetitions;
use App\Modules\Petition\Application\RequestVacation;
use App\Modules\Petition\Infrastucture\Form\RequestVacationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RequestVacationController extends AbstractController
{
    public function __construct(
        private TranslatorInterface $translator,
        private GetPendingPetitions $getPendingPetitions,
        private GetCalendarByDates $getCalendarByDates,
        private RequestVacation $requestVacation,
        private GetCurrentCalendar $getCurrentCalendar
    ) {}

    #[Route('/employee/request-vacation', name: 'app_employee_request-vacation')]
    public function requestVacation(Request $request): Response
    {
        $petition = new Petition();
        $form = $this->createForm(RequestVacationFormType::class, $petition);
        $form->handleRequest($request);

        $company = $this->getUser()->getDepartment()->getCompany();
        $calendar = $this->getCurrentCalendar->getCurrentCalendar($company);

        if (!is_null($calendar)) {
            $festives = $calendar->getFestives();
            $days = array();
            foreach ($festives as $festive) {
                $day = $festive->getDate();
                array_push($days, $day->format('Y-m-d'));
            }

            //Para el caso de SUPERVISOR para poner en el panel
            $num_petitions = count($this->getPendingPetitions->getPendingPetitions());

            if ($form->isSubmitted() && $form->isValid()) {

                // Cogemos el calendar al que pertenece según las fechas
                $calendar = $this->getCalendarByDates->getCalendarByDates($petition->getInitialDate(), $petition->getFinalDate(), $company);

                if (is_null($calendar)) {
                    return new Response($this->translator->trans('petition.incorrectCalendar'));
                }

                $this->requestVacation->requestVacation($petition, $calendar);

                return $this->redirectToRoute('app_employee_vacation', ['pagVac' => 1, 'pagAbs' => 1]);
            }


            return $this->render('empleado/request_vacation.html.twig', [
                'form' => $form->createView(),
                'festives' => $days,
                'num_petitions' => $num_petitions
            ]);
        } else {
            return $this->render('empleado/request_vacation.html.twig', [
                'festives' => [],
                'num_petitions' => 0
            ]);
        }
    }

}