<?php

namespace Frontastic\Catwalk\FrontendBundle\Twig;

use Frontastic\Common\JsonSerializer;
use Twig_Extension;

class JsonExtension extends Twig_Extension
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
            new \Twig_Filter(
                'frontastic_json_serialize',
                [$this->jsonSerializer, 'serialize']
            ),
        ];
    }
}
