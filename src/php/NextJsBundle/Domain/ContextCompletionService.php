<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context as OriginalContext;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Context;
use Frontastic\Catwalk\NextJsBundle\Domain\PageCompletion\FieldVisitorFactory;
use Frontastic\Common\SpecificationBundle\Domain\ConfigurationSchema;

class ContextCompletionService
{
    private FieldVisitorFactory $fieldVisitorFactory;

    public function __construct(FieldVisitorFactory $fieldVisitorFactory)
    {
        $this->fieldVisitorFactory = $fieldVisitorFactory;
    }

    public function completeContextData(Context $context, OriginalContext $originalContext): Context
    {
        $projectConfigurationSchema = ConfigurationSchema::fromSchemaAndConfiguration(
            $originalContext->projectConfigurationSchema,
            $context->projectConfiguration
        );
        $context->projectConfiguration =
            $projectConfigurationSchema->getCompleteValues(
                $this->fieldVisitorFactory->createProjectConfigurationDataVisitor($originalContext)
            );

        return $context;
    }
}
