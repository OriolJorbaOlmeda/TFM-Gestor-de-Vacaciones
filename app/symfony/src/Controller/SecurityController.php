<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         /*if ($this->getUser()) {
             return $this->redirectToRoute('app_dashboard');
         }*/

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }



    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): Response
    {
        return $this->redirectToRoute('app_dashboard');
        //throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
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
