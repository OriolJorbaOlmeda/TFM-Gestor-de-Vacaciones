<?php

namespace App\Controller\Petition;

use App\Modules\Petition\Application\GetPendingPetitions;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetPendingPetitionsController extends AbstractController
{
    public function __construct(private GetPendingPetitions $getPendingPetitions) {}

    #[Route('/supervisor/pending_requests', name: 'app_supervisor_pending_requests')]
    public function pendingRequests(): Response
    {
        $petitions = $this->getPendingPetitions->getPendingPetitions();
        $num_petitions = count($petitions);

        return $this->render('supervisor/solicitudes_pendientes.html.twig', [
            'petitions' => $petitions,
            'num_petitions' => $num_petitions
        ]);
    }

}
