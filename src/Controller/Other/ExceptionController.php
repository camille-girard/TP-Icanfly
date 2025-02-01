<?php

namespace App\Controller\Other;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

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
    public function show(HttpExceptionInterface $exception): Response
    {
        $statusCode = $exception->getStatusCode();
        $template = 404 === $statusCode ? 'bundles/TwigBundle/Exception/error404.html.twig' : 'bundles/TwigBundle/Exception/error403.html.twig';

        $content = $this->twig->render($template, [
            'exception' => $exception,
        ]);

        return new Response($content, $statusCode);
    }
}
