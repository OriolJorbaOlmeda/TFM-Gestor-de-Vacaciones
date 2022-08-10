<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CreateAdminType;
use App\Modules\User\Infrastucture\UserRepository;
use App\Modules\Company\Infrastucture\CompanyRepository;
use App\Modules\Department\Infrastucture\DepartmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class CompanyRegistrationController extends AbstractController
{

    public function __construct(
        private CompanyRepository $companyRepository,
        private DepartmentRepository $departmentRepository,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    ){}


    #[Route('/register_company/delete_department', name: 'app_delete_department')]
    public function deleteDepartment(Request $request): Response
    {
        $companyId = $request->get('companyId');
        $departmentId = $request->get('departmentId');

        $department = $this->departmentRepository->findOneBy(['id' => $departmentId]);

        $this->departmentRepository->remove($department, true);

        return $this->redirectToRoute('app_register_company_departments', ['companyId' => $companyId]);
    }

    #[Route('/register_company/admin/{companyId}', name: 'app_register_company_admin')]
    public function registerCompanyAdmin(string $companyId, Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(CreateAdminType::class, $user);
        $form->handleRequest($request);

        $company = $this->companyRepository->findOneBy(['id' => $companyId]);
        $adminDepartment = $company->getDepartments()[0];
        
        if ($form->isSubmitted() && $form->isValid()) {

            // Rellenamos lo necesario
            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
            $user->setRoles([$this->getParameter('role_admin')]);
            $user->setDepartment($adminDepartment);

            // Rellenamos los demás datos vacíos
            $user->setName("");
            $user->setLastname("");
            $user->setDirection("");
            $user->setCity("");
            $user->setProvince("");
            $user->setPostalcode("");
            $user->setTotalVacationDays(0);
            $user->setPendingVacationDays(0);

            $this->userRepository->add($user, true);

            return $this->redirectToRoute('app_login');

        }


        return $this->render('registro_empresa/registrar_admin.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
