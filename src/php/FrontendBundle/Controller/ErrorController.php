<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Debug\Exception\FlattenException;

use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\Page;
use Frontastic\Catwalk\FrontendBundle\Domain\Region;
use Frontastic\Catwalk\FrontendBundle\Domain\Cell;
use Frontastic\Catwalk\FrontendBundle\Domain\Tastic;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

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

    public function errorAction(Context $context, FlattenException $exception = null)
    {
        $masterService = $this->get('Frontastic\Catwalk\FrontendBundle\Domain\MasterService');
        $nodeService = $this->get('Frontastic\Catwalk\FrontendBundle\Domain\NodeService');
        $dataProvider = $this->get('Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider');
        $pageService = $this->get('Frontastic\Catwalk\FrontendBundle\Domain\PageService');

        try {
            $node = $nodeService->get(
                $masterService->matchNodeId(new PageMatcherContext([
                    'error' => !$exception ? true : (object) [
                        'message' => $exception->getMessage(),
                        'code' => $exception->getCode(),
                    ]
                ]))
            );
            $page = $pageService->fetchForNode($node);

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
                                    'tastics' => [
                                        new Tastic([
                                            'tasticId' => '1',
                                            'tasticType' => 'markdown',
                                            'configuration' => new Tastic\Configuration([
                                                'text' => $this->errorTexts,
                                            ]),
                                        ]),
                                    ],
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
}
