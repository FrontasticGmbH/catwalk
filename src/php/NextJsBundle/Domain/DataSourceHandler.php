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
     * @param DataSourceContext $context
     * @return DataSourceResult
     */
    public function provideData(
        DataSourceConfiguration $configuration,
        DataSourceContext $context
    ) : DataSourceResult;
}
