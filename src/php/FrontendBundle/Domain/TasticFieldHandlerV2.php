<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

abstract class TasticFieldHandlerV2
{
    /**
     * @return string
     */
    abstract public function getType(): string;

    /**
     * @param mixed $fieldValue
     * @return mixed Handled value
     */
    abstract public function handle(Context $context, Node $node, Page $page, $fieldValue);
}
