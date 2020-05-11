<?php

namespace Frontastic\Catwalk\FrontendBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FrontasticCatwalkFrontendBundle extends Bundle
{
    /**
     * Compatibility with QafooLabs/NoFrameworkBundle
     *
     * @return ?string
     */
    public function getParent()
    {
        return null;
    }
}
