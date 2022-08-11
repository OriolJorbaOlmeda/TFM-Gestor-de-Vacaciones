<?php

namespace App\Controller\Festive;

use App\Modules\Festive\Application\DeleteFestive;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteFestiveController extends AbstractController
{

    public function __construct(private DeleteFestive $deleteFestive) {}


    #[Route('/admin/delete_festive', name: 'app_delete_festive')]
    public function deleteFestive(Request $request): Response
    {
        $calendarId = $request->get('calendarId');
        $festiveId = $request->get('festiveId');

        $this->deleteFestive->deleteFestive($festiveId);

        return $this->redirectToRoute('app_create_festives', ['calendarId' => $calendarId]);

    }
}
