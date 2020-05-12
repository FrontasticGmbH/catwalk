<?php

namespace Frontastic\Catwalk\TwigTasticBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FrontasticCatwalkTwigTasticBundle extends Bundle
{
    /**
     * Compatibility with QafooLabs/NoFrameworkBundle
     *
     * @return ?string
     */
    public function getParent()
    {
        return 'FrontasticCatwalkFrontendBundle';
    }
}
