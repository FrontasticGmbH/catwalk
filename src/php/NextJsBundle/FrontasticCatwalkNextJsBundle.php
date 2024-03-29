<?php

namespace Frontastic\Catwalk\NextJsBundle;

use Frontastic\Catwalk\NextJsBundle\DependencyInjection\RemoveFrontasticReactServiceCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FrontasticCatwalkNextJsBundle extends Bundle
{
    /**
     * @return ?string
     */
    public function getParent()
    {
        return null;
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container); // TODO: Change the autogenerated stub

        $container->addCompilerPass(new RemoveFrontasticReactServiceCompilerPass());
    }
}
