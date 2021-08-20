<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

/**
 * This interface is to model the API used for data source implementations in Frontastic NextJS.
 */
interface DataSourceHandler
{
    public function provideData(
        DataSourceConfiguration $configuration,
        DataSourceContext $context
    ) : DataSourceResult;
}
