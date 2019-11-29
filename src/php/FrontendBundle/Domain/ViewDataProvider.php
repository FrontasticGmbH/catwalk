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
        $tastic = $page !== null ? $this->tasticFieldService->getFieldData($context, $node, $page) : [];
        $streamData = $this->streamService->getStreamData($node, $context, $streamParameters, $page);

        return new ViewData([
            'stream' => (object) $streamData,
            'tastic' => (object) $tastic,
        ]);
    }
}
