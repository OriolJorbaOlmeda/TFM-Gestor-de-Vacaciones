<?php

namespace App\Controller\Shared;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class DashboardController extends AbstractController
{

    public function __construct(private Security $security) {}

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

    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function dashboardAdmin(): Response
    {
        return $this->render('admin/home.html.twig');
    }

    #[Route('/supervisor/dashboard', name: 'app_supervisor_dashboard')]
    public function dashboardSupervisor(): Response
    {
        return $this->redirectToRoute("app_employee_dashboard");
    }


    #[Route('/change_locale/{locale}', name: 'change_locale')]
    public function changeLocale($locale, Request $request)
    {
        $request->getSession()->set('_locale', $locale);

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/home', name: 'app_home')]
    public function home(Request $request): Response
    {
        return $this->render('home.html.twig');

    }

}
