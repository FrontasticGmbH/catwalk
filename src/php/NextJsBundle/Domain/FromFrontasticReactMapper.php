<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context as OriginalContext;
use Frontastic\Catwalk\FrontendBundle\Domain\Stream as OriginalStream;
use Frontastic\Catwalk\FrontendBundle\Domain\Cell as OriginalCell;
use Frontastic\Catwalk\FrontendBundle\Domain\Page as OriginalPage;
use Frontastic\Catwalk\FrontendBundle\Domain\Node as OriginalNode;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewData as OriginalViewData;
use Frontastic\Catwalk\FrontendBundle\Domain\Region as OriginalRegion;
use Frontastic\Common\ReplicatorBundle\Domain\Project as OriginalProject;
use Frontastic\Catwalk\FrontendBundle\Domain\Tastic as OriginalTastic;
use Frontastic\Catwalk\FrontendBundle\Domain\Cell\Configuration as OriginalCellConfiguration;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Context;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\DataSourceConfiguration;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\LayoutElement;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Page;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\PageFolder;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Project;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Section;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Tastic;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\LayoutElement\Configuration as LayoutElementConfiguration;

class FromFrontasticReactMapper
{
    const CLASS_MAPPINGS = [
        OriginalContext::class => [
            'target' => Context::class,
            'propertyMappings' => []
        ],
        OriginalStream::class => [
            'target' => DataSourceConfiguration::class,
            'propertyMappings' => [
                'streamId' => 'dataSourceId',
            ]
        ],
        OriginalCell::class => [
            'target' => LayoutElement::class,
            'propertyMappings' => [
                'cellId' => 'layoutElementId',
            ]
        ],
        OriginalPage::class => [
            'target' => Page::class,
            'propertyMappings' => [
                'regions' => 'sections',
            ]
        ],
        OriginalNode::class => [
            'target' => PageFolder::class,
            'propertyMappings' => [
                'nodeId' => 'pageFolderId',
                'isMaster' => 'isDynamic',
                'nodeType' => 'pageFolderType',
                'streams' => 'dataSourceConfigurations',
                'path' => 'ancestorIdsMaterializedPath',
            ]
        ],
        OriginalViewData::class => [
            'target' => PageViewData::class,
            'propertyMappings' => [
                'stream' => 'dataSources',
            ]
        ],
        OriginalProject::class => [
            'target' => Project::class,
            'propertyMappings' => [
                'languages' => 'locales',
                'defaultLanguage' => 'defaultLocale'
            ]
        ],
        OriginalRegion::class => [
            'target' => Section::class,
            'propertyMappings' => [
                'regionId' => 'sectionId',
                'elements' => 'layoutElements',
            ]
        ],
        OriginalTastic::class => [
            'target' => Tastic::class,
            'propertyMappings' => []
        ],
        OriginalCellConfiguration::class => [
            'target' => LayoutElementConfiguration::class,
            'propertyMappings' => []
        ],
    ];

    public function map(object $input): object
    {
        $inputClass = get_class($input);
        $outputClass = $inputClass;

        $output = clone $input;

        if (isset(self::CLASS_MAPPINGS[$inputClass])) {
            $outputClass = self::CLASS_MAPPINGS[$inputClass]['target'];
            $outputPropertyMapping = self::CLASS_MAPPINGS[$inputClass]['propertyMappings'];

            $output = new $outputClass();
        }

        foreach (get_object_vars($input) as $inputPropertyName => $inputPropertyValue) {
            $outputPropertyName = $inputPropertyName;

            if ($inputPropertyValue === null) {
                continue;
            }

            if (isset($outputPropertyMapping[$inputPropertyName])) {
                $outputPropertyName = $outputPropertyMapping[$inputPropertyName];

                if (!property_exists($output, $outputPropertyName)) {
                    throw new \LogicException(sprintf(
                        'Property "%s" does not exist on class "%s", but there is a mapping defined',
                        $outputPropertyName,
                        $outputClass
                    ));
                }
            }

            if (!property_exists($output, $outputPropertyName)) {
                continue;
            }

            $output->$outputPropertyName = $this->mapAny($inputPropertyValue);
        }

        return $output;
    }

    public function mapAny($input)
    {
        if (is_object($input)) {
            return $this->map($input);
        }
        if (is_array($input)) {
            foreach ($input as $key => $arrayValue) {
                $input[$key] = $this->mapAny($arrayValue);
            }
            return $input;
        }
        return $input;
    }
}
