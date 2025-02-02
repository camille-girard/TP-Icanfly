<?php

namespace App\Controller\Other;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Throwable;

class ExceptionController
{
    public function __construct(
        private Environment $twig,
        private KernelInterface $kernel,
    ) {
    }

    /**
     * @throws \Throwable
     */
    #[Route(path: '/error', name: 'error')]
    public function show(\Throwable $exception): Response
    {
        if (!$exception instanceof HttpException) {
            throw $exception;
        }

        $statusCode = $exception->getStatusCode();

        if (!in_array($statusCode, [403, 404])) {
            throw $exception;
        }

        try {
            $template = sprintf('bundles/TwigBundle/Exception/error%d.html.twig', $statusCode);
            $content = $this->twig->render($template, [
                'exception' => $exception,
                'status_code' => $statusCode,
            ]);

            return new Response($content, $statusCode);
        } catch (\Throwable $e) {
            throw $exception;
        }
    }
}
