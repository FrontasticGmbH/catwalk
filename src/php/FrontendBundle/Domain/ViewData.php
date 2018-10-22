<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

class ViewData extends DataObject
{
    /**
     * @var object Hash map of streams
     */
    public $stream;

    /**
     * @var object Hash map of tastic field data
     */
    public $tastic;
}
