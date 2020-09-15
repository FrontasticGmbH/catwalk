<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * @type
 */
class Redirect extends DataObject
{
    const TARGET_TYPE_NODE = 'node';
    const TARGET_TYPE_LINK = 'link';

    /**
     * @var string
     * @required
     */
    public $redirectId;

    /**
     * @var string
     * @required
     */
    public $sequence;

    /**
     * @var string
     * @required
     */
    public $path;

    /**
     * @var string
     */
    public $query;

    /**
     * One of TARGET_TYPE_* constants
     *
     * @var string
     * @required
     */
    public $targetType;

    /**
     * @var string
     * @required
     */
    public $target;

    /**
     * @var ?string
     */
    public $language = null;

    /**
     * @var \Frontastic\Backstage\UserBundle\Domain\MetaData
     * @required
     */
    public $metaData;

    /**
     * @var bool
     * @required
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
