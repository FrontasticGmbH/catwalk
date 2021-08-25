<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Frontastic\Catwalk\FrontendBundle\Domain\Stream as OriginalStream;
use Kore\DataObject\DataObject;

/**
 * Stripped down version of {@link OriginalStream}.
 * @type
 */
class DataSourceConfiguration extends DataObject
{
    /**
     * @replaces $streamId
     * @var string
     * @required
     */
    public string $dataSourceId;

    /**
     * @var string
     * @required
     */
    public string $type;

    /**
     * @var string
     * @required
     */
    public string $name;

    /**
     * @var array
     * @required
     */
    public $configuration = [];
}
