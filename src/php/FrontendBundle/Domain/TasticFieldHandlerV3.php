<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

/**
 * *Experimental* field handler that also receives information about the tastic
 */
abstract class TasticFieldHandlerV3
{
    /**
     * @return string
     */
    abstract public function getType(): string;

    /**
     * @param mixed $fieldValue
     * @return mixed Handled value
     */
    abstract public function handle(Context $context, Node $node, Page $page, Tastic $tastic, $fieldValue);
}
