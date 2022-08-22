<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

interface StreamHandlerSupplier
{
    public function get(string $streamType): StreamHandlerV2;
}
