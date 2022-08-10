<?php

namespace App\Controller\User;

use App\Form\ChangePasswordType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChangePasswordController extends AbstractController
{

    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

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