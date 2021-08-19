<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

class MetaData extends DataObject
{
    /**
     * @var string
     */
    public $author;

    /**
     * @var \DateTimeImmutable
     * @fixme This is currently in the default server timezone and should be converted to UTC. Therefore the doctrine
     *        type has to be changed to offsetdatetime
     */
    public $changed;
}
