<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\TasticService;

class TasticController
{
    private TasticService $tasticService;

    public function __construct(TasticService $tasticService)
    {
        $this->tasticService = $tasticService;
    }

    public function allAction(): array
    {
        return $this->tasticService->getAll();
    }
}
