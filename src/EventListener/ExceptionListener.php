<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\{Response, JsonResponse};
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use App\Exception\ExceptionContract\SemanticError;


class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $message = ['error' => $exception->getMessage(), 'error_code' => $exception->getCode()];

        $response = new JsonResponse();
        $response->setContent(json_encode($message, JSON_UNESCAPED_UNICODE));

        if ($exception instanceof HttpExceptionInterface)
        {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        }

        else
        {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse($response);

    }
}