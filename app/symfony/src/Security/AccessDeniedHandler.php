<?php

namespace App\Security;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{


    protected $twig;
    public function __construct( Environment $twig)
    {
        $this->twig = $twig;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
    {

        $response = new Response($this->twig->render(
            'security/error_access.html.twig'));
        return $response;
    }



}