<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\Region;

use Frontastic\Catwalk\FrontendBundle\Domain\Configuration as BaseConfiguration;

class Configuration extends BaseConfiguration
{
    public $flexDirection = "column";
    public $flexWrap = "nowrap";
    public $justifyContent = "space-between";
    public $alignItems = "stretch";
    public $alignContent = "space-between";

    public function getStyle(): string
    {
        $styles = [
            ['property' => 'display', 'value' => 'flex'],
            ['property' => 'flex-direction', 'value' => $this->flexDirection],
            ['property' => 'flex-wrap', 'value' => $this->flexWrap],
            ['property' => 'justify-content', 'value' => $this->justifyContent],
            ['property' => 'align-items', 'value' => $this->alignItems],
            ['property' => 'align-content', 'value' => $this->alignContent],
        ];

        return join('; ', array_map(
            function (array $style): string {
                return sprintf(
                    '%s: %s',
                    $style['property'],
                    $style['value']
                );
            },
            $styles
        ));
    }
}
