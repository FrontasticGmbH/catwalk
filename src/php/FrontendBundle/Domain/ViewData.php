<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

/**
 * @type
 */
class ViewData extends DataObject
{
    /**
     * Hash map of streams
     *
     * @var object
     */
    public $stream;

    /**
     * Hash map of tastic field data
     *
     * @var object
     */
    public $tastic;
}
