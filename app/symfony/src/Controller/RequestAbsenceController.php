<?php

namespace App\Controller;

use App\Entity\Justify;
use App\Entity\Petition;
use App\Form\RequestAbsenceFormType;
use App\Repository\CalendarRepository;
use App\Repository\PetitionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RequestAbsenceController extends AbstractController
{

    public function __construct(
        private UserRepository $userRepository,
        private CalendarRepository $calendarRepository,
        private PetitionRepository $petitionRepository){}


    #[Route('/employee/request-absence', name: 'app_employee_request-absence')]
    public function requestAbsence(Request $request): Response
    {
        $petition = new Petition();
        $form = $this->createForm(RequestAbsenceFormType::class, $petition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Ya están rellenos: initial_date, final_date, duration y reason

            // Rellenar los datos que faltan: state, type, petition_date, employee, calendar y justify
            $petition->setState("PENDING");
            $petition->setType("ABSENCE");
            $petition->setPetitionDate(new \DateTime());
            $petition->setEmployee($this->userRepository->findOneBy(['id' => $this->getUser()->getId()]));
            // quizá haría falta añadir al supervisor

            $calendar = $this->calendarRepository->findCalendarByDates($petition->getInitialDate(), $petition->getFinalDate());
            if ($calendar == null) {
                return new Response("Las fechas seleccionadas no corresponden al calendario actual.");
            }
            $petition->setCalendar($calendar);

            $file = $form->get('justify_content')->getData();
            $name = $file->getClientOriginalName();

            $justify = new Justify();
            $justify->setTitle($name);
            $justify->setContent($file);

            $petition->setJustify($justify);

            $this->petitionRepository->add($petition, true);
            return $this->redirectToRoute('app_employee_vacation');

        }

        return $this->render('empleado/solicitar_ausencia.html.twig', [
            'form' => $form->createView()
        ]);

    }
}