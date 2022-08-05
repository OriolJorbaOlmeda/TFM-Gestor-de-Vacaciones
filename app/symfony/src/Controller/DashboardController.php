<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{

    public function __construct(private SecurityController $security, private UserPasswordHasherInterface $passwordHasher) {}

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


    #[Route('/change_locale/{locale}', name: 'change_locale')]
    public function changeLocale($locale, Request $request)
    {
        $request->getSession()->set('_locale', $locale);

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/change_password', name: 'app_change_password')]
    public function changePassword(): Response {

        $pass = $this->getUser()->getPassword();



        $form = $this->createForm(ChangePasswordType::class, ['pass' => $pass]);

        return $this->render('/security/change_password.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
