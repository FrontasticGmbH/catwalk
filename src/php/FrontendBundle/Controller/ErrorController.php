<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Cell;
use Frontastic\Catwalk\FrontendBundle\Domain\MasterService;
use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\Page;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\Region;
use Frontastic\Catwalk\FrontendBundle\Domain\Tastic;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects) Due to node faking. Keep
 * this class really concise to avoid errors in error handling.
 */
class ErrorController extends Controller
{
    private $errorTexts = [
        'en_GB' => "# Internal Server Error\n\n" .
            "Sorry – we didn't even manage to show you the error page. " .
            "Errors like this are logged and we will try to fix this as soon as possible. " .
            "Going back to the [start page](/) might help.",
        'de_DE' => "# Interner Server-Fehler\n\n" .
            "Es tut uns leid – wir haben es nichtal geschafft die normale Fehlerseite anzuzeigen. " .
            "Fehler wie dieser werden geloggt und wir werden versuchen diesen Fehler schnellstmöglich zu beheben. " .
            "Es kann eventuell helfen auf die [Startseite](/) zurück zu kehren.",
    ];

    private function getExceptionTastic(FlattenException $exception): Tastic
    {
        return new Tastic([
            'tasticId' => '1',
            'tasticType' => 'markdown',
            'configuration' => new Tastic\Configuration([
                'text' => "# Exception\n" .
                    "\n⚠ `". $exception->getMessage() . "`\n" .
                    "\n```\n" . implode(
                        "\n",
                        array_map(
                            function (array $traceLine): string {
                                return $traceLine['file'] . ' +' . $traceLine['line'];
                            },
                            $exception->getTrace()
                        )
                    ) . "\n```\n",
            ]),
        ]);
    }

    public function errorAction(Context $context, FlattenException $exception = null)
    {
        // Do not log a FlattenException this way – it has different methods
        // syslog(LOG_ERR, $exception->getMessage() . PHP_EOL . $exception->getTraceAsString());

        $masterService = $this->get(MasterService::class);
        $nodeService = $this->get(NodeService::class);
        $dataProvider = $this->get(ViewDataProvider::class);
        $pageService = $this->get(PageService::class);

        try {
            $node = $nodeService->get(
                $masterService->matchNodeId(new PageMatcherContext([
                    'error' => !$exception ? true : (object) [
                        'message' => $exception->getMessage(),
                        'code' => $exception->getCode(),
                    ]
                ]))
            );
            $page = $pageService->fetchForNode($node, $context);

            if (!$context->isProduction()) {
                array_push(
                    $page->regions['head']->elements[0]->tastics,
                    $this->getExceptionTastic($exception)
                );
            }

            return [
                'node' => $node,
                'page' => $page,
                'data' => $dataProvider->fetchDataFor($node, $context, [], $page),
            ];
        } catch (\Throwable $e) {
            return [
                'node' => new Node(),
                'page' => new Page([
                    'layoutId' => 'three_rows',
                    'regions' => [
                        'main' => new Region([
                            'regionId' => 'main',
                            'elements' => [
                                new Cell([
                                    'cellId' => '1',
                                    'tastics' => array_filter([
                                        new Tastic([
                                            'tasticId' => '1',
                                            'tasticType' => 'markdown',
                                            'configuration' => new Tastic\Configuration([
                                                'text' => $this->errorTexts,
                                            ]),
                                        ]),
                                        $context->isProduction() ? null : $this->getExceptionTastic(FlattenException::createFromThrowable($e)),
                                    ]),
                                ]),
                            ],
                        ]),
                    ]
                ]),
                'data' => (object) [
                    'stream' => (object) [],
                    'tastic' => (object) [],
                ],
            ];
        }
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

        file_put_contents(
            $this->getParameter('kernel.logs_dir') . '/javascript.log',
            json_encode($error) . PHP_EOL,
            FILE_APPEND
        );

        return new JsonResponse($error);
    }
}
