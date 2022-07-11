<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    private $security;

    public function __construct(SecurityController $security)
    {
        $this->security = $security;
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
        $currentUser = $this->security->getUser();
        if (in_array("ROLE_EMPLEADO", $currentUser->getRoles())) {
            return $this->render('empleado/home.html.twig');
        } elseif (in_array("ROLE_SUPERVISOR", $currentUser->getRoles())) {
            return $this->render('supervisor/panel_info.html.twig'); //pendiente saber el dashboard de supervisor
        }
            return $this->render('admin/home.html.twig');
    }
}
