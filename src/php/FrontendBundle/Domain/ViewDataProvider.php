<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

class ViewDataProvider
{
    /**
     * @var StreamService
     */
    private $streamService;

    /**
     * @var TasticFieldService
     */
    private $tasticFieldService;

    public function __construct(StreamService $streamService, TasticFieldService $tasticFieldService)
    {
        $this->streamService = $streamService;
        $this->tasticFieldService = $tasticFieldService;
    }

    public function fetchDataFor(Node $node, Context $context, array $streamParameters, Page $page = null): ViewData
    {
        return new ViewData([
            'stream' => (object) $this->streamService->getStreamData($node, $context, $streamParameters),
            'tastic' => (object) ($page !== null ? $this->tasticFieldService->getFieldData($context, $page) : []),
        ]);
    }
}
