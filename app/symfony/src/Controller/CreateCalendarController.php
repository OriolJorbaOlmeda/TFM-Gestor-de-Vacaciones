<?php

namespace App\Controller;

use App\Entity\Festive;
use App\Modules\Festive\Infrastucture\Form\FestiveType;
use App\Modules\Calendar\Infrastucture\CalendarRepository;
use App\Modules\Festive\Infrastucture\FestiveRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateCalendarController extends AbstractController
{

    public function __construct(
        private CalendarRepository $calendarRepository,
        private FestiveRepository $festiveRepository
    ) {}


    #[Route('/admin/create_festives/{calendarId}', name: 'app_create_festives')]
    public function createFestives(string $calendarId, Request $request): Response
    {
        $calendar = $this->calendarRepository->findOneBy(['id' => $calendarId]);

        $festive = new Festive();
        $form = $this->createForm(FestiveType::class, $festive);
        $form->handleRequest($request);

        if ($form->get('addFestive')->isClicked()) {
            $calendar->addFestive($festive);
            $this->calendarRepository->add($calendar);

            return $this->redirectToRoute('app_create_festives', ['calendarId' => $calendar->getId()]);

        }

        return $this->render('admin/crear_festivos.html.twig', [
            'calendar' => $calendar,
            'form' => $form->createView(),
            'festives' => $calendar->getFestives()
        ]);
    }


    #[Route('/admin/delete_festive', name: 'app_delete_festive')]
    public function deleteFestive(Request $request): Response
    {
        $calendarId = $request->get('calendarId');
        $festiveId = $request->get('festiveId');

        $calendar = $this->calendarRepository->findOneBy(['id' => $calendarId]);
        $festive = $this->festiveRepository->findOneBy(['id' => $festiveId]);

        $calendar->removeFestive($festive);
        $this->calendarRepository->add($calendar);

        return $this->redirectToRoute('app_create_festives', ['calendarId' => $calendar->getId()]);

    }
}
