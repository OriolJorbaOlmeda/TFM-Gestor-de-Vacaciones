<?php

namespace App\Controller\Department;

use App\Modules\Department\Application\DeleteDepartment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteDepartmentController extends AbstractController
{

    public function __construct(private DeleteDepartment $deleteDepartment){}

    #[Route('/register_company/delete_department', name: 'app_delete_department')]
    public function deleteDepartment(Request $request): Response
    {
        $companyId = $request->get('companyId');
        $departmentId = $request->get('departmentId');

        $this->deleteDepartment->deleteDepartment($departmentId);

        return $this->redirectToRoute('app_register_company_departments', ['companyId' => $companyId]);
    }


}