<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SupervisorController extends AbstractController
{
    #[Route('/supervisor/dashboard', name: 'app_supervisor_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('empleado/home.html.twig');
    }

    #[Route('/supervisor/pending_requests', name: 'app_supervisor_pending_requests')]
    public function pendingRequests(): Response
    {
        return $this->render('supervisor/solicitudes_pendientes.html.twig');
    }
}
