<?php

namespace Frontastic\Catwalk\FrontendBundle\Twig;

use Frontastic\Catwalk\FrontendBundle\Domain\RenderService;

class RenderExtension extends \Twig_Extension
{
    private $renderService;

    public function __construct(RenderService $renderService)
    {
        $this->renderService = $renderService;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_Function('frontastic_render', [$this->renderService, 'render']),
        ];
    }
}
