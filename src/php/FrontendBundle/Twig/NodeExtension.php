<?php

namespace Frontastic\Catwalk\FrontendBundle\Twig;

use Frontastic\Catwalk\ApiCoreBundle\Domain\TasticService;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;

class NodeExtension extends \Twig_Extension
{
    private $nodeService;

    private $tasticService;

    public function __construct(NodeService $nodeService, TasticService $tasticService)
    {
        $this->nodeService = $nodeService;
        $this->tasticService = $tasticService;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_Function('frontastic_tree', [$this->nodeService, 'getTree']),
            new \Twig_Function('completeInformation', [$this, 'completeInformation']),
        ];
    }

    public function completeInformation(array $data): array
    {
        return array_merge(
            [
                'tastics' => $this->tasticService->getAll(),
            ],
            $data
        );
    }
}
