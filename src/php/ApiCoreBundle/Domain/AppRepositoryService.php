<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Comparator;
use Doctrine\DBAL\Schema\ComparatorConfig;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadataFactory;
use Doctrine\ORM\Tools\SchemaTool;
use Frontastic\Catwalk\ApiCoreBundle\Gateway\AppGateway;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;

class AppRepositoryService
{
    const DOCTRINE_TYPE_MAP = [
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

    /**
     * Prefix of the stamp the codegen template renders into every generated
     * file (followed by the template hash). Files without the current stamp
     * were written by a different generator version and get purged.
     */
    const TEMPLATE_HASH_STAMP = 'Generated-Template-Hash: ';

    private $entityManager;

    private $sourceDir;

    /**
     * @var \Frontastic\Catwalk\ApiCoreBundle\Gateway\AppGateway
     */
    private $appGateway;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Filesystem
     */
    private $filesystem;

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
        LoggerInterface $logger,
        Filesystem $filesystem,
        string $sourceDir = ''
    ) {
        $this->entityManager = $entityManager;
        $this->appGateway = $appGateway;
        $this->logger = $logger;
        $this->filesystem = $filesystem;
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
        $tableName = 'app_' . \strtolower($className);
        $properties = $this->getProperties($app);
        $indexes = $app->configurationSchema['indexes'] ?? [];

        $suffix = 'SchemaUpdate' . str_shuffle('abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz');
        $schemaUpdateClassName = $className . $suffix;
        $schemaUpdateFileName = $this->sourceDir . '/App/' . $schemaUpdateClassName . '.php';

        try {
            $this->generateEntityClass(
                $schemaUpdateClassName,
                $tableName,
                $schemaUpdateFileName,
                $properties,
                $indexes
            );
            $this->updateDatabaseSchema($app->name ?? $app->appId ?? $className, $schemaUpdateClassName);
        } finally {
            $this->filesystem->remove($schemaUpdateFileName);
        }

        $this->generateEntityClass(
            $className,
            $tableName,
            $this->sourceDir . '/App/' . $className . '.php',
            $properties,
            $indexes
        );
    }

