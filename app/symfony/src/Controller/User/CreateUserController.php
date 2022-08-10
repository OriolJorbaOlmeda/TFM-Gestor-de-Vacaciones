<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Events\UserRegistrationEvent;
use App\Form\UserRegistrationType;
use App\Modules\User\Infrastucture\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateUserController extends AbstractController
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
            //$pass = $form->get('password')->getData();
            $password = $this->randomPassword();
            $user->setPassword($passwordHasher->hashPassword($user, $password));
            $user->setPendingVacationDays($user->getTotalVacationDays());

            $roles = $form->get('roles')->getData();
            $user->setRoles([$roles]);

            $this->userRepository->add($user, true);

            $this->dispatcher->dispatch(new UserRegistrationEvent($this->getUser()->getEmail(),$user->getEmail(), $user->getName(), $password ));
            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('admin/crear_usuario.html.twig', [
            "form" => $form->createView(),
            "error" => $form->getErrors(),
        ]);

    }


    function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

}