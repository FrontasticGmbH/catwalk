<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Frontastic\Common\ReplicatorBundle\Domain\Target;

use Frontastic\Catwalk\ApiCoreBundle\Gateway\AppRepositoryGateway;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

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
     * @var Context
     */
    private $context;

    public function __construct(
        AppService $appService,
        AppRepositoryService $appRepositoryService,
        AppRepositoryGateway $appRepositoryGateway,
        Context $context
    ) {
        $this->appService = $appService;
        $this->appRepositoryService = $appRepositoryService;
        $this->appRepositoryGateway = $appRepositoryGateway;
        $this->context = $context;
    }

    public function lastUpdate(): string
    {
        return $this->appRepositoryGateway->getHighestSequence() ?: '0';
    }

    public function replicate(array $updates): void
    {
        file_put_contents('/tmp/repli', 'Received: ' . \json_encode($updates), FILE_APPEND);
        foreach ($updates as $update) {
            $app = $this->appService->getByIdentifier($update['appId']);
            $repository = $this->appRepositoryService->getRepository($app->identifier);
            $properties = $this->appRepositoryService->getProperties($app);
            $entityClass = $this->appRepositoryService->getFullyQualifiedClassName($app->identifier);

            foreach ($this->context->project->languages as $locale) {
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
                            $value = $this->translate($value, $locale, $this->context->project->defaultLanguage);
                        }

                        $data->$property = $value;
                    }
                }
                file_put_contents('/tmp/repli', 'Stored: ' . \json_encode($data), FILE_APPEND);

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

        return '';
    }
}
