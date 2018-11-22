<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

abstract class TasticFieldHandler
{
    /**
     * @return string
     */
    abstract public function getType(): string;

    /**
     * @param Context $context
     * @param mixed $fieldValue
     * @return mixed Handled value
     */
    abstract public function handle(Context $context, $fieldValue);
}
