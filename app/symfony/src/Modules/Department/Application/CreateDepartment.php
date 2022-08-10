<?php

namespace App\Modules\Department\Application;

use App\Entity\Company;
use App\Entity\Department;
use App\Repository\DepartmentRepository;

class CreateDepartment
{
    public function __construct(private DepartmentRepository $departmentRepository) {}

    public function createDepartment(Company $company, Department $department)
    {
        $department->setCompany($company);
        $this->departmentRepository->add($department, true);

    }

}