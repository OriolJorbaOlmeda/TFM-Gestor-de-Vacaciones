<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Modules\Company\Application\GetCompanyById;
use App\Modules\User\Application\CreateCompanyAdmin;
use App\Modules\User\Infrastucture\Form\CreateAdminType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateCompanyAdminController extends AbstractController
{

    public function __construct(
        private CreateCompanyAdmin $createCompanyAdmin,
        private GetCompanyById $getCompanyById
    ) {}

    #[Route('/register_company/admin/{companyId}', name: 'app_register_company_admin')]
    public function registerCompanyAdmin(string $companyId, Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(CreateAdminType::class, $user);
        $form->handleRequest($request);

        $company = $this->getCompanyById->getCompanyById($companyId);
        $adminDepartment = $company->getDepartments()[0];

        if ($form->isSubmitted() && $form->isValid()) {

            $this->createCompanyAdmin->createCompanyAdmin($user, $adminDepartment);

            return $this->redirectToRoute('app_login');

        }

        return $this->render('registro_empresa/registrar_admin.html.twig', [
            'form' => $form->createView()
        ]);
    }
}