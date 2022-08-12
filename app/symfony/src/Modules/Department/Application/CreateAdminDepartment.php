<?php

namespace App\Modules\Department\Application;

use App\Entity\Department;

class CreateAdminDepartment
{

    public function createAdminDepartment(): Department
    {
        // Le creamos el departamento de administradores por defecto
        $department = new Department();
        $department->setName("Admin department");
        $department->setCode("DEP-01");
        return $department;

    }
}