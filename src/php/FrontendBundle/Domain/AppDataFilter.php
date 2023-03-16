<?php

declare(strict_types=1);

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Symfony\Component\PropertyAccess\PropertyAccess;

class AppDataFilter
{
    /**
     * @var string[]
     */
    private array $keysToKeepList;

    /**
     * @var string[]
     */
    private array $keysToAlwaysRemoveList;

    /**
     * @var string[]
     */
    private array $propertiesToAlwaysRemoveList;

    /**
     * @param string[] $keysToKeepList
     * @param string[] $keysToAlwaysRemoveList
     * @param string[] $propertiesToAlwaysRemoveList
     */
    public function __construct(
        array $keysToKeepList = [],
        array $keysToAlwaysRemoveList = [],
        array $propertiesToAlwaysRemoveList = []
    ) {
        $this->keysToKeepList = $keysToKeepList;
        $this->keysToAlwaysRemoveList = $keysToAlwaysRemoveList;
        $this->propertiesToAlwaysRemoveList = $propertiesToAlwaysRemoveList;
    }

    public function filterAppData(array $appData): array
    {
        if ($this->appDataShouldBeFiltered()) {
            $appData = $this->filterByPropertyPath($appData);

            return $this->removeNullValuesAndEmptyArrays($appData);
        }

        return $appData;
    }

    private function removeNullValuesAndEmptyArrays(array $data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            if (is_numeric($key) || in_array($key, $this->keysToKeepList, true)) {
                $result[$key] = is_array($value) ? $this->removeNullValuesAndEmptyArrays($value) : $value;
                continue;
            }

            if ($value === null || in_array($key, $this->keysToAlwaysRemoveList, true)) {
                continue;
            }

            if (is_array($value)) {
                $value = $this->removeNullValuesAndEmptyArrays($value);

                if (count($value) === 0) {
                    continue;
                }
            }

            $result[$key] = $value;
        }

        return count($result) > 0 ? $result : [];
    }

    private function filterByPropertyPath(array $appData): array
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach ($this->propertiesToAlwaysRemoveList as $propertyPath) {
            if ($propertyAccessor->isWritable($appData, $propertyPath)) {
                $propertyAccessor->setValue($appData, $propertyPath, null);
            }
        }

        return $appData;
    }

    private function appDataShouldBeFiltered(): bool
    {
        return getenv('filter_app_data') === '1';
    }
}
