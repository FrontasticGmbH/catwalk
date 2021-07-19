<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\FrontendBundle\Domain\Stream;

/**
 * This interface is to model the API used for data source implementations in Frontastic NextJS.
 */
interface DataSourceHandler
{
    /**
     * @param DataSourceConfiguration $configuration
     * @param object $dataSourceParameters Hashmap of parameters delivered via URL for this data source
     * @param DataSourceContext $context
     * @return DataSourceResult
     */
    public function provideData(
        DataSourceConfiguration $configuration,
        object $dataSourceParameters,
        DataSourceContext $context
    ) : DataSourceResult;
}
