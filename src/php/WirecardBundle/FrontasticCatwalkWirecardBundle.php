<?php

namespace Frontastic\Catwalk\WirecardBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FrontasticCatwalkWirecardBundle extends Bundle
{
    public function getParent(): ?string
    {
        return 'FrontasticCatwalkWirecardBundle';
    }
}
