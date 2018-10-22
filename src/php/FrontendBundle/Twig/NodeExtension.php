<?php

namespace Frontastic\Catwalk\FrontendBundle\Twig;

use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;

class NodeExtension extends \Twig_Extension
{
    private $nodeService;

    public function __construct(NodeService $nodeService)
    {
        $this->nodeService = $nodeService;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_Function('frontastic_tree', [$this->nodeService, 'getTree']),
        ];
    }
}
