<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ContextInContainerDeprecationProviderTest extends TestCase
{
    /**
     * @var ContextService|MockObject
     */
    private $contextServiceMock;

    /**
     * @var ContextInContainerDeprecationProvider
     */
    private $contextProvider;

    public function setUp(): void
    {
        $this->contextServiceMock = $this->getMockBuilder(ContextService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->contextProvider = new ContextInContainerDeprecationProvider($this->contextServiceMock);
    }

    public function testProvideContextThrowsExceptionInDevelopment()
    {
        $this->contextServiceMock->expects($this->any())
            ->method('createContextFromRequest')
            ->will($this->returnValue(
                new Context(['environment' => 'dev'])
            ));

        $this->expectException('\Frontastic\Catwalk\ApiCoreBundle\Exception\DeprecationException');
        $this->contextProvider->provideContext();
    }

    public function testProvideContextReturnsContextInProduction()
    {
        $this->contextServiceMock->expects($this->any())
            ->method('createContextFromRequest')
            ->will($this->returnValue(
                new Context(['environment' => 'prod'])
            ));

        $actualContext = $this->contextProvider->provideContext();

        $this->assertInstanceOf(Context::class, $actualContext);
    }
}
