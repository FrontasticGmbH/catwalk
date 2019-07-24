<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\TasticFieldHandler;

use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Page;
use Frontastic\Catwalk\FrontendBundle\Domain\TasticFieldHandlerV2;

/**
 * Passes the path to the current node to the tastic as an array of Node domain models.
 */
class BreadcrumbFieldHandler extends TasticFieldHandlerV2
{
    const FIELD_TYPE = 'breadcrumb';

    /**
     * @var NodeService
     */
    private $nodeService;

    public function __construct(NodeService $nodeService)
    {
        $this->nodeService = $nodeService;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::FIELD_TYPE;
    }

    /**
     * @param mixed $fieldValue
     * @return mixed Handled value
     */
    public function handle(Context $context, Node $node, Page $page, $fieldValue)
    {
        $nodesPathArray = [];

        $nodeIdsPathArray = explode("/", $node->path);

        foreach ($nodeIdsPathArray as $nodeId) {
            if (trim($nodeId) === '') {
                // skip empty segments
                continue;
            }

            $nodesPathArray[] = $this->nodeService->get($nodeId);
        }

        return $nodesPathArray;
    }
}
