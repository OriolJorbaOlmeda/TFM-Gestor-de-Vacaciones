<?php

namespace App\Controller;

use App\Entity\Justify;
use App\Entity\Petition;
use App\Form\RequestAbsenceFormType;
use App\Repository\CalendarRepository;
use App\Repository\JustifyRepository;
use App\Repository\PetitionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
    public function requestAbsence(Request $request, CalendarRepository $calendarRepository): Response
    {
        $petition = new Petition();
        $form = $this->createForm(RequestAbsenceFormType::class, $petition);
        $form->handleRequest($request);

        $user = $this->getUser();
        $department = $user->getDepartment();
        $company = $department->getCompany();
        $calendar = $calendarRepository->findCurrentCalendar($company->getId());
        $festives = $calendar->getFestives();
        $days = array();
        foreach ($festives as $festive) {
            $day = $festive->getDate();
            array_push($days, $day->format('Y-m-d'));
        }

        //Para el caso de SUPERVISOR para poner en el panel
        $num_petitions = 0;
        if (in_array("ROLE_SUPERVISOR", $user->getRoles())) {
            $petitions = $this->petitionRepository->findBy(['supervisor' => $user, 'state' => 'PENDING']);
            $num_petitions = count($petitions);
        }

        if ($form->isSubmitted() && $form->isValid()) {

            // Ya están rellenos: initial_date, final_date, duration y reason

            // Rellenar los datos que faltan: state, type, petition_date, employee, calendar y justify
            $petition->setState("PENDING");
            $petition->setType("ABSENCE");

            // si es supervisor el supervisor será el mismo
            if (in_array("ROLE_SUPERVISOR", $this->getUser()->getRoles())) {
                $petition->setSupervisor($this->userRepository->findOneBy(['id' => $this->getUser()->getId()]));
            } else {
                $petition->setSupervisor($this->getUser()->getSupervisor());
            }

            $petition->setPetitionDate(new \DateTime());
            $petition->setEmployee($this->userRepository->findOneBy(['id' => $this->getUser()->getId()]));

            $calendar = $this->calendarRepository->findCalendarByDates($petition->getInitialDate(), $petition->getFinalDate());
            if ($calendar == null) {
                return new Response("Las fechas seleccionadas no corresponden al calendario actual.");
            }
            $petition->setCalendar($calendar);

            $this->petitionRepository->add($petition, true);

            $updatedFile = $form->get('justify_content')->getData();

            if($updatedFile) {
                $originalFilename = $updatedFile->getClientOriginalName();
                $destination = $this->getParameter('documents');
                $RandomAccountNumber = uniqid();
                $fileSaveName = $RandomAccountNumber . '_' . $originalFilename;

                try {
                    $updatedFile->move(
                        $destination,
                        $fileSaveName
                    );
                    $file = $form->get('justify_content')->getData();
                    //$name = $file->getClientOriginalName();

                    $justify = new Justify();
                    $justify->setTitle($fileSaveName);
                    $justify->setContent($file);

                    $petition->setJustify($justify);


                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }
            }

            $this->petitionRepository->add($petition, true);
            return $this->redirectToRoute('app_employee_vacation');

        }

        return $this->render('empleado/solicitar_ausencia.html.twig', [
            'form' => $form->createView(),
            'festives' => $days,
            'num_petitions' => $num_petitions
        ]);

    }
}
