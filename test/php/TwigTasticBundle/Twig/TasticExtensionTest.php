<?php

namespace Frontastic\Catwalk\TwigTasticBundle\Twig;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

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
        $this->assertSame(
            $className,
            call_user_func_array([new TasticExtension(new Context()), 'classnames'], $parameters)
        );
    }
}
