<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Common\ReplicatorBundle\Domain\Target;

use Frontastic\Catwalk\FrontendBundle\Gateway\FacetGateway;

class FacetService implements Target
{
    /**
     * @var FacetGateway
     */
    private $facetGateway;

    public function __construct(FacetGateway $facetGateway)
    {
        $this->facetGateway = $facetGateway;
    }

    public function lastUpdate(): string
    {
        return $this->facetGateway->getHighestSequence();
    }

    public function replicate(array $updates): void
    {
        foreach ($updates as $update) {
            try {
                $facet = $this->facetGateway->getEvenIfDeleted($update['facetId']);
            } catch (\OutOfBoundsException $e) {
                $facet = new Facet();
                $facet->facetId = $update['facetId'];
            }

            $facet = $this->fill($facet, $update);
            $this->facetGateway->store($facet);
        }
    }

    public function fill(Facet $facet, array $data): Facet
    {
        $facet->sequence = $data['sequence'];
        $facet->attributeId = $data['attributeId'];
        $facet->attributeType = $data['attributeType'];
        $facet->sort = $data['sort'];
        $facet->isEnabled = (bool)$data['isEnabled'];
        $facet->label = (!empty($data['label']) ? (object)$data['label'] : null);
        $facet->urlIdentifier = (!empty($data['urlIdentifier']) ? $data['urlIdentifier'] : null);
        $facet->facetOptions = (!empty($data['facetOptions']) ? (object)$data['facetOptions'] : null);
        $facet->metaData = $data['metaData'];
        $facet->isDeleted = (bool)$data['isDeleted'];

        return $facet;
    }

    public function get(string $facetId): Facet
    {
        return $this->facetGateway->get($facetId);
    }

    /**
     * @return Facet[]
     */
    public function getEnabled(): array
    {
        return $this->facetGateway->getEnabled();
    }
}
