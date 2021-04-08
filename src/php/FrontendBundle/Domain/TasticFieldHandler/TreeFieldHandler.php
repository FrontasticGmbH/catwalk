<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\TasticFieldHandler;

use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\TasticFieldHandler;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

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
     * @param Context $context
     * @param mixed $fieldValue
     * @return mixed Handled value
     */
    public function handle(Context $context, $fieldValue)
    {
        return $this->nodeService->getTree(
            (empty($fieldValue['node']) ? null : $fieldValue['node']),
            ((empty($fieldValue['depth']) && (int)$fieldValue['depth'] !== 0) ? null : (int)$fieldValue['depth'])
        );
    }
}
