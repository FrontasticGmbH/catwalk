<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Tastic;
use Frontastic\Catwalk\ApiCoreBundle\Domain\TasticService;
use Psr\Log\LoggerInterface;

class TasticFieldService
{
    /**
     * @var TasticService
     */
    private $tasticDefinitionService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var TasticFieldHandlerV2[]
     */
    private $fieldHandlers = [];

    /**
     * @var Tastic[string]|null
     */
    private $tasticDefinitionMapCache;

    /**
     * @param TasticFieldHandler[]|TasticFieldHandlerV2[] $fieldHandlers
     */
    public function __construct(
        TasticService $tasticDefinitionService,
        LoggerInterface $logger,
        iterable $fieldHandlers = []
    ) {
        $this->tasticDefinitionService = $tasticDefinitionService;
        $this->logger = $logger;
        foreach ($fieldHandlers as $fieldHandler) {
            $this->addFieldHandler($fieldHandler);
        }
    }

    public function getFieldData(Context $context, Node $node, Page $page): array
    {
        $tasticDefinitionMap = $this->getTasticDefinitionMap();

        $handledFieldData = [];

        foreach ($page->regions as $region) {
            foreach ($region->elements as $element) {
                foreach ($element->tastics as $tastic) {
                    if (!isset($tasticDefinitionMap[$tastic->tasticType])) {
                        continue;
                    }

                    /** @var Tastic $definition */
                    $definition = $tasticDefinitionMap[$tastic->tasticType];
                    $currentTasticFieldData = [];
                    foreach ($definition->configurationSchema['schema'] as $fieldSet) {
                        $currentTasticFieldData =
                            $this->handleFieldDefinitions(
                                $context,
                                $node,
                                $page,
                                $fieldSet['fields'],
                                $currentTasticFieldData,
                                (array)$tastic->configuration
                            );
                    }
                    if (!empty($currentTasticFieldData)) {
                        $handledFieldData[$tastic->tasticId] = $currentTasticFieldData;
                    }
                }
            }
        }

        return $handledFieldData;
    }

    /**
     * @param TasticFieldHandler|TasticFieldHandlerV2 $fieldHandler
     */
    private function addFieldHandler($fieldHandler)
    {
        if ($fieldHandler instanceof TasticFieldHandler) {
            $fieldHandler = new TasticFieldHandlerAdapterV2($fieldHandler);
        }

        if (isset($this->fieldHandlers[$fieldHandler->getType()])) {
            throw new \LogicException('Duplicate field handler: "' . $fieldHandler->getType() . '"');
        }
        $this->fieldHandlers[$fieldHandler->getType()] = $fieldHandler;
    }

    /**
     * Handles the field definitions and takes care of recursively handling groups inside these fields. While
     * looping over the structure, the handledFieldData array gets filled if necessary and will be returned afterwards.
     */
    private function handleFieldDefinitions(
        Context $context,
        Node $node,
        Page $page,
        array $fieldDefinitions,
        array $handledFieldData,
        array $configuration
    ): array {
        foreach ($fieldDefinitions as $fieldDefinition) {
            $handledFieldData = $this->handleFieldDefinition(
                $context,
                $node,
                $page,
                $fieldDefinition,
                $handledFieldData,
                $configuration
            );
        }
        return $handledFieldData;
    }

    private function handleFieldDefinition(
        Context $context,
        Node $node,
        Page $page,
        array $fieldDefinition,
        array $handledFieldData,
        array $configuration
    ): array {
        if (!array_key_exists('field', $fieldDefinition) ||
            !array_key_exists('type', $fieldDefinition)) {
            return $handledFieldData;
        }

        $fieldName = $fieldDefinition['field'];
        $fieldType = $fieldDefinition['type'];
        $fieldValue = $this->getFieldValue($fieldDefinition, $configuration);

        // check if field is of type group and then recursively handle the group's fieldset.
        if ($fieldType === 'group') {
            if (!is_array($fieldValue) || !array_key_exists('fields', $fieldDefinition)) {
                return $handledFieldData;
            }
            $handledFieldData[$fieldName] = [];
            foreach ($fieldValue as $groupElementConfiguration) {
                $handledFieldData[$fieldName][] = $this->handleFieldDefinitions(
                    $context,
                    $node,
                    $page,
                    $fieldDefinition['fields'],
                    [],
                    $groupElementConfiguration
                );
            }
        } else {
            try {
                $streamType = $fieldDefinition['streamType'] ?? $fieldType;
                if (!array_key_exists($streamType, $this->fieldHandlers)) {
                    return $handledFieldData;
                }

                $handledFieldData[$fieldName] =
                    $this->fieldHandlers[$streamType]->handle($context, $node, $page, $fieldValue);
            } catch (\Throwable $throwable) {
                $this->logger->error(
                    'Error in custom field handler: {message}',
                    [
                        'message' => $throwable->getMessage(),
                        'exception' => $throwable,
                    ]
                );
                \debug($throwable->getMessage());
            }
        }

        return $handledFieldData;
    }

    /**
     * @return Tastic[]
     */
    private function getTasticDefinitionMap(): array
    {
        if ($this->tasticDefinitionMapCache === null) {
            $this->tasticDefinitionMapCache = $this->tasticDefinitionService->getTasticsMappedByType();
        }
        return $this->tasticDefinitionMapCache;
    }

    /**
     * @param $fieldDefinition
     * @param array $configuration
     * @return ?mixed
     */
    private function getFieldValue(array $fieldDefinition, array $configuration)
    {
        $fieldName = $fieldDefinition['field'];

        return array_key_exists($fieldName, $configuration)
            ? $configuration[$fieldName]
            : $fieldDefinition['default'] ?? null;
    }
}
