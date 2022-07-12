<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

interface StreamHandlerSupplier
{

    public function fetch(): array;

}
