<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\TasticFieldHandler;

use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\TasticFieldHandler;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Psr\Log\LoggerInterface;

class TreeFieldHandler extends TasticFieldHandler
{
    const FIELD_TYPE = 'tree';

    /**
     * @var NodeService
     */
    private $nodeService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(NodeService $nodeService, LoggerInterface $logger)
    {
        $this->nodeService = $nodeService;
        $this->logger = $logger;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::FIELD_TYPE;
    }

    /**
     * @param Context $context
     * @param mixed $fieldValue
     * @return mixed Handled value
     */
    public function handle(Context $context, $fieldValue)
    {
        try {
            return $this->nodeService->getTree(
                (empty($fieldValue['node']) ? null : $fieldValue['node']),
                empty($fieldValue['depth']) ? 0 : (int) $fieldValue['depth'],
            );
        } catch (\Throwable $exception) {
            $this->logger->error(
                'Error fetching the node tree with root {root} and depth {depth}: {message}',
                [
                    'root' => $fieldValue['node'] ?? '<null>',
                    'depth' => $fieldValue['depth'] ?? 0,
                    'message' => $exception->getMessage(),
                    'exception' => $exception,
                ]
            );
            return null;
        }
    }
}
