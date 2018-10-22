<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

abstract class TasticFieldHandler
{
    /**
     * @return string
     */
    abstract public function getType(): string;

    /**
     * @param mixed $fieldValue
     * @return mixed Handled value
     */
    abstract public function handle($fieldValue);
}
