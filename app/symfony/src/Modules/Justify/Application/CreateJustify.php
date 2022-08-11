<?php

namespace App\Modules\Justify\Application;

use App\Entity\Justify;
use App\Entity\Petition;
use App\Modules\Justify\Infrastucture\JustifyRepository;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;

class CreateJustify
{
    public function __construct(
        private JustifyRepository $justifyRepository,
        private ContainerInterface $container) {}

    public function createJustify($updatedFile, Petition $petition)
    {
        if ($updatedFile) {
            $originalFilename = $updatedFile->getClientOriginalName();
            $destination = $this->container->getParameter('documents');
            $RandomAccountNumber = uniqid();
            $fileSaveName = $RandomAccountNumber . '_' . $originalFilename;

            try {
                $updatedFile->move($destination, $fileSaveName);

                $justify = new Justify();
                $justify->setTitle($fileSaveName);
                $justify->setContent($updatedFile);
                $justify->setPetition($petition);

                $this->justifyRepository->add($justify, true);

            } catch (FileException $e) {
                return new Response($e->getMessage());
            }
        }
    }

}