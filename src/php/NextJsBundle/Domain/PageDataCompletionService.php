<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\TasticService;
use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\Page;

use Frontastic\Catwalk\FrontendBundle\Domain\Tastic as TasticInstance;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Tastic as TasticDefinition;
use Frontastic\Catwalk\NextJsBundle\Domain\PageCompletion\NodeUrlVisitor;
use Frontastic\Common\SpecificationBundle\Domain\ConfigurationSchema;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldVisitor;

class PageDataCompletionService
{
    private TasticService $tasticService;
    private FieldVisitor $fieldVisitor;

    public function __construct(TasticService $tasticService, FieldVisitor $fieldVisitor)
    {
        $this->tasticService = $tasticService;
        $this->fieldVisitor = $fieldVisitor;
    }

    public function completePageData(Page $page, Node $node)
    {
        $this->tasticService->getTasticsMappedByType();

        foreach ($page->regions as $region) {
            foreach ($region->elements as $element) {
                foreach ($element->tastics as $tasticInstance) {
                    $this->completeTasticData($tasticInstance, $node);
                }
            }
        }

    }

    private function completeTasticData(TasticInstance $tasticInstance, Node $node)
    {
        $tasticDefinition = $this->getTasticDefinition($tasticInstance->tasticType);
        if ($tasticDefinition === null) {
            return;
        }

        // TODO: Retain mobile, desktop, ... which are removed during completion
        $schema = ConfigurationSchema::fromSchemaAndConfiguration(
            $tasticDefinition->configurationSchema['schema'],
            (array)$tasticInstance->configuration
        );

        \debug($schema->getCompleteValues($this->fieldVisitor));
    }

    private function getTasticDefinition(string $tasticType): ?TasticDefinition
    {
        // TODO: Cache
        $tasticMap = $this->tasticService->getTasticsMappedByType();
        if (!isset($tasticMap[$tasticType])) {
            return null;
        }
        return $tasticMap[$tasticType];
    }
}
