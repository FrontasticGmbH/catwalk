<?php

namespace Frontastic\Catwalk\WirecardBundle\Controller;

use Frontastic\Catwalk\WirecardBundle\Domain\WirecardService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WirecardController extends AbstractController
{
    public function testAction()
    {
        $wirecardService = $this->get(WirecardService::class);

        return ['success' => $wirecardService->test()];
    }
}
