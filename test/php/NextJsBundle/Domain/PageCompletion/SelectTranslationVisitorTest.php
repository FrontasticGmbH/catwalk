<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\PageCompletion;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\ReplicatorBundle\Domain\Project;
use Frontastic\Common\SpecificationBundle\Domain\ConfigurationSchema;
use PHPUnit\Framework\TestCase;

class SelectTranslationVisitorTest extends TestCase
{
    private ConfigurationSchema $schema;

    public function setUp(): void
    {
        $this->schema = ConfigurationSchema::fromSchemaAndConfiguration(
            json_decode(file_get_contents(
                __DIR__ . '/_fixtures/translations_schema.json'
            ), true)['schema'],
            json_decode(file_get_contents(
                __DIR__ . '/_fixtures/translations_values.json'
            ), true)
        );
    }

    public function testFetchesSelectedLanguageWhenExists()
    {
        $visitor = new SelectTranslationVisitor(
            $this->contextFixture('de_LI')
        );

        $actualValues = $this->schema->getCompleteValues($visitor);

        $this->assertEquals('German Liechtenstein', $actualValues['fullTranslated']);
    }

    public function testFallbackToDefaultLanguageWhenNotExists()
    {
        $visitor = new SelectTranslationVisitor(
            $this->contextFixture('de_LI')
        );

        $actualValues = $this->schema->getCompleteValues($visitor);

        $this->assertEquals('German Swiss', $actualValues['partiallyTranslated']);
    }

    public function testNonTranslatableValue()
    {
        $visitor = new SelectTranslationVisitor(
            $this->contextFixture('de_LI')
        );

        $actualValues = $this->schema->getCompleteValues($visitor);

        $this->assertEquals('I am Groot', $actualValues['nonTranslatableDefaultText']);
    }

    public function testResolveToExactLocale()
    {
        $visitor = new SelectTranslationVisitor(
            $this->contextFixture('de_DE')
        );

        $actualValues = $this->schema->getCompleteValues($visitor);

        $this->assertEquals('I am a Swiss Groot', $actualValues['translatableLanguageOnly']);
    }

    public function testResolveToLocaleLanguage()
    {
        $visitor = new SelectTranslationVisitor(
            $this->contextFixture('de_CH')
        );

        $actualValues = $this->schema->getCompleteValues($visitor);

        $this->assertEquals('I am a German Groot', $actualValues['translatableLanguageOnly']);
    }

    public function testResolveToNonDefaultLocaleLanguage()
    {
        $visitor = new SelectTranslationVisitor(
            $this->contextFixture('it_IT')
        );

        $actualValues = $this->schema->getCompleteValues($visitor);

        $this->assertEquals('Grootitiano', $actualValues['translatableLanguageOnly']);
    }

    public function testResolveToLanguageFromDefaultLocale()
    {
        $visitor = new SelectTranslationVisitor(
            $this->contextFixture('fr_FR')
        );

        $actualValues = $this->schema->getCompleteValues($visitor);

        $this->assertEquals('I am a German Groot', $actualValues['translatableLanguageOnly']);
    }

    public function testNeitherDefaultNorSelectedLocaleAvailable()
    {
        $visitor = new SelectTranslationVisitor(
            $this->contextFixture('de_CH')
        );

        $actualValues = $this->schema->getCompleteValues($visitor);

        $this->assertEquals('I am an English Groot', $actualValues['randomLanguageOnly']);
    }

    /**
     * @return Context
     */
    private function contextFixture(string $currentLocale): Context
    {
        return new Context([
            'locale' => $currentLocale,
            'project' => new Project([
                'languages' => [
                    'de_CH',
                    'de_LI',
                    'fr_CH',
                    'it_CH',
                ],
                'defaultLanguage' => 'de_CH',
            ])
        ]);
    }
}
