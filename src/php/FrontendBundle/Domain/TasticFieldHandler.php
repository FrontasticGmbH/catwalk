<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

/**
 * @deprecated Do not use anymore. There is a newer Version called TasticFieldHandlerV2,
 * which passes the node and page to the field handler as well.
 *
 * @see TasticFieldHandlerV2
 */
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
