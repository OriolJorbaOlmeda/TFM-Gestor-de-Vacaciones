<?php

namespace App\Controller\Petition;

use App\Modules\Petition\Application\DeletePetition;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeletePetitionController extends AbstractController
{
    public function __construct(private DeletePetition $deletePetition){}

    #[Route('/employee/delete_petition', name: 'app_employee_delete_petition')]
    public function deletePetition(Request $request): Response
    {
        $petitionId = $request->get('petitionId');
        $pagVac = $request->get('pagVac');
        $pagAbs = $request->get('pagAbs');

        $this->deletePetition->deletePetition($petitionId);

        return $this->redirectToRoute('app_employee_vacation', ['pagVac' => $pagVac, 'pagAbs' => $pagAbs]);
    }


}