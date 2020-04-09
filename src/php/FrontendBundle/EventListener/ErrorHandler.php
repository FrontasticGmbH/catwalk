<?php

namespace Frontastic\Catwalk\FrontendBundle\EventListener;

use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
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
use Symfony\Component\Console\EventListener\ErrorListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * This should replace symfonys ErrorListener
 *
 * We do this, because the Error Controller in standard symfony does not receive the full exception,
 * but rather a FlattenException. While this is great for symfony as a framework,
 * we want to be able to access information of the exception, like the $statusCode in the HttpExceptionInterface.
 *
 * @see HttpExceptionInterface
 * @see ErrorListener
 */
class ErrorHandler implements EventSubscriberInterface
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
    /**
     * @var \Twig\Environment
     */
    private $twig;
    /**
     * @var MasterService
     */
    private $masterService;
    /**
     * @var NodeService
     */
    private $nodeService;
    /**
     * @var PageService
     */
    private $pageService;

    /**
     * @var ContextService
     */
    private $contextService;
    /**
     * @var ViewDataProvider
     */
    private $dataProvider;

    public function __construct(
        \Twig\Environment $twig,
        MasterService $masterService,
        NodeService $nodeService,
        PageService $pageService,
        ViewDataProvider $dataProvider,
        ContextService $contextService
    )
    {
        $this->twig = $twig;
        $this->masterService = $masterService;
        $this->nodeService = $nodeService;
        $this->pageService = $pageService;
        $this->contextService = $contextService;
        $this->dataProvider = $dataProvider;
    }

    public function getResponseForErrorEvent(ExceptionEvent $event)
    {
        $event->setResponse(
            new Response(
                $this->renderErrorNode($event),
                $this->getStatusCode($event)
            )
        );
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['getResponseForErrorEvent', -100],
            ],
        ];
    }

    private function renderErrorNode(ExceptionEvent $event): string
    {
        return $this->twig->render('@FrontasticCatwalkFrontendBundle/render.html.twig', $this->getViewData($event));
    }

    private function getViewData(ExceptionEvent $exceptionEvent): array
    {
        $throwable = $exceptionEvent->getThrowable();
        $context = $this->contextService->createContextFromRequest($exceptionEvent->getRequest());

        try {
            $node = $this->nodeService->get(
                $this->masterService->matchNodeId(new PageMatcherContext([
                    'error' => !$throwable ? true : (object)[
                        'message' => $throwable->getMessage(),
                        'code' => $throwable->getCode(),
                    ],
                ]))
            );
            $page = $this->pageService->fetchForNode($node, $context);

            if (!$context->isProduction()) {
                $node->error = $this->getExceptionInformation($throwable);
            }

            return [
                'node' => $node,
                'page' => $page,
                'data' => $this->dataProvider->fetchDataFor($node, $context, [], $page),
            ];
        } catch (\Throwable $e) {
            return [
                'node' => new Node([
                    'error' => $context->isProduction() ? null : $this->getExceptionInformation($e),
                ]),
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
                                            'tasticId' => 'dummy',
                                            'tasticType' => 'markdown',
                                            'configuration' => new Tastic\Configuration([
                                                'text' => $this->errorTexts,
                                            ]),
                                        ]),
                                    ]),
                                ]),
                            ],
                        ]),
                    ],
                ]),
                'data' => (object)[
                    'stream' => (object)[],
                    'tastic' => (object)[],
                ],
            ];
        }

    }

    private function getStatusCode(ExceptionEvent $event): int
    {
        $throwable = $event->getThrowable();

        if($throwable instanceof HttpExceptionInterface) {
            return $throwable->getStatusCode();
        }

        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    private function getExceptionInformation(\Throwable $exception = null): ?string
    {
        if (!$exception) {
            return '# Unknown Frontastic Error';
        }

        return "# Exception\n" .
            "\n⚠ `". $exception->getMessage() . "`\n" .
            "\n```\n" . implode(
                "\n",
                array_map(
                    function (array $traceLine): string {
                        return $traceLine['file'] . ' +' . $traceLine['line'];
                    },
                    $exception->getTrace()
                )
            ) . "\n```\n";
    }
}
