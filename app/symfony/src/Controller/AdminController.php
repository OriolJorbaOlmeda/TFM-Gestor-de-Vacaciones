<?php

namespace App\Controller;

use App\Form\UserRegistrationType;
use App\Repository\DepartmentRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/crear_usuario', name: 'app_admin_create-user')]
    public function createUser(Request $request, UserRepository $userRepository, DepartmentRepository $departmentRepository): Response
    {
        $form = $this->createForm(UserRegistrationType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $department = $departmentRepository->findOneBy(['name' => $form->get('departments')->getData()]);
            $data = $form->getData();
            $data->setDepartment($department);
            $userRepository->add($data, true);
            return $this->redirectToRoute($request->getUri());
        }else {
            return $this->render('admin/crear_usuario.html.twig', [
                'controller_name' => 'AdminController',
                "form" => $form->createView(),
                "error" => $form->getErrors(),
            ]);
        }
    }
    #[Route('/admin/modificar_usuario', name: 'app_admin_modifier')]
    public function modifyUser(): Response
    {
        return $this->render('admin/modificar_usuario.html.twig', [
            'controller_name' => 'AdminController',

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
