<?php

namespace Frontastic\Catwalk\FrontendBundle\Twig;

use Frontastic\Common\JsonSerializer;
use Twig\Extension\AbstractExtension;

class JsonExtension extends AbstractExtension
{
    /**
     * @var JsonSerializer
     */
    private $jsonSerializer;

    public function __construct(JsonSerializer $jsonSerializer)
    {
        $this->jsonSerializer = $jsonSerializer;
    }

    public function getFilters()
    {
        return [
            new \Twig\TwigFilter(
                'frontastic_json_serialize',
                [$this->jsonSerializer, 'serialize']
            ),
        ];
    }
}
