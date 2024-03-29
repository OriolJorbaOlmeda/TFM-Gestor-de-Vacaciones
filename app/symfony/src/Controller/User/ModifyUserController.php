<?php

namespace App\Controller\User;

use App\Modules\User\Application\ModifyUser;
use App\Modules\User\Application\SearchUser;
use App\Modules\User\Infrastucture\Form\SelectUserType;
use App\Modules\User\Infrastucture\Form\UserModificationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModifyUserController extends AbstractController
{
    public function __construct(
        private ModifyUser $modifyUser,
        private SearchUser $searchUser) {}

    #[Route('/admin/modificar_usuario', name: 'app_admin_modify-user')]
    public function modifyUser(Request $request): Response {

        $form = $this->createForm(SelectUserType::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //mirar si usuario no existe
            $userid = $form->get('user')->getData();
            return $this->redirectToRoute('app_admin_edit-user', ['userid' => $userid]);
        }

        return $this->render('admin/modify_user.html.twig', [
            'depar' => $form->createView()
        ]);
    }


    #[Route('/admin/modificar_usuario/{userid}', name: 'app_admin_edit-user')]
    public function editUser(string $userid, Request $request): Response
    {
        $user = $this->searchUser->searchUserById($userid);
        $form = $this->createForm(UserModificationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $roles = $form->get('roles')->getData();

            $this->modifyUser->modifyUser($user, $roles);

            return $this->redirectToRoute('app_admin_dashboard');
        }

        return $this->render('admin/modify_user.html.twig', [
            'user' => $user,
            'form' => $form->createView()

        ]);
    }

}