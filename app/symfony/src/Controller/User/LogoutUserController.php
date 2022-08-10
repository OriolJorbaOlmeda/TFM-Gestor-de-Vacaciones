<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class LogoutUserController extends AbstractController
{

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): Response
    {
        return $this->redirectToRoute('app_dashboard');
        //throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

}