<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\PageCompletion;

use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldConfiguration;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldVisitor;

class NodeReferenceGetterVisitor implements FieldVisitor
{
    /**
     * @var string[]
     */
    private $referencedNodeIds = [];

    /**
     * @return string[]
     */
    public function getReferencedNodeIds(): array
    {
        return $this->referencedNodeIds;
    }

    public function processField(FieldConfiguration $configuration, $value, array $fieldPath)
    {
        if ($configuration->getType() === 'reference' && isset($value['type']) && $value['type'] === 'node') {
            $nodeId = $value['target'] ?? '';
            if ($nodeId !== '') {
                $this->referencedNodeIds[] = $nodeId;
            }
        }
    }
}
