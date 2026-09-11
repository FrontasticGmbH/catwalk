<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Frontastic\Common\ReplicatorBundle\Domain\Project;
use Frontastic\Common\ReplicatorBundle\Domain\Target;

use Frontastic\Catwalk\ApiCoreBundle\Gateway\AppRepositoryGateway;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Psr\Log\LoggerInterface;

class AppDataTarget implements Target
{
    /**
     * @var AppService
     */
    private $appService;

    /**
     * @var AppRepositoryService
     */
    private $appRepositoryService;

    /**
     * @var AppRepositoryGateway
     */
    private $appRepositoryGateway;

    /**
     * @var Project
     */
    private $project;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        AppService $appService,
        AppRepositoryService $appRepositoryService,
        AppRepositoryGateway $appRepositoryGateway,
        Project $project,
        LoggerInterface $logger
    ) {
        $this->appService = $appService;
        $this->appRepositoryService = $appRepositoryService;
        $this->appRepositoryGateway = $appRepositoryGateway;
        $this->project = $project;
        $this->logger = $logger;
    }

    public function lastUpdate(): string
    {
        return $this->appRepositoryGateway->getHighestSequence() ?: '0';
    }

    /**
     * @param array $updates
     *
     * @todo Refactor to reduce complexity
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function replicate(array $updates): void
    {
        foreach ($updates as $update) {
            $app = $this->appService->getByIdentifier($update['appId']);
            $repository = $this->appRepositoryService->getRepository($app->identifier);

            if ($repository === null) {
                // App structure not fully synchronized, skipping for now
                return;
            }

            $properties = $this->appRepositoryService->getProperties($app);
            $entityClass = $this->appRepositoryService->getFullyQualifiedClassName($app->identifier);

            foreach ($this->project->languages as $locale) {
                $data = $repository->findOneByEvenIfDeleted([
                    'locale' => $locale,
                    'dataId' => $update['dataId'],
                ]) ?: new $entityClass([
                    'locale' => $locale,
                    'dataId' => $update['dataId']
                ]);

                $data->sequence = $update['sequence'];
                $data->isDeleted = $update['isDeleted'];

                foreach ($update['data'] as $property => $value) {
                    if (isset($properties[$property]) &&
                        property_exists($data, $property)) {
                        if (in_array($properties[$property], ['string', 'text', 'markdown'], true)) {
                            $value = $this->translate($value, $locale, $this->project->defaultLanguage);
                        }

                        if (in_array($properties[$property], ['number', 'integer', 'decimal'], true) &&
                            is_array($value)) {
                            $value = reset($value);
                        }

                        if ($this->shouldSkipValue(
                            $app->identifier,
                            $update,
                            $locale,
                            $property,
                            $properties[$property],
                            $value
                        )) {
                            continue;
                        }

                        $data->$property = $value;
                    }
                }

                $repository->store($data);
            }

            try {
                $appRepository = $this->appRepositoryGateway->get($app->identifier);
            } catch (\OutOfBoundsException $e) {
                $appRepository = new AppRepository(['app' => $app->identifier]);
            }
            $appRepository->sequence = $update['sequence'];
            $this->appRepositoryGateway->store($appRepository);
        }
    }

    /**
     * Schema field types the entity code generation does not know are generated as "text" columns, so a structured
     * value for such a field is handed to PDO as is. That triggers an "Array to string conversion" warning, which
     * fails the whole replication in debug environments and stores the string "Array" everywhere else.
     *
     * Those field types are not offered for custom apps by the studio, so app data still using them comes from
     * schemas that predate that restriction. Skip those values instead of corrupting the column, and log them so
     * that the offending schema can be fixed.
     */
    private function shouldSkipValue(
        string $appIdentifier,
        array $update,
        string $locale,
        string $property,
        string $schemaType,
        $value
    ): bool {
        if (isset(AppRepositoryService::DOCTRINE_TYPE_MAP[$schemaType])) {
            return false;
        }

        // Unmapped scalars, like "number" or "instant", are stored in the generated "text" column just fine.
        if (!is_array($value) && !is_object($value)) {
            return false;
        }

        $this->logger->warning(
            'Skipping {valueType} value of app data field "{app}.{property}" for data ID {dataId} in locale ' .
            '{locale}: the schema type "{schemaType}" is not supported for custom apps and cannot be stored in ' .
            'the generated text column: {value}',
            [
                'app' => $appIdentifier,
                'property' => $property,
                'schemaType' => $schemaType,
                'dataId' => $update['dataId'],
                'sequence' => $update['sequence'],
                'locale' => $locale,
                'valueType' => is_object($value) ? get_class($value) : 'array',
                'value' => mb_substr((string) json_encode($value), 0, 1000),
            ]
        );

        return true;
    }

    private function translate($value, string $locale, string $defaultLocale): string
    {
        if (is_object($value)) {
            $value = (array) $value;
        }

        if (!is_array($value)) {
            return (string) $value;
        }

        if (isset($value[$locale])) {
            return (string) $value[$locale];
        }

        if (isset($value[$defaultLocale])) {
            return (string) $value[$defaultLocale];
        }

        return (string) reset($value);
    }
}