    /**
     * Create data repository for $className.
     *
     * While the custom app is still not synchronized, this will return null.
     * Please make sure your code works accordingly!
     */
    public function getRepository(string $identifier): ?DataRepository
    {
        $this->ensureEntitiesInSync();

        $className = $this->getFullyQualifiedClassName($identifier);

        if (!class_exists($className, true)) {
            // Sometimes composer is not able to properly autoload these classes for some reason

            $filename = $this->sourceDir . '/App/' . $this->makeClassName($identifier) . '.php';
            if (file_exists($filename)) {
                require_once $filename;

                if (!class_exists($className, false)) {
                    return null;
                }
            } else {
                return null;
            }
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

    private function generateEntityClass(
        string $className,
        string $tableName,
        string $fileName,
        array $properties,
        array $indexes
    ) {
        $templating = new \Twig\Environment(
            new \Twig\Loader\FilesystemLoader([__DIR__ . '/../Resources/views/App/'])
        );

        // @TODO: Map PHP type information for better autocompletion
        $this->filesystem->dumpFile(
            $fileName,
            $templating->render(
                'dataObject.php.twig',
                [
                    'className' => $className,
                    'tableName' => $tableName,
                    'properties' => $properties,
                    'indexes' => $indexes,
                    'doctrineTypeMap' => self::DOCTRINE_TYPE_MAP,
                    'templateHash' => $this->computeTemplateHash(),
                ]
            )
        );
    }

    /**
     * Brings the database table of one custom app entity in line with its
     * mapping.
     *
     * Only the table of the given entity is compared and altered. Every other
     * table in the database is out of scope of this comparison, so it can
     * neither be dropped nor modified here.
     */
    private function updateDatabaseSchema(string $appName, string $className): void
    {
        $metaDataFactory = new ClassMetadataFactory();
        $metaDataFactory->setEntityManager($this->entityManager);

        $entityMetaData = $metaDataFactory->getMetadataFor(
            $this->getFullyQualifiedClassName($className)
        );

        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $schemaManager = $connection->createSchemaManager();

        $tableName = $entityMetaData->getTableName();
        $targetTable = (new SchemaTool($this->entityManager))
            ->getSchemaFromMetadata([$entityMetaData])
            ->getTable($tableName);

        if ($schemaManager->tablesExist([$tableName])) {
            $tableDiff = $this->createComparator($schemaManager)->compareTables(
                $schemaManager->introspectTable($tableName),
                $targetTable
            );
            $schemaSqlQueries = $tableDiff->isEmpty() ? [] : $platform->getAlterTableSQL($tableDiff);
        } else {
            $schemaSqlQueries = $platform->getCreateTableSQL($targetTable);
        }

        foreach ($schemaSqlQueries as $query) {
            $this->logger->info(sprintf(
                'Updating database schema for custom app %s: %s',
                $appName,
                $query
            ));
            $connection->executeStatement($query);
        }
    }

    /**
     * With the report option off, DBAL 4.4 lists a changed index as dropped
     * and added instead of modified and no longer deprecates the comparison.
     * DBAL 4.2 knows neither the option nor the deprecation.
     */
    private function createComparator(AbstractSchemaManager $schemaManager): Comparator
    {
        if (method_exists(ComparatorConfig::class, 'withReportModifiedIndexes')) {
            return $schemaManager->createComparator((new ComparatorConfig())->withReportModifiedIndexes(false));
        }

        return $schemaManager->createComparator();
    }

    private function ensureEntitiesInSync(): void
    {
        // @IMPORTANT: Dont' run this command during symfony:bootstrap of "catwalk"
        if (isset($_SERVER['_']) && in_array(basename($_SERVER['_']), ['ant', 'ansible-playbook']) &&
            '/var/www/frontastic/paas/catwalk/bin/console' == $_SERVER['SCRIPT_FILENAME']) {
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

        // Regenerate all entity classes when the generator itself changed
        // (template or type map) — a deploy does not bump any app sequence,
        // so long-lived environments would otherwise keep outdated files.
        $templateHash = $this->computeTemplateHash();
        $forceRegeneration = $templateHash !== ($metaData['~template_hash'] ?? null);

        // The meta data alone cannot prove the files are current:
        // pre-upgrade releases regenerate entities from their own template
        // but round-trip our meta keys untouched (branch switches, deploy
        // rollbacks). Every generated file therefore carries the hash of
        // the template that produced it; purge files with a missing or
        // foreign stamp before anything can load them. A missing file only
        // makes getRepository() return null — the fresh-deploy state — while
        // a wrong-format file breaks every Doctrine metadata access. This
        // runs before the gateway call on purpose: the purge must also
        // happen when the database is unavailable (cache:clear).
        $purgedFiles = $this->purgeStaleEntityFiles($templateHash);
        $droppedApps = $this->dropAppsWithoutEntityFile($metaData);

        try {
            $maxSequence = $this->appGateway->getHighestSequence();
        } catch (\Exception $e) {
            // We have other problems, ignore that here
            // THis normally happens during cache:clear in catwalk
            return;
        }

        // Nothing to sync
        if (!$forceRegeneration &&
            !$purgedFiles &&
            !$droppedApps &&
            strcmp($maxSequence, $metaData['~highest_sequence']) <= 0
        ) {
            return;
        }

        $metaData['~highest_sequence'] = $maxSequence;
        $metaData['~template_hash'] = $templateHash;

        foreach ($this->appGateway->getAll() as $app) {
            if (!$forceRegeneration &&
                isset($metaData[$app->identifier]) &&
                $metaData[$app->identifier] == $app->sequence
            ) {
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
        // dumpFile() writes to a temp file and rename()s it — after a deploy
        // all workers regenerate at once, and a concurrently included
        // half-written code.meta.php would be a parse error.
        $this->filesystem->dumpFile(
            $this->sourceDir . '/App/code.meta.php',
            '<?php return ' . var_export($metaData, true) . ';' . PHP_EOL
        );
    }

    private function computeTemplateHash(): string
    {
        return hash(
            'sha256',
            file_get_contents(__DIR__ . '/../Resources/views/App/dataObject.php.twig') .
            var_export(self::DOCTRINE_TYPE_MAP, true)
        );
    }

    private function purgeStaleEntityFiles(string $templateHash): bool
    {
        $purged = false;
        foreach (glob($this->sourceDir . '/App/*.php') ?: [] as $file) {
            if (basename($file) === 'code.meta.php') {
                continue;
            }
            $content = (string) file_get_contents($file);
            if (strpos($content, self::TEMPLATE_HASH_STAMP . $templateHash) !== false) {
                continue;
            }
            $this->filesystem->remove($file);
            $purged = true;
        }
        return $purged;
    }

    /**
     * Drop meta entries whose entity file does not exist (just purged or
     * deleted out-of-band), so the sync loop regenerates those apps even
     * though their recorded sequence looks current.
     */
    private function dropAppsWithoutEntityFile(array &$metaData): bool
    {
        $dropped = false;
        foreach (array_keys($metaData) as $identifier) {
            if (strpos($identifier, '~') === 0) {
                continue;
            }
            if (file_exists($this->sourceDir . '/App/' . $this->makeClassName($identifier) . '.php')) {
                continue;
            }
            unset($metaData[$identifier]);
            $dropped = true;
        }
        return $dropped;
    }
}
