<?php

namespace App\Modules\Company\Application;

use App\Modules\Company\Infrastucture\CompanyRepository;
use App\Modules\Department\Application\CreateAdminDepartment;

class CreateCompany
{
    public function __construct(
        private CompanyRepository $companyRepository,
        private CreateAdminDepartment $createAdminDepartment
    ){}

    public function createCompany($company)
    {
        $department = $this->createAdminDepartment->createAdminDepartment();
        $company->addDepartment($department);

        $this->companyRepository->add($company, true);

    }

}