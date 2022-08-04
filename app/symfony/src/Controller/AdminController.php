<?php

namespace App\Controller;

use App\Entity\User;
use App\Events\UserRegistrationEvent;
use App\Form\SelectUserType;
use App\Form\UserModificationType;
use App\Form\UserRegistrationType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    public function __construct(
        private UserRepository $userRepository,
        private EventDispatcherInterface $dispatcher

    ) {}

    #[Route('/admin/crear_usuario', name: 'app_admin_create-user')]
    public function createUser(Request $request, UserPasswordHasherInterface $passwordHasher): Response {

        $user = new User();
        $form = $this->createForm(UserRegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pass = $form->get('password')->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $pass));

            $user->setPendingVacationDays($user->getTotalVacationDays());

            $roles = $form->get('roles')->getData();
            $user->setRoles([$roles]);

            $this->userRepository->add($user, true);

            $this->dispatcher->dispatch(new UserRegistrationEvent($this->getUser()->getEmail(),$user->getEmail(), $user->getName(), $user->getPassword() ));
            return $this->redirectToRoute('app_dashboard');
        }else {
            return $this->render('admin/crear_usuario.html.twig', [
                "form" => $form->createView(),
                "error" => $form->getErrors(),
            ]);
        }
    }


    #[Route('/admin/modificar_usuario', name: 'app_admin_modify-user')]
    public function modifyUser(
        Request $request,
    ): Response {
        $departments = $this->getUser()->getDepartment()->getCompany()->getDepartments();

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
            'depar' => $form->createView(),
            'departments' => $departments

        ]);
    }


    #[Route('/admin/modificar_usuario/{userid}', name: 'app_admin_edit-user')]
    public function editUser(string $userid, Request $request): Response
    {
        $user = $this->userRepository->findOneBy(array('id' => $userid));
        $form = $this->createForm(UserModificationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $roles = $form->get('roles')->getData();
            $user->setRoles([$roles]);

            $this->userRepository->add($user, true);
            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('admin/modificar_usuario.html.twig', [
            'user' => $user,
            'form' => $form->createView()

        ]);
    }

    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('admin/home.html.twig');
    }
    

    #[Route('/admin/getUsers', name: 'app_admin_prueba')]
    public function getUsers(Request $request): Response
    {
        $departmentId = $request->request->get('id');
        $users = $this->userRepository->findBy(['department' => $departmentId]);
        $result = [];
        foreach ($users as $user) {
            $result[$user->getId()] = $user->getName() . " " . $user->getLastname();

        }

        return new JsonResponse(["users" => $result]);
    }

    #[Route('/admin/get_supervisors', name: 'app_admin_get_supervisors')]
    public function getSupervisors(Request $request): Response
    {
        $departmentId = $request->request->get('department_id');
        $users = $this->userRepository->findBy(['department' => $departmentId]);
        $result = [];
        foreach ($users as $user) {
            if (in_array($this->getParameter('role_supervisor'), $user->getRoles())) {
                $result[$user->getId()] = $user->getName() . " " . $user->getLastname();
            }

        }
        return new JsonResponse(["users" => $result]);
    }
}
