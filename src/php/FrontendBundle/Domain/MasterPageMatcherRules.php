<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\UserBundle\Domain\MetaData;

class MasterPageMatcherRules
{
    /**
     * @var string
     */
    public $rulesId;

    /**
     * @var array
     */
    public $rules;

    /**
     * @var string
     */
    public $sequence;

    /**
     * @var MetaData
     */
    public $metaData;
}
