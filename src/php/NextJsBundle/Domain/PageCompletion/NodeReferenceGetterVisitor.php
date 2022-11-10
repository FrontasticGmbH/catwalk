<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\PageCompletion;

use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldConfiguration;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldVisitor;

class NodeReferenceGetterVisitor implements FieldVisitor
{
    public function processField(FieldConfiguration $configuration, $value, array $fieldPath)
    {
        if ($configuration->getType() === 'reference' && isset($value['type']) && $value['type'] === 'node') {
            $result = $value['target'];
        }

        return $result ?? null;
    }
}
