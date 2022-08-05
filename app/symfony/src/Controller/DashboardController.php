<?php

namespace App\Controller;

use App\Form\CalendarType;
use App\Form\ChangePasswordType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{

    public function __construct(
        private SecurityController $security,
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository $userRepository) {}

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
    public function changePassword(Request $request): Response {

        $form = $this->createForm(ChangePasswordType::class, []);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $newPass = $form->get('new_password')->getData();
            $user = $this->userRepository->findOneBy(['id' => $this->getUser()->getId()]);
            $user->setPassword($this->passwordHasher->hashPassword($user, $newPass));
            $this->userRepository->add($user, true);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('/security/change_password.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
