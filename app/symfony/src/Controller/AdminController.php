<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SelectUserType;
use App\Form\UserRegistrationType;
use App\Repository\DepartmentRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AdminController extends AbstractController
{

    public function __construct(private UserRepository $userRepository, private DepartmentRepository $departmentRepository) {}


    #[Route('/admin/modificar_usuario', name: 'app_admin_modify-user')]
    public function modifyUser(
        Request $request,
        Security $security,

    ): Response {
        ob_start();

        $actualUser = $this->userRepository->findOneBy(array('email' => 'mireia16@hotmail.com'));
        $departments = $this->departmentRepository->findBy(array('company' => '1'));
        $choices = [];
        foreach ($departments as $choice) {
            $choices[$choice->getName()] = $choice->getId();
        }

        $form = $this->createForm(SelectUserType::class, $choices);
        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                //mirar si usuario no existe
                $userid = $form->get('user')->getData();
                return $this->redirectToRoute('app_admin_edit-user', ['userid' => $userid]);



        }



        return $this->render('admin/modificar_usuario.html.twig', [
            'controller_name' => 'AdminController',
            'depar' => $form->createView(),
            'departments' => $departments

        ]);
    }


    #[Route('/admin/modificar_usuario/{userid}', name: 'app_admin_edit-user')]
    public function editUser(
        string $userid,
        Request $request,
        Security $security,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = $this->userRepository->findOneBy(array('id' => $userid));
        $form = $this->createForm(UserRegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$pass = $form->get('password')->getData();
            //$user->setPassword($passwordHasher->hashPassword($user, $pass));
            $pending_vacation_days = $form->get('total_vacation_days')->getData();
            $roles = $user->getRoles();
            $pass = $user->getPassword();

            $user->setRoles([$roles[0]]);
            $user->setPassword($pass);


            $user->setPendingVacationDays($pending_vacation_days);
            $this->userRepository->add($user, true);
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

    #[Route('/admin/getUsers', name: 'app_admin_prueba')]
    public function getUsers(Request $request): Response
    {
        $departmentId = $request->request->get('id');
        $users = $this->userRepository->findBy(['department' => $departmentId]);
        $result = [];
        foreach ($users as $user) {
            $result[$user->getId()] = $user->getName();
        }

        return new JsonResponse(["users" => $result]);
    }
}
