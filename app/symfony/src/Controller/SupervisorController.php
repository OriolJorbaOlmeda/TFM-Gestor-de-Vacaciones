<?php

namespace App\Controller;

use App\Modules\User\Infrastucture\UserRepository;
use App\Repository\PetitionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class SupervisorController extends AbstractController
{
    public function __construct(
        private PetitionRepository $petitionRepository,
        private UserRepository $userRepository
    ) {}

    #[Route('/supervisor/dashboard', name: 'app_supervisor_dashboard')]
    public function dashboard(): Response
    {
        return $this->redirectToRoute("app_employee_dashboard");
    }

    #[Route('/supervisor/pending_requests', name: 'app_supervisor_pending_requests')]
    public function pendingRequests(): Response
    {

        $petitions = $this->petitionRepository->findBy(['supervisor' => $this->getUser(), 'state' => $this->getParameter('pending')]);
        $num_petitions = count($petitions);
        $justify =[];
        foreach ($petitions as $petition) {
            if($petition->getJustify()) {
                $justify [] = $petition->getJustify();
            }

        }

        return $this->render('supervisor/solicitudes_pendientes.html.twig', [
            'petitions' => $petitions,
            'num_petitions' => $num_petitions,
            'justify'=>$justify
        ]);
    }

    #[Route('/supervisor/accept_request', name: 'app_supervisor_accept_request')]
    public function acceptRequest(Request $request): Response
    {

        $petitionId = $request->get('petitionId');
        $petition = $this->petitionRepository->findOneBy(['id' => $petitionId]);
        $petition->setState($this->getParameter('accepted'));
        $this->petitionRepository->add($petition, true);

        $duration = $petition->getDuration();
        $user = $petition->getEmployee();
        $this->userRepository->updateVacationDays($user, $duration);

        return $this->redirectToRoute("app_supervisor_pending_requests");
    }

    #[Route('/supervisor/deny_request', name: 'app_supervisor_deny_request')]
    public function denyRequest(Request $request): Response
    {

        $petitionId = $request->get('petitionId');
        $petition = $this->petitionRepository->findOneBy(['id' => $petitionId]);
        $petition->setState($this->getParameter('denied'));
        $this->petitionRepository->add($petition, true);

        return $this->redirectToRoute("app_supervisor_pending_requests");
    }

    /**
     * @Route("/download/{filename}", name="download_file")
     **/
    public function downloadFileAction(string $filename,
    ){
        $destination = $this->getParameter('documents');
        $response = new BinaryFileResponse($destination.'/'.$filename);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT,$filename);
        return $response;
    }
}
