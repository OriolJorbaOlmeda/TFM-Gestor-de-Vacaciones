<?php

namespace App\Controller\Festive;

use App\Entity\Festive;
use App\Modules\Calendar\Application\GetCalendarById;
use App\Modules\Festive\Application\CreateFestive;
use App\Modules\Festive\Infrastucture\Form\FestiveType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateFestiveController extends AbstractController
{
    public function __construct(
        private CreateFestive $createFestive,
        private GetCalendarById $getCalendarById
    ){}

    #[Route('/admin/create_festives/{calendarId}', name: 'app_create_festives')]
    public function createFestives(string $calendarId, Request $request): Response
    {
        $calendar = $this->getCalendarById->getCalendarById($calendarId);

        $festive = new Festive();
        $form = $this->createForm(FestiveType::class, $festive);
        $form->handleRequest($request);

        if ($form->get('addFestive')->isClicked()) {

            $this->createFestive->createFestive($festive, $calendar);

            return $this->redirectToRoute('app_create_festives', ['calendarId' => $calendar->getId()]);

        }

        return $this->render('admin/create_festives.html.twig', [
            'calendar' => $calendar,
            'form' => $form->createView(),
            'festives' => $calendar->getFestives()
        ]);
    }
}