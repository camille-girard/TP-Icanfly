<?php

namespace App\Controller\Other;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Throwable;

class ExceptionController
{
    private $twig;
    private $kernel;

    public function __construct(Environment $twig, KernelInterface $kernel)
    {
        $this->twig = $twig;
        $this->kernel = $kernel;
    }

    /**
     * @Route("/error", name="error")
     */
    public function show(Throwable $exception): Response
    {
        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
            $template = 404 === $statusCode ? 'bundles/TwigBundle/Exception/error404.html.twig' : 'bundles/TwigBundle/Exception/error403.html.twig';

            $content = $this->twig->render($template, [
                'exception' => $exception,
            ]);
        } else {
            // Autres exceptions (TypeError, RuntimeException, etc.)
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $content = 'Une erreur interne s\'est produite.';
        }

        return new Response($content, $statusCode);
    }
}
