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
     * @var string
     * @required
     */
    // public $apiKey;

    /**
     * @var string
     * @required
     * @fixme does a customer ever need this?
     */
    public $previewUrl;

    /**
     * @var string
     * @required
     * @fixme does a customer ever need this?
     */
    public $publicUrl;

    /**
     * @var int
     * @required
     */
    // public $webpackPort;

    /**
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
     * Additional external project data from sources like tideways. Does not
     * follow any defined schema.
     *
     * @var array
     * @required
     */
    public $data = [];

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
     * @var string[]
     * @required
     */
    // public $projectSpecific = [];

    /**
     * @var Endpoint[]
     * @required
     */
    // public $endpoints = [];
}
