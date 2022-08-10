<?php

namespace App\Modules\Company\Application;

use App\Entity\Department;
use App\Modules\Company\Infrastucture\CompanyRepository;

class CreateCompany
{

    public function __construct(
        private CompanyRepository $companyRepository
    ){}

    public function createCompany($company) {

        // Le creamos el departamento de administradores por defecto
        $department = new Department();
        $department->setName("Admin department");
        $department->setCode("DEP-01");
        $company->addDepartment($department);

        $this->companyRepository->add($company, true);

    }

}