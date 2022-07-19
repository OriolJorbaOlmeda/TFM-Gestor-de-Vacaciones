<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegistrationType;
use App\Repository\DepartmentRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/crear_usuario', name: 'app_admin_create-user')]
    public function createUser(Request $request, UserRepository $userRepository, DepartmentRepository $departmentRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserRegistrationType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $pass = $form->get('password')->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $pass));
            $user->setPendingVacationDays(10);
            $userRepository->add($user, true);
            return $this->redirectToRoute('app_dashboard');
        }else {
            return $this->render('admin/crear_usuario.html.twig', [
                'controller_name' => 'AdminController',
                "form" => $form->createView(),
                "error" => $form->getErrors(),
            ]);
        }
    }
    #[Route('/admin/modificar_usuario', name: 'app_admin_modify-user')]
    public function modifyUser(): Response
    {
        return $this->render('admin/modificar_usuario.html.twig', [
            'controller_name' => 'AdminController',

        ]);
    }

    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('admin/home.html.twig');

    }

    #[Route('/admin/calendar', name: 'app_admin_calendar')]
    public function calendar(): Response
    {
        return $this->render('admin/crear_calendario.html.twig');

    }
}
