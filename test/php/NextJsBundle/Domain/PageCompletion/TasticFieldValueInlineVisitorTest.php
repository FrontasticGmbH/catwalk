<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\PageCompletion;

use Frontastic\Common\SpecificationBundle\Domain\ConfigurationSchema;

use PHPUnit\Framework\TestCase;

class TasticFieldValueInlineVisitorTest extends TestCase
{
    public function testDoesNotPolluteGroupFields()
    {
        $schema = ConfigurationSchema::fromSchemaAndConfiguration(
            json_decode(file_get_contents(
                __DIR__ . '/_fixtures/inline_tastic_values_group_schema.json'
            ), true)['schema'],
            json_decode(file_get_contents(
                __DIR__ . '/_fixtures/inline_tastic_values_group_values.json'
            ), true)
        );

        $visitor = new TasticFieldValueInlineVisitor(\json_decode(file_get_contents(
            __DIR__ . '/_fixtures/inline_tastic_values_group_tasticfieldvalues.json',
            ), true
        ));

        $actualValues = $schema->getCompleteValues($visitor);

        $this->assertArrayNotHasKey('studioValue', $actualValues['gropies']);
        $this->assertArrayNotHasKey('handledValue', $actualValues['gropies']);
    }

}
