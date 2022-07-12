<?php

namespace App\Controller;

use App\Entity\Calendar;
use App\Entity\Festive;
use App\Form\CalendarType;

use App\Form\FestiveType;
use App\Repository\CalendarRepository;
use App\Repository\CompanyRepository;
use App\Repository\FestiveRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateCalendarController extends AbstractController
{

    public function __construct(
        private CompanyRepository $companyRepository,
        private CalendarRepository $calendarRepository,
        private FestiveRepository $festiveRepository
    ) {
    }

    #[Route('/admin/create_calendar', name: 'app_create_calendar')]
    public function createCalendar(Request $request, EntityManagerInterface $entityManager): Response
    {
        $calendar = new Calendar();
        $festive = new Festive();

        $formCalendar = $this->createForm(CalendarType::class, $calendar);
        $formCalendar->handleRequest($request);

        if ($formCalendar->get('festives')['date']->getData() != "") {
            if ($formCalendar->get('festives')->get('addFestive')->isSubmitted() && $formCalendar->get('festives')->get(
                    'addFestive'
                )->isValid()) {
                $date = $formCalendar->get('festives')['date']->getData();
                $name = $formCalendar->get('festives')['name']->getData();
                $festive->setDate($date);
                $festive->setName($name);
                $this->festiveRepository->add($festive, true);
            }
        }
        if ($formCalendar->isSubmitted() && $formCalendar->isValid()) {
            $calendar = new Calendar();


            $calendar->setCompany($this->companyRepository->findOneBy(['id' => 1]));
            $initial_date = $formCalendar['initial_date']->getData();
            $year = $formCalendar['year']->getData();

            $final_date = $formCalendar['final_date']->getData();
            $calendar->setInitialDate($initial_date);
            $calendar->setFinalDate($final_date);
            $calendar->setYear($year);
            $this->calendarRepository->add($calendar, true);
            $festive->addCalendar($calendar);
            //return $this->redirectToRoute('app_login');

        }


        return $this->render('admin/crear_calendario.html.twig', [
            'formCalendar' => $formCalendar->createView(),

        ]);
    }

    #[Route('/create-festive', name: 'app_create_festive', options: ['expose' => true])]
    public function createFesByAjax(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            var_dump($request->request);
            $desc = $request->request->get('desc');
            $date = $request->request->get('date');
            var_dump($desc);
        }
    }



    /*#[Route('/admin/create_calendar_festives/{calendar}', name: 'app_create_calendar_festives')]
    public function createCalendarFestives($calendar, Request $request): Response
    {
        $festive = new Festive();
        $formFestive = $this->createForm(FestiveType::class, $festive);
        $formFestive->handleRequest($request);

        return new Response($calendar);

        /*if ($formFestive->isSubmitted() && $formFestive->isValid()) {
            // setear los campos que faltan



            //$this->finalCalendar->addFestive($festive);
            //$this->calendarRepository->add($this->finalCalendar);

            return new Response("OK");
        }


        return $this->render('admin/crear_calendario2.html.twig', [
            'formFestive' => $formFestive->createView()
        ]);*/
    /*}*/


    /*#[Route('/create-festive', name: 'app_new_festive')]
    public function createCategory(Request $request): Response {
        //Added line for getting the output of ajax/json post.

        //print_r($request->getContent());

        $data = json_decode($request->getContent(), true);

        $date = $data['date'];
        $desc = $data['desc'];


        $festive = new Festive();
        $festive->setName($desc);
        $festive->setDate(new \DateTime("now"));
        print_r($festive);
        $this->festiveRepository->add($festive);

        //echo "<script>console.log('Console: " . $output . "' );</script>";
        return new Response("OK");
    }*/
}
