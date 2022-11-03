<?php

namespace Frontastic\Catwalk\FrontendBundle\DependencyInjection;

use Frontastic\Catwalk\FrontendBundle\Routing\RoutingConfigCacheFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RoutingCacheCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->getDefinition('router.default')->addMethodCall(
            'setConfigCacheFactory',
            [
                new Reference(RoutingConfigCacheFactory::class)
            ]
        );
    }
}
