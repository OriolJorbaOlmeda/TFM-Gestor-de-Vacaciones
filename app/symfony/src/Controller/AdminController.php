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
use Symfony\Component\Security\Core\Security;

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
            $pending_vacation_days = $form->get('total_vacation_days')->getData();
            $roles = $form->get('roles')->getData();
            $user->setRoles([$roles]);

            $user->setPendingVacationDays($pending_vacation_days);
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
    public function modifyUser(
        Request $request,
        UserRepository $userRepository,
        Security $security,
        DepartmentRepository $departmentRepository
    ): Response {
        ob_start();

        $actualUser = $userRepository->findOneBy(array('email' => 'mireia16@hotmail.com'));
        $departments = $departmentRepository->findBy(array('company' => '1'));
        $selectedDepartment = $request->get('department');
        print_r($selectedDepartment);
        $users = $userRepository->findBy(array('department' => '2'));
        if (isset($_POST['submit'])) {
            if (isset($_POST['department']) && isset($_POST['user'])) {
                $userid = $_POST['user'];
                return $this->redirectToRoute('app_admin_edit-user', ['userid' => $userid]);
            }
        }

        return $this->render('admin/modificar_usuario.html.twig', [
            'controller_name' => 'AdminController',
            'users' => $users,
            'departments' => $departments

        ]);
    }

    #[Route('/admin/modificar_usuario/{userid}', name: 'app_admin_edit-user')]
    public function editUser(
        string $userid,
        Request $request,
        UserRepository $userRepository,
        Security $security,
        DepartmentRepository $departmentRepository,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = $userRepository->findOneBy(array('id' => $userid));
        $form = $this->createForm(UserRegistrationType::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pass = $form->get('password')->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $pass));
            $pending_vacation_days = $form->get('total_vacation_days')->getData();
            $roles = $form->get('roles')->getData();
            $user->setRoles([$roles]);

            $user->setPendingVacationDays($pending_vacation_days);
            $userRepository->add($user, true);
            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('admin/modificar_usuario.html.twig', [
            'controller_name' => 'AdminController',
            'user' => $user,
            'form' => $form->createView()

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
