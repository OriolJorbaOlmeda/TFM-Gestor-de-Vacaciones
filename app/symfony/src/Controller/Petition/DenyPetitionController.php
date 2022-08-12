<?php

namespace App\Controller\Petition;

use App\Modules\Petition\Application\DenyPetition;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DenyPetitionController extends AbstractController
{
    public function __construct(private DenyPetition $denyPetition){}

    #[Route('/supervisor/deny_request', name: 'app_supervisor_deny_request')]
    public function denyRequest(Request $request): Response
    {
        $petitionId = $request->get('petitionId');

        $this->denyPetition->denyPetition($petitionId);

        return $this->redirectToRoute("app_supervisor_pending_requests");
    }

}