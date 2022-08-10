<?php

namespace App\Controller\Company;

use App\Entity\Company;
use App\Modules\Company\Application\CreateCompany;
use App\Modules\Company\Infrastucture\Form\CompanyType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateCompanyController extends AbstractController
{

    public function __construct(
        private CreateCompany $createCompany
    ){}

    #[Route('/register_company', name: 'app_register_company')]
    public function registerCompany(Request $request): Response
    {

        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->createCompany->createCompany($company);

            return $this->redirectToRoute('app_register_company_departments', ['companyId' => $company->getId()]);
        }

        return $this->render('registro_empresa/registrar_empresa.html.twig', [
            "form" => $form->createView()
        ]);

    }

}