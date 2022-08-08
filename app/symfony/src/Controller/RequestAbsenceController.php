<?php

namespace App\Controller;

use App\Entity\Justify;
use App\Entity\Petition;
use App\Form\RequestAbsenceFormType;
use App\Repository\CalendarRepository;
use App\Repository\PetitionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RequestAbsenceController extends AbstractController
{

    public function __construct(
        private UserRepository $userRepository,
        private CalendarRepository $calendarRepository,
        private PetitionRepository $petitionRepository,
        private TranslatorInterface $translator
    ){}


    #[Route('/employee/request-absence', name: 'app_employee_request-absence')]
    public function requestAbsence(Request $request): Response
    {
        $petition = new Petition();
        $form = $this->createForm(RequestAbsenceFormType::class, $petition);
        $form->handleRequest($request);

        $company = $this->getUser()->getDepartment()->getCompany();
        $calendar = $this->calendarRepository->findCurrentCalendar($company->getId());
        if(!is_null($calendar)) {
            $festives = $calendar->getFestives();
            $days = array();
            foreach ($festives as $festive) {
                $day = $festive->getDate();
                array_push($days, $day->format('Y-m-d'));
            }

            //Para el caso de SUPERVISOR para poner en el panel
            $num_petitions = 0;
            if (in_array($this->getParameter('role_supervisor'), $this->getUser()->getRoles())) {
                $petitions = $this->petitionRepository->findBy(
                    ['supervisor' => $this->getUser(), 'state' => $this->getParameter('pending')]
                );
                $num_petitions = count($petitions);
            }

            if ($form->isSubmitted() && $form->isValid()) {
                // Ya están rellenos: initial_date, final_date, duration y reason

                // Rellenar los datos que faltan: state, type, petition_date, employee, calendar, justify y supervisor
                $petition->setType($this->getParameter('absence'));

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

                $updatedFile = $form->get('justify_content')->getData();

                if ($updatedFile) {
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

                        $justify = new Justify();
                        $justify->setTitle($fileSaveName);
                        $justify->setContent($file);

                        $petition->setJustify($justify);
                    } catch (FileException $e) {
                        return new Response($e->getMessage());
                    }
                }

                $this->petitionRepository->add($petition, true);
                return $this->redirectToRoute('app_employee_vacation', ['pagVac' => 1, 'pagAbs' => 1]);
            }

            return $this->render('empleado/solicitar_ausencia.html.twig', [
                'form' => $form->createView(),
                'festives' => $days,
                'num_petitions' => $num_petitions
            ]);
        }else{
            return $this->render('empleado/solicitar_ausencia.html.twig', [
                'festives' => 0,
                'num_petitions' => 0
            ]);
        }

    }
}
