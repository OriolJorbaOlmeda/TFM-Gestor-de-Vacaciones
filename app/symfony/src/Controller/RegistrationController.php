<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegistrationType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{

    public function __construct(private UserRepository $userRepository) {}

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
            return $this->redirectToRoute('app_dashboard');
        }else {
            return $this->render('admin/crear_usuario.html.twig', [
                "form" => $form->createView(),
                "error" => $form->getErrors(),
            ]);
        }
    }


    #[Route('/admin/get_supervisors', name: 'app_admin_get_supervisors')]
    public function getSupervisors(Request $request): Response
    {
        $departmentId = $request->request->get('department_id');
        $users = $this->userRepository->findBy(['department' => $departmentId]);
        $result = [];
        foreach ($users as $user) {
            if (in_array("ROLE_SUPERVISOR", $user->getRoles())) {
                $result[$user->getId()] = $user->getName() . " " . $user->getLastname();
            }

        }
        return new JsonResponse(["users" => $result]);
    }

}
