<?php

namespace App\Controller\User;

use App\Modules\User\Application\SearchUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserAPIController extends AbstractController
{

    public function __construct(
        private SearchUser $searchUser
    ) {}


    #[Route('/getEmployees', name: 'app_get_employees')]
    public function getEmployees(Request $request): Response
    {
        $departmentId = $request->request->get('id');

        $result = $this->searchUser->searchEmployeesByDepartment($departmentId);

        return new JsonResponse(["users" => $result]);
    }

    #[Route('/getSupervisors', name: 'app_get_supervisors')]
    public function getSupervisors(Request $request): Response
    {
        $departmentId = $request->request->get('department_id');

        $result = $this->searchUser->searchSupervisorsByDepartment($departmentId);

        return new JsonResponse(["users" => $result]);
    }

    #[Route('/getUsers', name: 'app_get_users')]
    public function getUsers(Request $request): Response
    {
        $departmentId = $request->request->get('id');

        $result = $this->searchUser->searchUsersByDepartment($departmentId);

        return new JsonResponse(["users" => $result]);
    }
}
