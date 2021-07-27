<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\FrontendBundle\Domain\Stream as OriginalStream;
use Kore\DataObject\DataObject;

/**
 * Stripped down version of {@link OriginalStream}.
 */
class DataSourceConfiguration extends DataObject
{
    /**
     * @replaces $streamId
     * @var string
     * @required
     */
    public $dataSourceId;

    /**
     * @var string
     * @required
     */
    public $type;

    /**
     * @var string
     * @required
     */
    public $name;

    /**
     * @var array
     * @required
     */
    public $configuration = [];
}
