<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\PageCompletion;

use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldConfiguration;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldVisitor;

class SelectTranslationVisitor implements FieldVisitor
{
    public function processField(FieldConfiguration $configuration, $value)
    {
        return $value;
    }
}
