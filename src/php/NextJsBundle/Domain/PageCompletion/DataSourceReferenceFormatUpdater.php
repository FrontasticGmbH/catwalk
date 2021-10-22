<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\PageCompletion;

use Frontastic\Catwalk\NextJsBundle\Domain\Api\TasticFieldValue\DataSourceReference;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldConfiguration;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldVisitor;

class DataSourceReferenceFormatUpdater implements FieldVisitor
{
    public function processField(FieldConfiguration $configuration, $value, array $fieldPath)
    {
        if ($configuration->getType() !== 'stream') {
            return $value;
        }

        return new DataSourceReference([
            // TODO: Needs extension of FieldConfiguration model
            'dataSourceType' => 'TODO: Implement!',
            'dataSourceId' => $value,
        ]);
    }
}
