<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

/**
 * Receives an FieldHandler in V1 and makes it compatible with V2
 */
class TasticFieldHandlerAdapterV2 extends TasticFieldHandlerV2
{
    /**
     * @var TasticFieldHandler
     */
    private $fieldHandler;

    public function __construct(TasticFieldHandler $fieldHandler)
    {
        $this->fieldHandler = $fieldHandler;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->fieldHandler->getType();
    }

    /**
     * @param mixed $fieldValue
     * @return mixed Handled value
     */
    public function handle(Context $context, Node $node, Page $page, $fieldValue)
    {
        return $this->fieldHandler->handle($context, $fieldValue);
    }
}
