<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Department;
use App\Form\CompanyType;
use App\Form\CreateAdminType;
use App\Form\DepartmentType;
use App\Repository\CompanyRepository;
use App\Repository\DepartmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyRegistrationController extends AbstractController
{

    public function __construct(
        private CompanyRepository $companyRepository,
        private DepartmentRepository $departmentRepository
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

    #[Route('/register_company/admin', name: 'app_register_company_admin')]
    public function registerCompanyAdmin(Request $request): Response
    {
        $form = $this->createForm(CreateAdminType::class, []);
        $form->handleRequest($request);


        return $this->render('registro_empresa/registrar_admin.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
