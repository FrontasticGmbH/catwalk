<?php

namespace Frontastic\Catwalk\WirecardBundle\Domain;

use Frontastic\Catwalk\IntegrationTest;

class WirecardServiceIntegrationTest extends IntegrationTest
{
    /** @var WirecardService */
    private $wirecardService;

    /**
     * @before
     */
    public function setupService()
    {
        $this->wirecardService = $this->getContainer()->get(WirecardService::class);
    }

    public function testTestMethod()
    {
        $this->assertTrue($this->wirecardService->test());
    }
}
