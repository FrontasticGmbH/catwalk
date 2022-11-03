<?php

namespace Frontastic\Catwalk\FrontendBundle;

use Frontastic\Catwalk\FrontendBundle\DependencyInjection\RoutingCacheCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new RoutingCacheCompilerPass());
    }
}
