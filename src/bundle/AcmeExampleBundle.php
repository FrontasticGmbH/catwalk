<?php

namespace Acme\ExampleBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AcmeExampleBundle extends Bundle
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
