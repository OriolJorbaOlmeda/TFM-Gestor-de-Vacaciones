<?php

namespace App\Controller\Department;

use App\Entity\Department;
use App\Modules\Company\Application\GetCompanyById;
use App\Modules\Department\Application\CreateDepartment;
use App\Modules\Department\Infrastucture\Form\DepartmentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateDepartmentController extends AbstractController
{
    public function __construct(
        private CreateDepartment $createDepartment,
        private GetCompanyById $getCompanyById
    ){}

    #[Route('/register_company/departments/{companyId}', name: 'app_register_company_departments')]
    public function registerCompanyDepartments(string $companyId, Request $request): Response
    {
        $company = $this->getCompanyById->getCompanyById($companyId);

        $department = new Department();
        $form = $this->createForm(DepartmentType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->createDepartment->createDepartment($company, $department);

            return $this->redirectToRoute('app_register_company_departments', ['companyId' => $companyId]);
        }

        return $this->render('registro_empresa/register_departments.html.twig', [
            'form' => $form->createView(),
            'companyId' => $companyId,
            'departments' => $company->getDepartments()
        ]);

    }

}