<?php

namespace App\Controller\Petition;

use App\Modules\Petition\Application\AcceptPetition;
use App\Modules\User\Application\UpdateVacationDays;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcceptPetitionController extends AbstractController
{
    public function __construct(
        private AcceptPetition $acceptPetition,
        private UpdateVacationDays $updateVacationDays
    ){}

    #[Route('/supervisor/accept_request', name: 'app_supervisor_accept_request')]
    public function acceptRequest(Request $request): Response
    {
        $petitionId = $request->get('petitionId');

        $petition = $this->acceptPetition->acceptPetition($petitionId);

        $this->updateVacationDays->updateVacationDays($petition->getEmployee(), $petition->getDuration());

        return $this->redirectToRoute("app_supervisor_pending_requests");
    }



}