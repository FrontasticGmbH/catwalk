<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\Region;

use Frontastic\Catwalk\FrontendBundle\Domain\Configuration as BaseConfiguration;

/**
 * @type
 */
class Configuration extends BaseConfiguration
{
    /**
     * @var string
     */
    public $flexDirection = "column";

    /**
     * @var string
     */
    public $flexWrap = "nowrap";

    /**
     * @var string
     */
    public $justifyContent = "space-between";

    /**
     * @var string
     */
    public $alignItems = "stretch";

    /**
     * @var string
     */
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
