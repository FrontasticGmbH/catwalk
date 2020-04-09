<?php

namespace Frontastic\Catwalk\TwigTasticBundle\Twig;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;

class TasticExtensionTest extends \PHPUnit\Framework\TestCase
{
    public function getClassNameData()
    {
        return array(
            [['c-test'], 'c-test'],
            [['c-test', 'c-test-2'], 'c-test c-test-2'],
            [['c-test', 'c-test'], 'c-test'],
            [[' c-test', 'c-test'], 'c-test'],
            [[['c-test'], ['c-test-2' => true]], 'c-test c-test-2'],
            [[['c-test' => false], ['c-test-2' => true]], 'c-test-2'],
            [[null, false, 'bar', 0, ['c-test' => null], ''], 'bar'],
        );
    }

    /**
     * @dataProvider getClassNameData
     */
    public function testClassNames(array $parameters, string $className)
    {
        $contextServiceMock = $this->getMockBuilder(ContextService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $contextServiceMock->expects($this->any())
            ->method('createContextFromRequest')
            ->will($this->returnValue(new Context()));

        $this->assertSame(
            $className,
            call_user_func_array([new TasticExtension($contextServiceMock), 'classnames'], $parameters)
        );
    }
}
