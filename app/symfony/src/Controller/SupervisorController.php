<?php

namespace App\Controller;

use App\Modules\Petition\Infrastucture\PetitionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class SupervisorController extends AbstractController
{
    public function __construct(
        private PetitionRepository $petitionRepository
    ) {}


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
