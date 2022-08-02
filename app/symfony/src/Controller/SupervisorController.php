<?php

namespace App\Controller;

use App\Repository\PetitionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SupervisorController extends AbstractController
{
    public function __construct(private PetitionRepository $petitionRepository) {}

    #[Route('/supervisor/dashboard', name: 'app_supervisor_dashboard')]
    public function dashboard(): Response
    {
        return $this->redirectToRoute("app_employee_dashboard");
    }

    #[Route('/supervisor/pending_requests', name: 'app_supervisor_pending_requests')]
    public function pendingRequests(): Response
    {

        $petitions = $this->petitionRepository->findBy(['supervisor' => $this->getUser(), 'state' => 'PENDING']);
        $num_petitions = count($petitions);

        return $this->render('supervisor/solicitudes_pendientes.html.twig', [
            'petitions' => $petitions,
            'num_petitions' => $num_petitions
        ]);
    }

    #[Route('/supervisor/accept_request', name: 'app_supervisor_accept_request')]
    public function acceptRequest(Request $request): Response
    {

        $petitionId = $request->get('petitionId');
        $petition = $this->petitionRepository->findOneBy(['id' => $petitionId]);
        $petition->setState("ACCEPTED");
        $this->petitionRepository->add($petition, true);

        return $this->redirectToRoute("app_supervisor_pending_requests");
    }

    #[Route('/supervisor/deny_request', name: 'app_supervisor_deny_request')]
    public function denyRequest(Request $request): Response
    {

        $petitionId = $request->get('petitionId');
        $petition = $this->petitionRepository->findOneBy(['id' => $petitionId]);
        $petition->setState("DENIED");
        $this->petitionRepository->add($petition, true);

        return $this->redirectToRoute("app_supervisor_pending_requests");
    }
}
