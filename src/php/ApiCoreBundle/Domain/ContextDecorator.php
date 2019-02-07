<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Symfony\Component\HttpFoundation\Request;

interface ContextDecorator
{
    public function decorate(Context $context): Context;
}
