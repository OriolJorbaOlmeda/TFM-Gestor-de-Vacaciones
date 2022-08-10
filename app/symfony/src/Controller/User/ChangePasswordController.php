<?php

namespace App\Controller\User;

use App\Form\ChangePasswordType;
use App\Modules\User\Application\ChangePassword;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChangePasswordController extends AbstractController
{

    public function __construct(
        private ChangePassword $changePassword
    ) {}

    #[Route('/change_password', name: 'app_change_password')]
    public function changePassword(Request $request): Response {

        $form = $this->createForm(ChangePasswordType::class, []);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $newPass = $form->get('new_password')->getData();

            $this->changePassword->__invoke($newPass);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('/security/change_password.html.twig', [
            'form' => $form->createView()
        ]);

    }
}