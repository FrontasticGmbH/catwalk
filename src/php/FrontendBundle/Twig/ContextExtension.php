<?php

namespace Frontastic\Catwalk\FrontendBundle\Twig;

use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Common\JsonSerializer;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContextExtension extends \Twig\Extension\AbstractExtension
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    private $serializedContext;

    public function __construct(ContainerInterface $container)
    {
        /*
         * IMPORTANT: We must use the container here, otherwise we try to load
         * the ContextService in context free situations.
         */
        $this->container = $container;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig\TwigFunction('frontastic_context', [$this, 'getContext']),
        ];
    }

    public function getContext()
    {
        if(!isset($this->serializedContext)) {
            $this->serializedContext = $this->serializeContext();
        }
        return $this->serializedContext;
    }

    private function serializeContext()
    {
        $jsonSerializer = $this->container->get(JsonSerializer::class);
        $contextService = $this->container->get(ContextService::class);

        return $jsonSerializer->serialize(
            $contextService->createContextFromRequest()
        );
    }
}
