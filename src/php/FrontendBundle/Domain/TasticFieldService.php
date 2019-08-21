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
     * @var TasticFieldHandlerV2[]
     */
    private $fieldHandlers = [];

    /**
     * @var \Frontastic\Catwalk\ApiCoreBundle\Domain\Tastic[string]|null
     */
    private $tasticDefinionMapCache;

    /**
     * @param TasticFieldHandler[]|TasticFieldHandlerV2[] $fieldHandlers
     */
    public function __construct(TasticService $tasticDefinitionService, iterable $fieldHandlers = [], bool $debug = false)
    {
        $this->tasticDefinitionService = $tasticDefinitionService;
        foreach ($fieldHandlers as $fieldHandler) {
            $this->addFieldHandler($fieldHandler);
        }
    }

    /**
     * @param TasticFieldHandler|TasticFieldHandlerV2 $fieldHandler
     * @todo Should we allow multiple field handlers to work as a filter chain?
     */
    private function addFieldHandler($fieldHandler)
    {
        if ($fieldHandler instanceof TasticFieldHandler) {
            $fieldHandler = new TasticFieldHandlerAdapterV2($fieldHandler);
        }

        if (isset($this->fieldHandlers[$fieldHandler->getType()])) {
            throw new \LogicException('Duplicate field handler: "'. $fieldHandler->getType() . '"');
        }
        $this->fieldHandlers[$fieldHandler->getType()] = $fieldHandler;
    }

    public function getFieldData(Context $context, Node $node, Page $page): array
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
                            try {
                                $fieldData = $this->setHandledFieldData(
                                    $node,
                                    $page,
                                    $context,
                                    $fieldData,
                                    $tastic,
                                    $fieldDefinition
                                );
                            } catch (\Throwable $throwable) {
                                // debug($throwable->getMessage());
                            }
                        }
                    }
                }
            }
        }

        return $fieldData;
    }

    private function setHandledFieldData(
        Node $node,
        Page $page,
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
        $fieldHandler = $this->fieldHandlers[$type];

        if (!isset($fieldData[$tastic->tasticId])) {
            $fieldData[$tastic->tasticId] = [];
        }

        $fieldData[$tastic->tasticId][$field] = $fieldHandler->handle(
            $context,
            $node,
            $page,
            ($tastic->configuration->$field !== null
                ? $tastic->configuration->$field
                : $fieldDefinition['default'] ?? null
            )
        );

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
