<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Department;
use App\Entity\User;
use App\Form\CompanyType;
use App\Form\CreateAdminType;
use App\Form\DepartmentType;
use App\Repository\CompanyRepository;
use App\Repository\DepartmentRepository;
use App\Repository\UserRepository;
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

    #[Route('/register_company', name: 'app_register_company')]
    public function registerCompany(Request $request): Response
    {

        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Le creamos el departamento de administradores por defecto
            $department = new Department();
            $department->setName("Admin department");
            $department->setCode("DEP-01");
            $company->addDepartment($department);

            $this->companyRepository->add($company, true);

            return $this->redirectToRoute('app_register_company_departments', ['companyId' => $company->getId()]);
        }

        return $this->render('registro_empresa/registrar_empresa.html.twig', [
            "form" => $form->createView()
        ]);

    }

    #[Route('/register_company/departments/{companyId}', name: 'app_register_company_departments')]
    public function registerCompanyDepartments(string $companyId, Request $request): Response
    {
        $company = $this->companyRepository->findOneBy(['id' => $companyId]);

        $department = new Department();
        $form = $this->createForm(DepartmentType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $company->addDepartment($department);
            $this->companyRepository->add($company, true);

            return $this->redirectToRoute('app_register_company_departments', ['companyId' => $companyId]);
        }

        return $this->render('registro_empresa/registrar_departamentos.html.twig', [
            'form' => $form->createView(),
            'companyId' => $companyId,
            'departments' => $company->getDepartments()
        ]);

    }


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
        $form = $this->createForm(CreateAdminType::class, $user,[
            'csrf_protection' => false,
        ]);
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
