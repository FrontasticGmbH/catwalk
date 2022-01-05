<?php

namespace Frontastic\Catwalk\NextJsBundle\DependencyInjection;

use Frontastic\Catwalk\FrontendBundle\EventListener\Http2LinkListener;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Removes all services from the container which have a tag name "nextjs.remove" on them.
 *
 * IMPORTANT: Only mark services in such a way which are definitely not used in Frontastic Next.js. Usually, these are
 * only event listeners!
 */
class RemoveFrontasticReactServiceCompilerPass implements CompilerPassInterface
{
    const TAG = 'nextjs.remove';

    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds(self::TAG) as $serviceId => $definition) {
            $container->removeDefinition($serviceId);
        }
    }
}
