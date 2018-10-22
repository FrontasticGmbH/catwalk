<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Symfony\Component\Templating\EngineInterface;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataFactory;
use Doctrine\ORM\Tools\SchemaTool;

class AppRepositoryService
{
    private $templating;

    private $entityManager;

    private $sourceDir;

    public function __construct(EngineInterface $templating, EntityManager $entityManager, string $sourceDir = '')
    {
        $this->templating = $templating;
        $this->entityManager = $entityManager;
        $this->sourceDir = $sourceDir ?: __DIR__;
    }

    public function getProperties(App $app): array
    {
        $properties = [];
        foreach ($app->configurationSchema['schema'] as $fieldGroup) {
            foreach ($fieldGroup['fields'] as $field) {
                if (!isset($field['field'])) {
                    continue;
                }

                $properties[$field['field']] = $field['type'];
            }
        }

        return $properties;
    }

    public function update(App $app)
    {
        $className = $this->makeClassName($app->configurationSchema['identifier']);
        $this->generateEntityClass(
            $className,
            $this->getProperties($app),
            $app->configurationSchema['indexes'] ?? []
        );
        $this->updateDatabaseSchema($className);
    }

    public function getRepository(string $className): DataRepository
    {
        return $this->entityManager->getRepository(
            $this->getFullyQualifiedClassName($className)
        );
    }

    private function makeClassName(string $identifier): string
    {
        return ucfirst(preg_replace('([^A-Za-z]+)', '', ucwords($identifier)));
    }

    public function getFullyQualifiedClassName(string $identifier): string
    {
        return 'Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\App\\' . $this->makeClassName($identifier);
    }

    private function generateEntityClass(string $className, array $properties, array $indexes)
    {
        $doctrineTypeMap = [
            'string' => 'string',
            'text' => 'text',
            'integer' => 'integer',
            'markdown' => 'text',
            'decimal' => 'float',
            'boolean' => 'boolean',
            'enum' => 'string',
            'stream' => 'object',
            'node' => 'object',
            'media' => 'object',
            'group' => 'object',
        ];

        // @TODO: Map PHP type information for better autocompletion

        file_put_contents(
            $this->sourceDir . '/App/' . $className . '.php',
            $this->templating->render(
                '@FrontasticCatwalkApiCore/App/dataObject.php.twig',
                [
                    'className' => $className,
                    'properties' => $properties,
                    'indexes' => $indexes,
                    'doctrineTypeMap' => $doctrineTypeMap,
                ]
            )
        );
    }

    private function updateDatabaseSchema(string $className)
    {
        $metaDataFactory = new ClassMetadataFactory();
        $metaDataFactory->setEntityManager($this->entityManager);

        $entityMetaData = $metaDataFactory->getMetadataFor(
            $this->getFullyQualifiedClassName($className)
        );

        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->updateSchema([$entityMetaData], true);
    }
}
