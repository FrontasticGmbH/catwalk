<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\FrontendBundle\EventListener\ErrorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ErrorController extends AbstractController
{
    /**
     * Action for displaying an error page in development.
     * The actual displaying is being done in the ErrorHandler these days
     *
     * @see ErrorHandler
     */
    public function errorAction(Request $request)
    {
        $code = $this->getStatusCodeForException($request->attributes->get('exception'));

        // We just throw an exception in here, because our ErrorHandler takes care of the rest :parrot:
        throw new HttpException(
            $code,
            'Error page was triggered for frontend error'
        );
    }

    public function recordFrontendErrorAction(Request $request): JsonResponse
    {
        // Try to keep this method as resilient as possible, which also means
        // to keep the amount of dependencies as minimal as possible.
        //
        // @TODO: It would be really nice to use the source maps to show the
        // real error location.
        $error = json_decode($request->getContent());
        if (!$error) {
            return new JsonResponse(false);
        }

        $error->time = date('r');
        $error->stack = array_slice(
            array_map('trim', preg_split('(\r|\n|\r\n)', $error->stack ?? '')),
            1
        );
        $error->browser = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown Browser';
        $error->clientIp = $request->getClientIp();

        // We could load the session key from the config but skipped that to avoid adding more dependencies here
        $error->sessionId = $_COOKIE['FCSESSID0815'];

        file_put_contents(
            $this->getParameter('kernel.logs_dir') . '/javascript.log',
            json_encode($error) . PHP_EOL,
            FILE_APPEND
        );

        return new JsonResponse();
    }

    /**
     * @param \Throwable|FlattenException|null $exception
     */
    private function getStatusCodeForException($exception): int
    {
        if ($exception instanceof FlattenException) {
            return $exception->getStatusCode() ?? 500;
        }

        if ($exception instanceof HttpExceptionInterface) {
            return $exception->getStatusCode();
        }

        // We don't have an error at all, so we should not send a server error
        // (e.g. frontend had an error and loaded error master page)
        if (!$exception) {
            return 200;
        }

        return 500;
    }
}
