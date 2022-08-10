<?php

namespace App\Controller;

use App\Modules\User\Infrastucture\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    public function __construct(
        private UserRepository $userRepository

    ) {}


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
            if($user->getRoles()[0]!='ROLE_ADMIN') {
                $result[$user->getId()] = $user->getName() . " " . $user->getLastname();
            }

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
