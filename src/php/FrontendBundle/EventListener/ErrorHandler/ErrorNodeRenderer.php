<?php


namespace Frontastic\Catwalk\FrontendBundle\EventListener\ErrorHandler;

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
 * Creates the HTML for an error page.
 *
 * If no error master pages exist or something happens while rendering them,
 * we will try to render a default node.
 *
 * This class triggers the SSR and will return HTML which contains the result of SSR + everything to run our PWA.
 */
class ErrorNodeRenderer
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
     * @var ViewDataProvider
     */
    private $dataProvider;

    public function __construct(
        \Twig\Environment $twig,
        MasterService $masterService,
        NodeService $nodeService,
        PageService $pageService,
        ViewDataProvider $dataProvider
    ) {
        $this->twig = $twig;
        $this->masterService = $masterService;
        $this->nodeService = $nodeService;
        $this->pageService = $pageService;
        $this->dataProvider = $dataProvider;
    }

    public function renderErrorNode(Context $context, \Throwable $throwable) : string
    {
        return $this->twig->render(
            '@FrontasticCatwalkFrontendBundle/render.html.twig',
            $this->getViewData($context, $throwable)
        );
    }
    private function getViewData(Context $context, \Throwable $throwable): array
    {
        try {
            $node = $this->nodeService->get(
                $this->masterService->matchNodeId(new PageMatcherContext([
                    'error' => !$throwable ? true : (object)[
                        'message' => $throwable->getMessage(),
                        'code' => $throwable->getCode(),
                    ],
                ]))
            );
            $node->nodeType = 'error';
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
                        return ($traceLine['file'] ?? 'unknown') . ' +' . ($traceLine['line'] ?? '???');
                    },
                    $exception->getTrace()
                )
            ) . "\n```\n";
    }
}
