<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Doctrine;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Frontastic\Catwalk\FrontendBundle\Domain\Facet;
use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\Page;
use Frontastic\Catwalk\FrontendBundle\Domain\Redirect;
use Frontastic\Catwalk\FrontendBundle\Domain\Schema;

class UndeletedFilter extends SQLFilter
{
    const ENTITY_CLASSES = [
        Facet::class => 'f',
        Node::class => 'n',
        Page::class => 'p',
        Redirect::class => 'rd',
        Schema::class => 's',
    ];

    const APP_DATA_NAMESPACE = 'Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\App';

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (!$this->shouldCareFor($targetEntity)) {
            return "";
        }

        return sprintf(
            '%s.%s_is_deleted = 0',
            $targetTableAlias,
            $this->getTablePrefix($targetEntity)
        );
    }

    private function shouldCareFor(ClassMetadata $entityClass): bool
    {
        if (isset(self::ENTITY_CLASSES[$entityClass->reflClass->name])) {
            return true;
        }

        if ($entityClass->reflClass->getNamespaceName() === self::APP_DATA_NAMESPACE) {
            return true;
        }
        return false;
    }

    private function getTablePrefix(ClassMetaData $entityClass): string
    {
        if ($entityClass->reflClass->getNamespaceName() === self::APP_DATA_NAMESPACE) {
            return 'd';
        }

        return self::ENTITY_CLASSES[$entityClass->name];
    }
}
