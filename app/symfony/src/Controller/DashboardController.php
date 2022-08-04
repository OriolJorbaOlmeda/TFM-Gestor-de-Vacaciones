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
        $currentUser = $this->security->getUser();
        if (in_array($this->getParameter('role_employee'), $currentUser->getRoles())) {
            return $this->redirectToRoute('app_employee_dashboard');
        } elseif (in_array($this->getParameter('role_supervisor'), $currentUser->getRoles())) {
            return $this->redirectToRoute('app_supervisor_dashboard');
        }
            return $this->redirectToRoute('app_admin_dashboard');
    }
}
