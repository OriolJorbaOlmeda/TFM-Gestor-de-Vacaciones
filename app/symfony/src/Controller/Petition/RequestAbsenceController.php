<?php

namespace App\Controller\Petition;

use App\Entity\Justify;
use App\Entity\Petition;
use App\Modules\Calendar\Application\GetCalendarByDates;
use App\Modules\Calendar\Application\GetCurrentCalendar;
use App\Modules\Petition\Application\GetPendingPetitions;
use App\Modules\Petition\Application\RequestAbsence;
use App\Modules\Petition\Infrastucture\Form\RequestAbsenceFormType;
use App\Modules\Petition\Infrastucture\PetitionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RequestAbsenceController extends AbstractController
{

    public function __construct(
        private PetitionRepository $petitionRepository,
        private TranslatorInterface $translator,
        private GetPendingPetitions $getPendingPetitions,
        private GetCurrentCalendar $getCurrentCalendar,
        private GetCalendarByDates $getCalendarByDates,
        private RequestAbsence $requestAbsence
    ){}


    #[Route('/employee/request-absence', name: 'app_employee_request-absence')]
    public function requestAbsence(Request $request): Response
    {
        $petition = new Petition();
        $form = $this->createForm(RequestAbsenceFormType::class, $petition);
        $form->handleRequest($request);

        $company = $this->getUser()->getDepartment()->getCompany();
        $calendar = $this->getCurrentCalendar->getCurrentCalendar($company);
        if(!is_null($calendar)) {
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
                $calendar = $this->getCalendarByDates->getCalendarByDates($petition->getInitialDate(), $petition->getFinalDate());

                if ($calendar == null) {
                    return new Response($this->translator->trans('petition.incorrectCalendar'));
                }

                $this->requestAbsence->requestVacation($petition, $calendar);

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

            return $this->render('empleado/request_absence.html.twig', [
                'form' => $form->createView(),
                'festives' => $days,
                'num_petitions' => $num_petitions
            ]);
        }else{
            return $this->render('empleado/request_absence.html.twig', [
                'festives' => 0,
                'num_petitions' => 0
            ]);
        }

    }
}
