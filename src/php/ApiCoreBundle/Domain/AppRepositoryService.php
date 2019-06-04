<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Gateway\AppGateway;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadataFactory;
use Doctrine\ORM\Tools\SchemaTool;

class AppRepositoryService
{
    private $entityManager;

    private $sourceDir;

    /**
     * @var \Frontastic\Catwalk\ApiCoreBundle\Gateway\AppGateway
     */
    private $appGateway;

    /**
     * Internal <alert>STATE</alert> - did we have synced the current auto
     * generated custom app code, with the current state of the database?
     *
     * @var boolean
     */
    private $entitiesInSync = false;

    public function __construct(
        EntityManager $entityManager,
        AppGateway $appGateway,
        string $sourceDir = ''
    ) {
        $this->entityManager = $entityManager;
        $this->appGateway = $appGateway;
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

    /**
     * Create data repository for $className.
     *
     * While the custom app is still not synchronized, this will return null.
     * Please make sure your code works accordingly!
     */
    public function getRepository(string $className): ?DataRepository
    {
        $this->ensureEntitiesInSync();

        $className = $this->getFullyQualifiedClassName($className);

        if (!class_exists($className, true)) {
            return null;
        }

        return $this->entityManager->getRepository($className);
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
            'json' => 'text',
        ];

        $templating = new \Twig_Environment(
            new \Twig_Loader_Filesystem([__DIR__ . '/../Resources/views/App/'])
        );

        // @TODO: Map PHP type information for better autocompletion
        file_put_contents(
            $this->sourceDir . '/App/' . $className . '.php',
            $templating->render(
                'dataObject.php.twig',
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

    private function ensureEntitiesInSync(): void
    {
        // @IMPORTANT: Dont' run this command during symfony:bootstrap of "catwalk"
        if (isset($_SERVER['_']) && in_array(basename($_SERVER['_']), ['ant', 'ansible-playbook']) &&
            isset($_SERVER['PWD']) && 'catwalk' == basename($_SERVER['PWD'])) {
            return;
        }

        if ($this->entitiesInSync) {
            return;
        }
        $this->entitiesInSync = true;
        $this->syncEntityClasses();
    }

    private function syncEntityClasses()
    {
        $metaData = $this->loadEntitiesMetaData();

        $maxSequence = $this->appGateway->getHighestSequence();
        // Nothing to sync
        if (strcmp($maxSequence, $metaData['~highest_sequence']) <= 0) {
            return;
        }

        $metaData['~highest_sequence'] = $maxSequence;

        foreach ($this->appGateway->getAll() as $app) {
            if (isset($metaData[$app->identifier]) && $metaData[$app->identifier] == $app->sequence) {
                continue;
            }
            $this->update($app);
            $metaData[$app->identifier] = $app->sequence;
        }

        $this->updateCodeMetaData($metaData);
    }

    private function loadEntitiesMetaData(): array
    {
        if (false === file_exists($this->sourceDir . '/App/code.meta.php')) {
            return ['~highest_sequence' => -1];
        }
        return include $this->sourceDir . '/App/code.meta.php';
    }

    private function updateCodeMetaData(array $metaData): void
    {
        file_put_contents(
            $this->sourceDir . '/App/code.meta.php',
            '<?php return ' . var_export($metaData, true) . ';' . PHP_EOL
        );
    }
}
