<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Common\ReplicatorBundle\Domain\Project as OriginalProject;
use Kore\DataObject\DataObject;

/**
 * Stripped down version of {@link OriginalProject}.
 */
class Project extends DataObject
{
    /**
     * @var string
     * @required
     */
    public $projectId;

    /**
     * @var string
     * @required
     */
    public $name;

    /**
     * @var string
     * @required
     */
    public $customer;

    /**
     * In the config this is the `secret`.
     *
     * Only used in API hub
     *
     * @removed not relevant to customers
     * @var string
     * @required
     */
    // public $apiKey;

    /**
     * @removed not relevant to customers
     * @var string
     * @required
     */
    // public $previewUrl;

    /**
     * @var string
     * @required
     */
    public $publicUrl;

    /**
     * @removed not relevant to customers
     * @var int
     * @required
     */
    // public $webpackPort;

    /**
     * @removed not relevant to customers
     * @var int
     * @required
     */
    // public $ssrPort;

    /**
     * @var array
     * @required
     */
    public $configuration = [];

    /**
     * @removed this was mainly used by customers to store custom config. It
     *          will not be possible for them to edit this in the future.
     *          Therefore we can remove this and have it replaced by
     *          `projectConfiguration` from Frontatsic studio.
     */
    // public $data = [];

    /**
     * Renamed from $languages
     *
     * @var string[]
     * @required
     */
    public $locales = [];

    /**
     * Renamed from $defaultLanguage
     *
     * @var string
     * @required
     */
    public $defaultLocale;

    /**
     * @removed not relevant to customers
     * @var string[]
     * @required
     */
    // public $projectSpecific = [];

    /**
     * @removed not relevant to customers
     * @var Endpoint[]
     * @required
     */
    // public $endpoints = [];
}
