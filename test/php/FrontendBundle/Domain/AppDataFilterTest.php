<?php

declare(strict_types=1);

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\FrontendBundle\Domain\AppDataFilter;
use PHPUnit\Framework\TestCase;

class AppDataFilterTest extends TestCase
{
    public function testFilterAppData(): void
    {
        $filter = new AppDataFilter(['children'], ['_type'], ['[foo][bar]']);

        $result = $filter->filterAppData([
            'foo' => [
                'bar' => 'baz',
            ],
            'children' => [],
            '_type' => 'TestType',
            'nullValue' => null,
            'emptyString' => '',
            'numeric_key' => [
                2 => [],
            ],
        ]);

        self::assertEquals(['children' => [], 'emptyString' => '', 'numeric_key' => [2 => []]], $result);
    }
}
