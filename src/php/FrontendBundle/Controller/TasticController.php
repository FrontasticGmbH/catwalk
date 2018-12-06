<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\TasticService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TasticController extends Controller
{
    public function allAction(): array
    {
        $tasticService = $this->get(TasticService::class);

        return $tasticService->getAll();
    }
}
