<?php

namespace App\Controller\Petition;

use App\Entity\Petition;
use App\Modules\Petition\Application\GetPendingPetitions;
use App\Modules\Petition\Infrastucture\Form\RequestVacationFormType;
use App\Modules\User\Infrastucture\UserRepository;
use App\Modules\Calendar\Infrastucture\CalendarRepository;
use App\Modules\Petition\Infrastucture\PetitionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RequestVacationController extends AbstractController
{

    public function __construct(
        private UserRepository $userRepository,
        private CalendarRepository $calendarRepository,
        private PetitionRepository $petitionRepository,
        private TranslatorInterface $translator,
        private GetPendingPetitions $getPendingPetitions
    ) {}

    #[Route('/employee/request-vacation', name: 'app_employee_request-vacation')]
    public function requestVacation(Request $request): Response
    {
        $petition = new Petition();
        $form = $this->createForm(RequestVacationFormType::class, $petition);
        $form->handleRequest($request);

        $company = $this->getUser()->getDepartment()->getCompany();
        $calendar = $this->calendarRepository->findCurrentCalendar($company->getId());
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
                // Ya están rellenos: initial_date, final_date, duration y reason

                // Rellenar los datos que faltan: state, type, petition_date, employee, calendar y supervisor
                $petition->setType($this->getParameter('vacation'));

                // si es supervisor el supervisor será el mismo
                if (in_array($this->getParameter('role_supervisor'), $this->getUser()->getRoles())) {
                    $user = $this->userRepository->findOneBy(['id' => $this->getUser()->getId()]);
                    $petition->setSupervisor($user);
                    $petition->setState($this->getParameter('accepted'));
                    $duration = $petition->getDuration();
                    $this->userRepository->updateVacationDays($user, $duration);
                } else {
                    $petition->setSupervisor($this->getUser()->getSupervisor());
                    $petition->setState($this->getParameter('pending'));
                }

                $petition->setPetitionDate(new \DateTime());
                $petition->setEmployee($this->userRepository->findOneBy(['id' => $this->getUser()->getId()]));

                $calendar = $this->calendarRepository->findCalendarByDates(
                    $petition->getInitialDate(),
                    $petition->getFinalDate()
                );
                if ($calendar == null) {
                    return new Response($this->translator->trans('petition.incorrectCalendar'));
                }
                $petition->setCalendar($calendar);
                $this->petitionRepository->add($petition, true);
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