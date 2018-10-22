<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\TasticFieldHandler;

use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\TasticFieldHandler;

class TreeFieldHandler extends TasticFieldHandler
{
    const FIELD_TYPE = 'tree';

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
    public function handle($fieldValue)
    {
        return $this->nodeService->getTree(
            (empty($fieldValue['node']) ? null : $fieldValue['node']),
            (empty($fieldValue['depth']) ? null : $fieldValue['depth'])
        );
    }
}
