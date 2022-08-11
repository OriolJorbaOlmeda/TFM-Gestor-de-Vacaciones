<?php

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class JustifyAPIController extends AbstractController
{

    #[Route('/download/{filename}', name: 'download_file')]
    public function downloadFileAction(string $filename,
    ){
        $destination = $this->getParameter('documents');
        $response = new BinaryFileResponse($destination.'/'.$filename);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT,$filename);
        return $response;
    }


}