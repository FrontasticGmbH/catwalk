<?php

namespace Frontastic\Catwalk\ApiCoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FrontasticCatwalkApiCoreBundle extends Bundle
{
    /**
     * Compatibility with QafooLabs/NoFrameworkBundle
     *
     * @return null|string
     */
    public function getParent()
    {
        return null;
    }
}
