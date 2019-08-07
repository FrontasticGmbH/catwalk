<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\TasticService;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

class TasticFieldService
{
    /**
     * @var TasticService
     */
    private $tasticDefinitionService;

    /**
     * @var TasticFieldHandler[]
     */
    private $fieldHandlers = [];

    /**
     * @var \Frontastic\Catwalk\ApiCoreBundle\Domain\Tastic[string]|null
     */
    private $tasticDefinionMapCache;

    /**
     * @var bool
     */
    private $debug;

    public function __construct(
        TasticService $tasticDefinitionService,
        iterable $fieldHandlers = [],
        bool $debug = false
    ) {
        $this->tasticDefinitionService = $tasticDefinitionService;
        foreach ($fieldHandlers as $fieldHandler) {
            $this->addFieldHandler($fieldHandler);
        }
        $this->debug = $debug;
    }

    /**
     * @todo Should we allow multiple field handlers to work as a filter chain?
     */
    private function addFieldHandler(TasticFieldHandler $fieldHandler)
    {
        if (isset($this->fieldHandlers[$fieldHandler->getType()])) {
            throw new \LogicException('Duplicate field handler: "'. $fieldHandler->getType() . '"');
        }
        $this->fieldHandlers[$fieldHandler->getType()] = $fieldHandler;
    }

    public function getFieldData(Context $context, Page $page): array
    {
        $tasticDefinitionMap = $this->getTasticDefinitionMap();

        $fieldData = [];

        foreach ($page->regions as $region) {
            /** @var Cell $element */
            foreach ($region->elements as $element) {
                foreach ($element->tastics as $tastic) {
                    if (!isset($tasticDefinitionMap[$tastic->tasticType])) {
                        continue;
                    }

                    /** @var \Frontastic\Catwalk\ApiCoreBundle\Domain\Tastic $definition */
                    $definition = $tasticDefinitionMap[$tastic->tasticType];
                    foreach ($definition->configurationSchema['schema'] as $fieldSet) {
                        foreach ($fieldSet['fields'] as $fieldDefinition) {
                            $fieldData = $this->setHandledFieldData(
                                $context,
                                $fieldData,
                                $tastic,
                                $fieldDefinition
                            );
                        }
                    }
                }
            }
        }

        return $fieldData;
    }

    private function setHandledFieldData(
        Context $context,
        array $fieldData,
        Tastic $tastic,
        array $fieldDefinition
    ): array {
        $type = $fieldDefinition['streamType'] ?? $fieldDefinition['type'];
        if (!isset($this->fieldHandlers[$type])) {
            return $fieldData;
        }

        $field = $fieldDefinition['field'];

        if (!isset($fieldData[$tastic->tasticId])) {
            $fieldData[$tastic->tasticId] = [];
        }

        try {
            $fieldData[$tastic->tasticId][$field] = $this->fieldHandlers[$type]->handle(
                $context,
                ($tastic->configuration->$field !== null
                    ? $tastic->configuration->$field
                    : ($fieldDefinition['default'] ?? null)
                )
            );
        } catch (\Throwable $e) {
            $fieldData[$tastic->tasticId][$field] = (object)[
                'ok' => false,
                'message' => $e->getMessage(),
            ];
            if ($this->debug) {
                $fieldData[$tastic->tasticId][$field]->trace = $e->getTrace();
            }
        }

        return $fieldData;
    }

    /**
     * @return \Frontastic\Catwalk\ApiCoreBundle\Domain\Tastic[]
     */
    private function getTasticDefinitionMap(): array
    {
        if ($this->tasticDefinionMapCache === null) {
            $this->tasticDefinionMapCache = $this->tasticDefinitionService->getTasticsMappedByType();
        }
        return $this->tasticDefinionMapCache;
    }
}
