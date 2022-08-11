<?php

namespace App\Modules\Department\Application;

use App\Modules\Department\Infrastucture\DepartmentRepository;

class DeleteDepartment
{

    public function __construct(private DepartmentRepository $departmentRepository){}

    public function deleteDepartment(string $departmentId)
    {
        $department = $this->departmentRepository->findOneBy(['id' => $departmentId]);

        $this->departmentRepository->remove($department, true);

    }

}