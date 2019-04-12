<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;
use Symfony\Component\HttpFoundation\ParameterBag;

class Redirect extends DataObject
{
    const TARGET_TYPE_NODE = 'node';
    const TARGET_TYPE_LINK = 'link';

    /**
     * @var string
     */
    public $redirectId;

    /**
     * @var string
     */
    public $sequence;

    /**
     * @var string
     */
    public $path;

    /**
     * @var string
     */
    public $query;

    /**
     * @var string One of TARGET_TYPE_* constants
     */
    public $targetType;

    /**
     * @var string
     */
    public $target;

    /**
     * @var \Frontastic\Backstage\UserBundle\Domain\MetaData
     */
    public $metaData;

    /**
     * @var bool
     */
    public $isDeleted = false;

    public function getQueryParameters(): ParameterBag
    {
        $paremters = [];
        if ($this->query !== null && $this->query !== '') {
            parse_str($this->query, $paremters);
        }

        return new ParameterBag($paremters);
    }
}
