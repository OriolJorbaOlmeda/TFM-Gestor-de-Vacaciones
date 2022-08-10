<?php

namespace App\Controller\User;

use App\Modules\User\Application\ModifyUser;
use App\Modules\User\Infrastucture\Form\SelectUserType;
use App\Modules\User\Infrastucture\Form\UserModificationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModifyUserController extends AbstractController
{

    public function __construct(
        private ModifyUser $modifyUser

    ) {}

    #[Route('/admin/modificar_usuario', name: 'app_admin_modify-user')]
    public function modifyUser(Request $request): Response {
        $departments = $this->getUser()->getDepartment()->getCompany()->getDepartments();

        $choices = [];
        foreach ($departments as $choice) {
            if(!str_contains(strtolower($choice->getName()),'admin')){
                $choices[$choice->getName()] = $choice->getId();
            }
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
        $user = $this->modifyUser->getUserById($userid);

        $form = $this->createForm(UserModificationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $roles = $form->get('roles')->getData();

            $this->modifyUser->__invoke($user, $roles);

            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('admin/modificar_usuario.html.twig', [
            'user' => $user,
            'form' => $form->createView()

        ]);
    }

}