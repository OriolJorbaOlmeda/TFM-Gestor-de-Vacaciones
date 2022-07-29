<?php

namespace App\Controller;

use App\Entity\Petition;
use App\Form\RequestVacationFormType;
use App\Repository\CalendarRepository;
use App\Repository\FestiveRepository;
use App\Repository\PetitionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RequestVacationController extends AbstractController
{

    public function __construct(
        private UserRepository $userRepository,
        private CalendarRepository $calendarRepository,
        private PetitionRepository $petitionRepository) {}

    #[Route('/employee/request-vacation', name: 'app_employee_request-vacation')]
    public function requestVacation(Request $request, CalendarRepository $calendarRepository, FestiveRepository $festiveRepository): Response
    {
        $petition = new Petition();
        $form = $this->createForm(RequestVacationFormType::class, $petition);
        $form->handleRequest($request);

        $user = $this->getUser();
        $department = $user->getDepartment();
        $company = $department->getCompany();
        $calendar = $calendarRepository->findOneBy(['company' => $company]);
        $festives = $calendar->getFestives();
        $days = array();
        foreach ($festives as $festive) {
            $day = $festive->getDate();
            array_push($days, $day->format('Y-m-d'));
        }


        if ($form->isSubmitted() && $form->isValid()) {

            // Ya están rellenos: initial_date, final_date, duration y reason

            // Rellenar los datos que faltan: state, type, petition_date, employee y calendar
            $petition->setState("PENDING");
            $petition->setType("VACATION");
            $petition->setPetitionDate(new \DateTime());
            $petition->setEmployee($this->userRepository->findOneBy(['id' => $this->getUser()->getId()]));
            // quizá haría falta añadir al supervisor

            $calendar = $this->calendarRepository->findCalendarByDates($petition->getInitialDate(), $petition->getFinalDate());
            if ($calendar == null) {
                return new Response("Las fechas seleccionadas no corresponden al calendario actual.");
            }
            $petition->setCalendar($calendar);
            $this->petitionRepository->add($petition, true);
            return $this->redirectToRoute('app_employee_vacation');
        }

        return $this->render('empleado/solicitar_vacaciones.html.twig', [
            'form' => $form->createView(),
            'festives' => $days
        ]);

    }
}