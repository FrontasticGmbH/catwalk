<?php

declare(strict_types=1);

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use PHPUnit\Framework\TestCase;

class AppDataFilterTest extends TestCase
{
    public function testFilterAppData(): void
    {
        putenv('filter_app_data=1');

        $filter = new AppDataFilter(['children'], ['_type'], ['[foo][bar]']);

        $result = $filter->filterAppData($this->getAppData());

        self::assertEquals(['children' => [], 'emptyString' => '', 'numeric_key' => [2 => []]], $result);
    }

    public function testFilterAppDataIsInactiveByDefault(): void
    {
        putenv('filter_app_data');

        $appData = $this->getAppData();
        $filter  = new AppDataFilter(['children'], ['_type'], ['[foo][bar]']);

        $result = $filter->filterAppData($appData);

        self::assertEquals($appData, $result);
    }

    private function getAppData(): array
    {
        return [
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
        ];
    }
}
