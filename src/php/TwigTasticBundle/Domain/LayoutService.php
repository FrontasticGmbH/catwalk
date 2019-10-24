<?php

namespace Frontastic\Catwalk\TwigTasticBundle\Domain;

use QafooLabs\MVC\Exception\UnauthenticatedLayoutException;
use QafooLabs\MVC\TokenContext;

use Frontastic\Catwalk\TwigTasticBundle\Gateway\LayoutGateway;

class LayoutService
{
    /**
     * @var LayoutGateway
     */
    private $layoutGateway;

    public function __construct(LayoutGateway $layoutGateway)
    {
        $this->layoutGateway = $layoutGateway;
    }

    public function getAll(): array
    {
        return $this->layoutGateway->fetchAll();
    }

    public function get(string $layoutId): Layout
    {
        return $this->layoutGateway->get($layoutId);
    }

    public function store(Layout $layout): Layout
    {
        return $this->layoutGateway->store($layout);
    }

    public function remove(Layout $layout): void
    {
        $this->layoutGateway->remove($layout);
    }
}
